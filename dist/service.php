<?php

(new service(
    /**
     * Вставьте свой аккаунт\идентификатор для интеграции
     * Put your account for integration here
     */ 'cdek-login',

    /**
     * Вставьте свой пароль для интеграции
     * Put your password for integration here
     */ 'cdek-pass'))->process($_GET, file_get_contents('php://input'));

class service
{
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

    public function __construct($login, $secret, $baseUrl = 'https://api.cdek.ru/v2')
    {
        $this->login = $login;
        $this->secret = $secret;
        $this->baseUrl = $baseUrl;
    }

    public function process($requestData, $body)
    {
        $this->requestData = array_merge($requestData, json_decode($body, true) ?: []);

        if (!isset($this->requestData['action'])) {
            $this->sendValidationError('Action is required');
        }

        $this->getAuthToken();

        switch ($this->requestData['action']) {
            case 'offices':
                $this->sendResponse($this->getOffices());
            case 'calculate':
                $this->sendResponse($this->calculate());
            default:
                $this->sendValidationError('Unknown action');
        }
    }

    private function sendValidationError($message)
    {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['message' => $message]);
        exit();
    }

    private function getAuthToken()
    {
        $result = $this->httpRequest('oauth/token', [
            'grant_type' => 'client_credentials',
            'client_id' => $this->login,
            'client_secret' => $this->secret,
        ], true);
        if (!isset($result['access_token'])) {
            throw new RuntimeException('Server not authorized to CDEK API');
        }

        $this->authToken = $result['access_token'];
    }

    private function httpRequest($method, $data, $useFormData = false, $useJson = false)
    {
        $ch = curl_init("$this->baseUrl/$method");

        $headers = [
            'Accept: application/json',
        ];

        if ($this->authToken) {
            $headers[] = "Authorization: Bearer $this->authToken";
        }

        if ($useFormData) {
            curl_setopt_array($ch, [
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => $data,
            ]);
        } elseif ($useJson) {
            $headers[] = 'Content-Type: application/json';
            curl_setopt_array($ch, [
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => json_encode($data),
            ]);
        } else {
            curl_setopt($ch, CURLOPT_URL, "$this->baseUrl/$method?" . http_build_query($data));
        }

        curl_setopt_array($ch, [
            CURLOPT_USERAGENT => 'widget/2.0',
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_RETURNTRANSFER => true,
        ]);

        $result = curl_exec($ch);

        if ($result === false) {
            throw new RuntimeException(curl_error($ch), curl_errno($ch));
        }

        return json_decode($result, true);
    }

    private function sendResponse($data)
    {
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(['response' => $data]);
        exit();
    }

    protected function getOffices()
    {
        return $this->httpRequest('deliverypoints', $this->requestData);
    }

    protected function calculate()
    {
        return $this->httpRequest('calculator/tarifflist', $this->requestData, false, true);
    }
}
