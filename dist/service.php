<?php

$service = new service(/**
 * Вставьте свой аккаунт\идентификатор для интеграции
 * Put your account for integration here
 */ 'cdek-login',

    /**
     * Вставьте свой пароль для интеграции
     * Put your password for integration here
     */ 'cdek-pass');
$service->process($_GET, file_get_contents('php://input'));

class service
{
    const WIDGET_VERSION = '3.12.1';

    /**
     * @var string Auth login
     */
    private $login;

    /**
     * @var string Auth pwd
     */
    private $secret;
    /**
     * @var string Base Url for API 2.0 Production
     */
    private $baseUrl;
    /**
     * @var string Auth Token
     */
    private $authToken;
    /**
     * @var array Data From Request
     */
    private $requestData;
    /** @var array Request metrics */
    private $metrics;

    public function __construct($login, $secret, $baseUrl = 'https://api.cdek.ru/v2')
    {
        $this->login = $login;
        $this->secret = $secret;
        $this->baseUrl = $baseUrl;
        $this->metrics = array();
    }

    public function process($requestData, $body)
    {
        $time = $this->startMetrics();

        $this->requestData = array_merge($requestData, json_decode($body, true) ?: array());

        if (!isset($this->requestData['action'])) {
            $this->sendValidationError('Action is required');
        }

        switch ($this->requestData['action']) {
            case 'offices':
                $this->getAuthToken();

                $result = $this->getOffices();

                $this->sendResponse($result, $time);
            case 'calculate':
                $this->getAuthToken();

                $result = $this->calculate();

                $this->sendResponse($result, $time);
            case 'byCoordinate':
                $this->getAuthToken();

                $result = $this->getOfficesByCoordinates();

                $this->sendResponse($result, $time);
            default:
                $this->sendValidationError('Unknown action');
        }
    }

    private function sendValidationError($message)
    {
        http_response_code(400);
        header('Content-Type: application/json');
        header('X-Service-Version: ' . self::WIDGET_VERSION);
        echo json_encode(array('message' => $message));
        exit();
    }

    private function getAuthToken()
    {
        $time = $this->startMetrics();

        $token = $this->httpRequest('oauth/token', array(
            'grant_type' => 'client_credentials',
            'client_id' => $this->login,
            'client_secret' => $this->secret,
        ), true);

        $this->endMetrics('auth', 'Server Auth Time', $time);

        $result = json_decode($token['result'], true);

        if (!isset($result['access_token'])) {
            throw new RuntimeException('Server not authorized to CDEK API');
        }

        $this->authToken = $result['access_token'];
    }

    private function startMetrics()
    {
        return function_exists('hrtime') ? hrtime(true) : microtime(true);
    }

    private function httpRequest($method, $data, $useFormData = false, $useJson = false)
    {
        $ch = curl_init("$this->baseUrl/$method");

        $headers = array(
            'Accept: application/json',
            'X-App-Name: widget_pvz',
            'X-App-Version: ' . self::WIDGET_VERSION
        );

        if ($this->authToken) {
            $headers[] = "Authorization: Bearer $this->authToken";
        }

        if ($useFormData) {
            curl_setopt_array($ch, array(
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => $data,
            ));
        } elseif ($useJson) {
            $headers[] = 'Content-Type: application/json';
            curl_setopt_array($ch, array(
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => json_encode($data),
            ));
        } else {
            curl_setopt($ch, CURLOPT_URL, "$this->baseUrl/$method?" . http_build_query($data));
        }

        curl_setopt_array($ch, array(
            CURLOPT_USERAGENT => 'widget/' . self::WIDGET_VERSION,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER => true,
        ));

        $response = curl_exec($ch);

        if ($response === false) {
            throw new RuntimeException(curl_error($ch), curl_errno($ch));
        }

        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $headers = substr($response, 0, $headerSize);
        $result = substr($response, $headerSize);

        $addedHeaders = $this->getHeaderValue($headers);

        return array('result' => $result, 'addedHeaders' => $addedHeaders, 'httpCode' => $httpCode);
    }

    private function getHeaderValue($headers)
    {
        $headerLines = explode("\r\n", $headers);
        return array_filter($headerLines, static function ($line) {
            return !empty($line) && stripos($line, 'X-') !== false;
        });
    }

    private function endMetrics($metricName, $metricDescription, $start)
    {
        $this->metrics[] = array(
            'name' => $metricName,
            'description' => $metricDescription,
            'time' => round(function_exists('hrtime') ? (hrtime(true) - $start) / 1e+6 : (microtime(true) - $start) * 1000,
                2),
        );
    }

    private function sendResponse($data, $start)
    {
        http_response_code(!empty($data['httpCode']) ? $data['httpCode'] : 200);
        header('Content-Type: application/json');
        header('X-Service-Version: ' . self::WIDGET_VERSION);
        if (!empty($data['addedHeaders'])) {
            foreach ($data['addedHeaders'] as $header) {
                header($header);
            }
        }

        $this->endMetrics('total', 'Total Time', $start);

        if (!empty($this->metrics)) {
            header('Server-Timing: ' . array_reduce($this->metrics, function ($c, $i) {
                    return $c . $i['name'] . ';desc="' . $i['description'] . '";dur=' . $i['time'] . ',';
                }, ''));
        }

        echo $data['result'];

        exit();
    }

    protected function getOffices()
    {
        $time = $this->startMetrics();
        $result = $this->httpRequest('deliverypoints', $this->requestData);

        $this->endMetrics('office', 'Offices Request', $time);
        return $result;
    }

    protected function calculate()
    {
        $time = $this->startMetrics();
        $result = $this->httpRequest('calculator/tarifflist', $this->requestData, false, true);

        $this->endMetrics('calc', 'Calculate Request', $time);
        return $result;
    }

    protected function getOfficesByCoordinates()
    {
        $time = $this->startMetrics();
        $result = $this->httpRequest('deliverypoints/byPolygons', $this->requestData);

        $this->endMetrics('officeByCoordinate', 'Offices By Coordinates Request', $time);
        return $result;
    }
}

