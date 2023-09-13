<?php

namespace {
    header('Access-Control-Allow-Origin: *');
    error_reporting(0);

    SDEKService\Controller::processRequest(
        SDEKService\Settings::factory(
            /** Настройте приоритет тарифов курьерской доставки */
            /** Set up the priority of courier delivery tariffs */
            array(233, 137, 139, 16, 18, 11, 1, 3, 61, 60, 59, 58, 57, 83),
            /** Настройте приоритет тарифов доставки до пунктов выдачи */
            /** Set the priority of delivery tariffs to pick-up points */
            array(234, 136, 138, 15, 17, 10, 12, 5, 62, 63),
            /** Вставьте свой аккаунт\идентификатор для интеграции */
            /** Put your account for integration here */
            '',
            /** Вставьте свой пароль для интеграции */
            /** Put your password for integration here */
            ''
        )
    );
}

namespace SDEKService {

    class Settings
    {
        const COURIER_TARIFF_PRIORITY = 'courier';

        const PICKUP_TARIFF_PRIORITY = 'pickup';

        /**
         * @var array $courierTariffPriority
         */
        private $courierTariffPriority;
        /**
         * @var array $pickupTariffPriority
         */
        private $pickupTariffPriority;
        /**
         * @var string|bool $account
         */
        private $account;
        /**
         * @var string|bool $key
         */
        private $key;

        /**
         * @param array $courierTariffPriority
         * @param array $pickupTariffPriority
         * @param string|bool $account
         * @param string|bool $key
         */
        private function __construct($courierTariffPriority, $pickupTariffPriority, $account, $key)
        {
            $this->courierTariffPriority = $courierTariffPriority;
            $this->pickupTariffPriority = $pickupTariffPriority;
            $this->account = $account ?: false;
            $this->key = $key ?: false;
        }

        /**
         * @param array $courierTariffPriority
         * @param array $pickupTariffPriority
         * @param string|bool $account
         * @param string|bool $key
         * @return static
         */
        public static function factory($courierTariffPriority, $pickupTariffPriority, $account, $key)
        {
            return new self($courierTariffPriority, $pickupTariffPriority, $account, $key);
        }

        /**
         * @param string|null $type
         * @return array - all or concrete tariffs priority
         * @throws \InvalidArgumentException
         */
        public function getTariffPriority($type = self::COURIER_TARIFF_PRIORITY)
        {
            if (!\in_array($type, array(self::COURIER_TARIFF_PRIORITY, self::PICKUP_TARIFF_PRIORITY), true)) {
                throw new \InvalidArgumentException("Unknown tariff type {$type}");
            }

            return $type === self::COURIER_TARIFF_PRIORITY ? $this->courierTariffPriority : $this->pickupTariffPriority;
        }

        /**
         * @return bool
         */
        public function hasCredentials()
        {
            return $this->account && $this->key;
        }

        /**
         * @return bool|string
         */
        public function getAccount()
        {
            return $this->account;
        }

        /**
         * @return bool|string
         */
        public function getKey()
        {
            return $this->key;
        }
    }

    /** base actions class */
    abstract class BaseAction
    {
        /**
         * @var Controller $controller
         */
        protected $controller;

        /**
         * BaseAction constructor.
         * @param Controller $controller
         */
        public function __construct(Controller $controller)
        {
            $this->controller = $controller;
        }

        /**
         * @return array|mixed result data for response
         */
        abstract public function run();

        /**
         * @param string $url
         * @param array|string|bool $data
         * @param bool $rawRequest
         * @return array
         */
        protected function sendCurlRequest($url, $data = false, $rawRequest = false)
        {
            if (!\function_exists('curl_init')) {
                return array('error' => 'No php CURL-library installed on server');
            }

            $curlOptions = array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => TRUE
            );

            if ($rawRequest) {
                $curlOptions[CURLOPT_POST] = FALSE;
                $curlOptions[CURLOPT_HTTPHEADER] = array('Content-type: application/json');
            }

            if ($data) {
                $curlOptions += array(
                    CURLOPT_POST => TRUE,
                    CURLOPT_POSTFIELDS => $data,
                    CURLOPT_REFERER => 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['SCRIPT_NAME'],
                );
            }

            $ch = \curl_init();
            \curl_setopt_array($ch, $curlOptions);
            $result = \curl_exec($ch);
            $code = \curl_getinfo($ch, CURLINFO_HTTP_CODE);
            \curl_close($ch);

            return array(
                'code' => $code,
                'result' => $result
            );
        }
    }

    /** pvz */
    class PickupAction extends BaseAction
    {
        /**
         * @return array|string[]
         */
        public function run()
        {
            if (!\function_exists('simplexml_load_string')) {
                return array('error' => 'No php simplexml-library installed on server');
            }

            $langPart = $this->controller->getRequestValue('lang') ? '&lang=' . $this->controller->getRequestValue('lang') : '';
            $request = $this->sendCurlRequest('https://integration.cdek.ru/pvzlist/v1/xml?type=ALL' . $langPart);

            if ($request && $request['code'] === 200) {
                $xml = \simplexml_load_string($request['result']);

                $arList = array('PVZ' => array(), 'CITY' => array(), 'REGIONS' => array(), 'CITYFULL' => array(), 'COUNTRIES' => array());

                foreach ($xml as $key => $val) {

                    if (($country = $this->controller->getRequestValue('country'))
                        && $country !== 'all'
                        && ((string)$val['CountryName'] !== $country)) {
                        continue;
                    }

                    $cityCode = (string)$val['CityCode'];
                    $type = 'PVZ';
                    $city = (string)$val['City'];
                    if (strpos($city, '(') !== false) {
                        $city = \trim(\mb_substr($city, 0, \strpos($city, '(')));
                    }
                    if (strpos($city, ',') !== false) {
                        $city = \trim(\mb_substr($city, 0, \strpos($city, ',')));
                    }
                    $code = (string)$val['Code'];

                    $arList[$type][$cityCode][$code] = array(
                        'Name' => (string)$val['Name'],
                        'WorkTime' => (string)$val['WorkTime'],
                        'Address' => (string)$val['Address'],
                        'Phone' => (string)$val['Phone'],
                        'Note' => (string)$val['Note'],
                        'cX' => (string)$val['coordX'],
                        'cY' => (string)$val['coordY'],
                        'Dressing' => ((string)$val['IsDressingRoom'] === 'true'),
                        'Cash' => ((string)$val['HaveCashless'] === 'true'),
                        'Postamat' => (\strtolower($val['Type']) === 'postamat'),
                        'Station' => (string)$val['NearestStation'],
                        'Site' => (string)$val['Site'],
                        'Metro' => (string)$val['MetroStation'],
                        'AddressComment' => (string)$val['AddressComment'],
                        'CityCode' => (string)$val['CityCode'],
                    );
                    if ($val->WeightLimit) {
                        $arList[$type][$cityCode][$code]['WeightLim'] = array(
                            'MIN' => (float)$val->WeightLimit['WeightMin'],
                            'MAX' => (float)$val->WeightLimit['WeightMax']
                        );
                    }

                    $arImgs = array();

                    foreach ($val->OfficeImage as $img) {
                        if (strpos($_tmpUrl = (string)$img['url'], 'http') === false) {
                            continue;
                        }
                        $arImgs[] = (string)$img['url'];
                    }

                    if (\count($arImgs = \array_filter($arImgs))) {
                        $arList[$type][$cityCode][$code]['Picture'] = $arImgs;
                    }
                    if ($val->OfficeHowGo) {
                        $arList[$type][$cityCode][$code]['Path'] = (string)$val->OfficeHowGo['url'];
                    }

                    if (!\array_key_exists($cityCode, $arList['CITY'])) {
                        $arList['CITY'][$cityCode] = $city;
                        $arList['CITYREG'][$cityCode] = (int)$val['RegionCode'];
                        $arList['REGIONSMAP'][(int)$val['RegionCode']][] = (int)$cityCode;
                        $arList['CITYFULL'][$cityCode] = $val['CountryName'] . ' ' . $val['RegionName'] . ' ' . $city;
                        $arList['REGIONS'][$cityCode] = \implode(', ', \array_filter(array((string)$val['RegionName'], (string)$val['CountryName'])));
                    }

                }

                \krsort($arList['PVZ']);

                return array('pvz' => $arList);
            }

            if ($request) {

                return array('error' => 'Wrong answer code from server : ' . $request['code']);
            }
            return array('error' => 'Some error PVZ');
        }
    }

    /** address, city, etc */
    class AddressAction extends BaseAction
    {
        /**
         * @param array $data (optional)
         * @return array|string[]
         */
        public function run($data = array())
        {
            if ($city = $this->controller->getRequestValue(
                'city',
                $this->controller->getValue($data, 'city')
            )
            ) {
                return $this->getCityByName($city);
            }

            if ($address = $this->controller->getRequestValue(
                'address',
                $this->controller->getValue($data, 'address')
            )
            ) {
                return $this->getCityByAddress($address);
            }

            return array('error' => 'No city to search given');
        }

        /**
         * @param string $name
         * @param bool $single
         * @return array|string[]
         */
        protected function getCityByName($name, $single = true)
        {
            $arReturn = array();

            $result = $this->sendCurlRequest(
                'http://api.cdek.ru/city/getListByTerm/json.php?q=' . \urlencode($name)
            );
            if ($result && $result['code'] == 200) {
                $result = json_decode($result['result']);
                if (!isset($result->geonames)) {
                    $arReturn = array('error' => 'No cities found');
                } else {
                    if ($single) {
                        $arReturn = array(
                            'id' => $result->geonames[0]->id,
                            'city' => $result->geonames[0]->cityName,
                            'region' => $result->geonames[0]->regionName,
                            'country' => $result->geonames[0]->countryName
                        );
                    } else {
                        $arReturn['cities'] = array();
                        foreach ($result->geonames as $city) {
                            $arReturn['cities'][] = array(
                                'id' => $city->id,
                                'city' => $city->cityName,
                                'region' => $city->regionName,
                                'country' => $city->countryName
                            );
                        }
                    }
                }
            } else {
                $arReturn = array('error' => 'Wrong answer code from server : ' . $result['code']);
            }

            return $arReturn;
        }

        public function getCityByAddress($address)
        {
            $arReturn = array();
            $arStages = array('country' => false, 'region' => false, 'subregion' => false);
            $arAddress = \explode(',', $address);

            $ind = 0;
            // finging country in address
            if (\in_array((string)$arAddress[0], $this->getCountries(), true)) {
                $arStages['country'] = \mb_strtolower(\trim($arAddress[0]));
                $ind++;
            }
            // finding region in address
            foreach ($this->getRegion() as $regionStr) {
                $search = \mb_strtolower(\trim($arAddress[$ind]));
                $indSearch = \strpos($search, $regionStr);
                if ($indSearch !== false) {
                    if ($indSearch) {
                        $arStages['region'] = \mb_substr($search, 0, \strpos($search, $regionStr));
                    } else {
                        $arStages['region'] = \mb_substr($search, \mb_strlen($regionStr));
                    }
                    $arStages['region'] = \trim($arStages['region']);
                    $ind++;
                    break;
                }
            }
            // finding subregions
            foreach ($this->getSubRegion() as $subRegionStr) {
                $search = \mb_strtolower(\trim($arAddress[$ind]));
                $indSearch = \strpos($search, $subRegionStr);
                if ($indSearch !== false) {
                    if ($indSearch) {
                        $arStages['subregion'] = \mb_substr($search, 0, \strpos($search, $subRegionStr));
                    } else {
                        $arStages['subregion'] = \mb_substr($search, \mb_strlen($subRegionStr));
                    }
                    $arStages['subregion'] = \trim($arStages['subregion']);
                    $ind++;
                    break;
                }
            }
            // finding city
            $cityName = trim($arAddress[$ind]);
            $cdekCity = $this->getCityByName($cityName, false);

            if (!empty($cdekCity['error'])) {
                foreach ($this->getCityDef() as $placeLbl) {
                    $search = \str_replace('ё', 'е', \mb_strtolower(\trim($arAddress[$ind])));
                    $indSearch = \strpos($search, $placeLbl);
                    if ($indSearch !== false) {
                        if ($indSearch) {
                            $search = \mb_substr($search, 0, \strpos($search, $placeLbl));
                        } else {
                            $search = \mb_substr($search, \mb_strlen($placeLbl));
                        }
                        $search = \trim($search);
                        $cityName = $search;
                        $cdekCity = $this->getCityByName($search, false);
                        break;
                    }
                }
            }

            if (!empty($cdekCity['error'])) {
                $arReturn['error'] = $cdekCity['error'];
            } else {
                if (\count($cdekCity['cities']) > 0) {
                    $pretend = false;
                    $arPretend = array();
                    // parseCountry
                    if ($arStages['country']) {
                        foreach ($cdekCity['cities'] as $arCity) {
                            $possCountry = \mb_strtolower($arCity['country']);
                            if (!$possCountry || \mb_stripos($arStages['country'], $possCountry) !== false) {
                                $arPretend [] = $arCity;
                            }
                        }
                    } else {
                        $arPretend = $cdekCity['cities'];
                    }

                    // parseRegion
                    if (!empty($arStages['region']) && (\count($arPretend) > 1)) {
                        $_arPretend = array();
                        foreach ($arPretend as $arCity) {
                            $possRegion = \str_replace($this->getRegion(), '', \mb_strtolower(\trim($arCity['region'])));
                            if (!$possRegion || \mb_stripos($possRegion, \str_replace($this->getRegion(), '', $arStages['region'])) !== false) {
                                $_arPretend [] = $arCity;
                            }
                        }
                        $arPretend = $_arPretend;
                    }

                    // parseSubRegion
                    if (!empty($arStages['subregion']) && (\count($arPretend) > 1)) {
                        $_arPretend = array();
                        foreach ($arPretend as $arCity) {
                            $possSubRegion = \mb_strtolower($arCity['city']);
                            if (!$possSubRegion || \mb_stripos($possSubRegion, $arStages['subregion']) !== false) {
                                $_arPretend [] = $arCity;
                            }
                        }
                        $arPretend = $_arPretend;
                    }
                    // parseUndefined
                    // not full city name
                    if (\count($arPretend) > 1) {
                        $_arPretend = array();
                        foreach ($arPretend as $arCity) {
                            if (\mb_stripos($arCity['city'], ',') === false) {
                                $_arPretend [] = $arCity;
                            }
                        }
                        $arPretend = $_arPretend;
                    }
                    if (\count($arPretend) > 1) {
                        $_arPretend = array();
                        foreach ($arPretend as $arCity) {
                            if (\mb_strlen($arCity['city']) === \mb_strlen($cityName)) {
                                $_arPretend [] = $arCity;
                            }
                        }
                        $arPretend = $_arPretend;
                    }
                    // federalCities
                    if (\count($arPretend) > 1) {
                        $_arPretend = array();
                        foreach ($arPretend as $arCity) {
                            if ($arCity['city'] === $arCity['region']) {
                                $_arPretend [] = $arCity;
                            }
                        }
                        $arPretend = $_arPretend;
                    }


                    // end
                    if (\count($arPretend) === 1) {
                        $pretend = \array_pop($arPretend);
                    }
                } else {
                    $pretend = $cdekCity['cities'][0];
                }
                if ($pretend) {
                    $arReturn['city'] = $pretend;
                } else {
                    $arReturn['error'] = 'Undefined city';
                }
            }
            return $arReturn;
        }

        protected function getCountries()
        {
            return array('Россия', 'Беларусь', 'Армения', 'Казахстан', 'Киргизия', 'Молдова', 'Таджикистан', 'Узбекистан');
        }

        protected function getRegion()
        {
            return array('автономная область', 'область', 'республика', 'автономный округ', 'округ', 'край', 'обл.');
        }

        protected function getSubRegion()
        {
            return array('муниципальный район', 'район', 'городской округ');
        }

        protected function getCityDef()
        {
            return array(
                'поселок городского типа',
                'населенный пункт',
                'курортный поселок',
                'дачный поселок',
                'рабочий поселок',
                'почтовое отделение',
                'сельское поселение',
                'ж/д станция',
                'станция',
                'городок',
                'деревня',
                'микрорайон',
                'станица',
                'хутор',
                'аул',
                'поселок',
                'село',
                'снт'
            );
        }
    }

    /** calc delivery */
    class CalculationAction extends BaseAction
    {
        /**
         * @return array|string[]
         */
        public function run()
        {
            $shipment = $this->controller->getRequestValue('shipment', array());

            if (empty($shipment['tariffList'])) {
                $shipment['tariffList'] = $this->controller->getSettings()->getTariffPriority($shipment['type']);
            }

            if (($ref = $this->controller->getValue($_SERVER, 'HTTP_REFERER')) && !empty($ref)) {
                $shipment['ref'] = $ref;
            }

            if (empty($shipment['cityToId'])) {
                $cityTo = $this->sendToCity($shipment['cityTo']);
                if ($cityTo && $cityTo['code'] === 200) {
                    $pretendents = \json_decode($cityTo['result']);
                    if ($pretendents && isset($pretendents->geonames)) {
                        $shipment['cityToId'] = $pretendents->geonames[0]->id;
                    }
                }
            }

            if ($shipment['cityToId']) {
                $answer = $this->calculate($shipment);

                if ($answer) {
                    $returnData = array(
                        'result' => $answer,
                        'type' => $shipment['type'],
                    );
                    if ($shipment['timestamp']) {
                        $returnData['timestamp'] = $shipment['timestamp'];
                    }

                    return $returnData;
                }
            }

            return array('error' => 'City to not found');
        }

        protected function calculate($shipment)
        {
            if (empty($shipment['goods'])) {
                return array('error' => 'The dimensions of the goods are not defined');
            }

            $headers = $this->getHeaders();

            $arData = array(
                'dateExecute' => $this->controller->getValue($headers, 'date'),
                'version' => '1.0',
                'authLogin' => $this->controller->getValue($headers, 'account'),
                'secure' => $this->controller->getValue($headers, 'secure'),
                'senderCityId' => $this->controller->getValue($shipment, 'cityFromId'),
                'receiverCityId' => $this->controller->getValue($shipment, 'cityToId'),
                'ref' => $this->controller->getValue($shipment, 'ref'),
                'widget' => 1,
                'currency' => $this->controller->getValue($shipment, 'currency', 'RUB'),
            );

            if (!empty($shipment['tariffList'])) {
                foreach ($shipment['tariffList'] as $priority => $tariffId) {
                    $tariffId = (int)$tariffId;
                    $arData['tariffList'] [] = array(
                        'priority' => $priority + 1,
                        'id' => $tariffId
                    );
                }
            }

            $arData['goods'] = array();
            foreach ($shipment['goods'] as $arGood) {
                $arData['goods'] [] = array(
                    'weight' => $arGood['weight'],
                    'length' => $arGood['length'],
                    'width' => $arGood['width'],
                    'height' => $arGood['height']
                );
            }

            $type = $this->controller->getValue($shipment, 'type');

            $resultTariffs = $this->sendCurlRequest(
                'http://api.cdek.ru/calculator/calculate_tarifflist.php',
                \json_encode($arData),
                true
            );
            if ($resultTariffs && $resultTariffs['code'] === 200) {
                if (!\is_null(\json_decode($resultTariffs['result'], false))) {
                    $resultTariffs = \json_decode($resultTariffs['result'], true);

                    $returnFirst = function ($array) {
                        $first = reset($array);

                        return $first['result'];
                    };

                    if (!empty($type) && empty($arData['tariffId'])) {
                        $tariffListSorted = $this->controller->getSettings()->getTariffPriority($type);

                        $array_column = function ($array, $columnName) {
                            return \array_map(function ($element) use ($columnName) {
                                return $element[$columnName];
                            }, $array);
                        };

                        $calcTariffs = \array_filter(
                            $this->controller->getValue($resultTariffs, 'result', array()),
                            function ($item) {
                                return $item['status'] === true;
                            }
                        ) ?: array();

                        $calcTariffs = \array_combine($array_column($calcTariffs, 'tariffId'), $calcTariffs);

                        foreach ($tariffListSorted as $tariffId) {
                            if (\array_key_exists($tariffId, $calcTariffs)) {
                                return $calcTariffs[$tariffId]['result'];
                            }
                        }
                        return $returnFirst($calcTariffs);
                    }
                    return $returnFirst($resultTariffs);
                }
                return array('error' => 'Wrong server answer');
            }

            return array('error' => 'Wrong answer code from server : ' . $resultTariffs['code']);
        }

        protected function sendToCity($city)
        {
            static $action;
            if (!$action) {
                $action = new AddressAction($this->controller);
            }

            return $action->run(array('city' => $city));
        }


        protected function getHeaders()
        {
            $date = date('Y-m-d');
            $headers = array(
                'date' => $date
            );

            $settings = $this->controller->getSettings();
            if ($settings->hasCredentials()) {
                $headers = array(
                    'date' => $date,
                    'account' => $settings->getAccount(),
                    'secure' => md5($date . "&" . $settings->getKey())
                );
            }

            return $headers;
        }
    }

    /** translate */
    class I18nAction extends BaseAction
    {
        /**
         * @return array with translations
         */
        public function run()
        {
            return array('LANG' => $this->controller->getValue(
                $translate = array(
                    'rus' => array(
                        'YOURCITY' => 'Ваш город',
                        'COURIER' => 'Курьер',
                        'PICKUP' => 'Самовывоз',
                        'TERM' => 'Срок',
                        'PRICE' => 'Стоимость',
                        'DAY' => 'дн.',
                        'RUB' => ' руб.',
                        'KZT' => 'KZT',
                        'USD' => 'USD',
                        'EUR' => 'EUR',
                        'GBP' => 'GBP',
                        'CNY' => 'CNY',
                        'BYN' => 'BYN',
                        'UAH' => 'UAH',
                        'KGS' => 'KGS',
                        'AMD' => 'AMD',
                        'TRY' => 'TRY',
                        'THB' => 'THB',
                        'KRW' => 'KRW',
                        'AED' => 'AED',
                        'UZS' => 'UZS',
                        'MNT' => 'MNT',
                        'NODELIV' => 'Нет доставки',
                        'CITYSEARCH' => 'Поиск города',
                        'ALL' => 'Все',
                        'PVZ' => 'Пункты выдачи',
                        'POSTOMAT' => 'Постаматы',
                        'MOSCOW' => 'Москва',
                        'RUSSIA' => 'Россия',
                        'COUNTING' => 'Идет расчет',

                        'NO_AVAIL' => 'Нет доступных способов доставки',
                        'CHOOSE_TYPE_AVAIL' => 'Выберите способ доставки',
                        'CHOOSE_OTHER_CITY' => 'Выберите другой населенный пункт',

                        'TYPE_ADDRESS' => 'Уточните адрес',
                        'TYPE_ADDRESS_HERE' => 'Введите адрес доставки',

                        'L_ADDRESS' => 'Адрес пункта выдачи заказов',
                        'L_TIME' => 'Время работы',
                        'L_WAY' => 'Как к нам проехать',
                        'L_CHOOSE' => 'Выбрать',

                        'H_LIST' => 'Список пунктов выдачи заказов',
                        'H_PROFILE' => 'Способ доставки',
                        'H_CASH' => 'Расчет картой',
                        'H_DRESS' => 'С примеркой',
                        'H_POSTAMAT' => 'Постаматы СДЭК',
                        'H_SUPPORT' => 'Служба поддержки',
                        'H_QUESTIONS' => 'Если у вас есть вопросы, можете<br> задать их нашим специалистам',
                        'ADDRESS_WRONG' => 'Невозможно определить выбранное местоположение. Уточните адрес из выпадающего списка в адресной строке.',
                        'ADDRESS_ANOTHER' => 'Ознакомьтесь с новыми условиями доставки для выбранного местоположения.'
                    ),
                    'eng' => array(
                        'YOURCITY' => 'Your city',
                        'COURIER' => 'Courier',
                        'PICKUP' => 'Pickup',
                        'TERM' => 'Term',
                        'PRICE' => 'Price',
                        'DAY' => 'days',
                        'RUB' => 'RUB',
                        'KZT' => 'KZT',
                        'USD' => 'USD',
                        'EUR' => 'EUR',
                        'GBP' => 'GBP',
                        'CNY' => 'CNY',
                        'BYN' => 'BYN',
                        'UAH' => 'UAH',
                        'KGS' => 'KGS',
                        'AMD' => 'AMD',
                        'TRY' => 'TRY',
                        'THB' => 'THB',
                        'KRW' => 'KRW',
                        'AED' => 'AED',
                        'UZS' => 'UZS',
                        'MNT' => 'MNT',
                        'NODELIV' => 'Not delivery',
                        'CITYSEARCH' => 'Search for a city',
                        'ALL' => 'All',
                        'PVZ' => 'Points of self-delivery',
                        'POSTOMAT' => 'Postamats',
                        'MOSCOW' => 'Moscow',
                        'RUSSIA' => 'Russia',
                        'COUNTING' => 'Calculation',

                        'NO_AVAIL' => 'No shipping methods available',
                        'CHOOSE_TYPE_AVAIL' => 'Choose a shipping method',
                        'CHOOSE_OTHER_CITY' => 'Choose another location',

                        'TYPE_ADDRESS' => 'Specify the address',
                        'TYPE_ADDRESS_HERE' => 'Enter the delivery address',

                        'L_ADDRESS' => 'Adress of self-delivery',
                        'L_TIME' => 'Working hours',
                        'L_WAY' => 'How to get to us',
                        'L_CHOOSE' => 'Choose',

                        'H_LIST' => 'List of self-delivery',
                        'H_PROFILE' => 'Shipping method',
                        'H_CASH' => 'Payment by card',
                        'H_DRESS' => 'Dressing room',
                        'H_POSTAMAT' => 'Postamats CDEK',
                        'H_SUPPORT' => 'Support',
                        'H_QUESTIONS' => 'If you have any questions,<br> you can ask them to our specialists',

                        'ADDRESS_WRONG' => 'Impossible to define address. Please, recheck the address.',
                        'ADDRESS_ANOTHER' => 'Read the new terms and conditions.'
                    )
                ),
                $this->controller->getRequestValue('lang', 'rus'),
                $translate['rus']
            ));
        }
    }

    /** all other actions */
    class UnknownAction extends BaseAction
    {
        /**
         * @return string
         */
        public function run()
        {
            return 'unknownAction';
        }
    }

    /**  */
    class Controller
    {
        /**
         * @var array $request
         */
        private $request;
        /**
         * @var array $response
         */
        private $response;
        /**
         * @var Settings $settings
         */
        private $settings;

        /**
         * Entrypoint
         * @param Settings $settings
         */
        public static function processRequest(Settings $settings)
        {
            $self = new self($settings);
            $self->toResponse(
                $self->getAction()
                    ->run()
            );
            echo \json_encode($self->response ?: false);
        }

        /**
         * @param array|mixed $data
         * @return void
         */
        public function toResponse($data)
        {
            if (!is_array($data)) {
                $data = array('info' => $data);
            }

            foreach ($data as $key => $value) {
                if ($key === 'error') {
                    if (!array_key_exists($key, $this->response)) {
                        $this->response[$key] = array();
                    }
                    $this->response[$key][] = $value;
                } else {
                    $this->response[$key] = $value;
                }
            }
        }

        /**
         * @param array $fromArray
         * @param string|int $key
         * @param mixed|null $default
         * @return mixed|null
         */
        public function getValue($fromArray, $key, $default = null)
        {
            return isset($fromArray[$key]) ? $fromArray[$key] : $default;
        }

        /**
         * @param string|int $key
         * @param mixed|null $default
         * @return mixed|null
         */
        public function getRequestValue($key, $default = null)
        {
            return $this->getValue($this->request, $key, $default);
        }

        /**
         * @return Settings
         */
        public function getSettings()
        {
            return $this->settings;
        }

        /**
         * @return BaseAction concrete action implementation
         */
        protected function getAction()
        {
            $actionName = $this->getRequestValue('isdek_action');
            switch (true) {
                case $actionName === 'getPVZ':
                    $action = new PickupAction($this);
                    break;
                case $actionName === 'getCity':
                    $action = new AddressAction($this);
                    break;
                case $actionName === 'calc':
                    $action = new CalculationAction($this);
                    break;
                case $actionName === 'getLang':
                    $action = new I18nAction($this);
                    break;
                default:
                    $action = new UnknownAction($this);
            }
            return $action;
        }

        /**
         * Controller constructor.
         * @param Settings $settings
         */
        protected function __construct(Settings $settings)
        {
            $this->request = $this->getRequest();
            $this->response = array();
            $this->settings = $settings;
        }

        /**
         * @return array - one of $_REQUEST, $_POST, $_GET
         */
        protected function getRequest()
        {
            $request = $_REQUEST;
            if (isset($_SERVER['REQUEST_METHOD']) && in_array($_SERVER['REQUEST_METHOD'], array('GET', 'POST'))) {
                $request = $_SERVER['REQUEST_METHOD'] === 'POST' ? $_POST : $_GET;
            }
            return $request;
        }
    }
}
