<?php

namespace TL\weather\CWeatherTrigger;

use TL\weather\IWeatherProvider;
use TL\weather\weather_functions as WF;

require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/weather_service/defines.php");

require_once FUNCTIONS_ROOT . '/functions.php';

class CWeatherTrigger implements IWeatherProvider
{
    const API_ENDPOINT = 'http://api.weatherunlocked.com/api/current/';
    const TRUE_KEY = 1;
    const BAD_KEY = 0;
    const SERVICE_UNAVAILABLE = 3;

    private $apiKey;
    private $appKey;

    public function __construct($initApiKey, $initAppKey)
    {
        $this->apiKey = $initApiKey;
        $this->appKey = $initAppKey;
    }

    public function getWeatherIcon($code)
    {
        $weatherTable = array(
            '0' => 'wi-day-sunny',
            '1' => 'wi-day-cloudy',
            '2' => 'wi-day-cloudy',
            '3' => 'wi-day-sunny-overcast',
            '10' => 'wi-day-fog',
            '21' => 'wi-rain',
            '22' => 'wi-snow',
            '23' => 'wi-sleet',
            '24' => 'wi-snow',
            '29' => 'wi-lightning',
            '38' => 'wi-windy',
            '39' => 'wi-sandstorm',
            '45' => 'wi-fog',
            '49' => 'wi-fog',
            '50' => 'wi-snow',
            '51' => 'wi-snow',
            '56' => 'wi-snow',
            '57' => 'wi-snow',
            '60' => 'wi-rain',
            '61' => 'wi-rain',
            '62' => 'wi-rain',
            '63' => 'wi-rain',
            '64' => 'wi-rain',
            '65' => 'wi-rain',
            '66' => 'wi-rain',
            '67' => 'wi-rain',
            '68' => 'wi-sleet',
            '69' => 'wi-sleet',
            '70' => 'wi-snow',
            '71' => 'wi-snow',
            '72' => 'wi-snow',
            '73' => 'wi-snow',
            '74' => 'wi-snow',
            '75' => 'wi-snow',
            '79' => 'wi-snow',
            '80' => 'wi-rain',
            '81' => 'wi-rain',
            '82' => 'wi-rain',
            '83' => 'wi-sleet',
            '84' => 'wi-sleet',
            '85' => 'wi-snow',
            '86' => 'wi-snow',
            '87' => 'wi-snow-wind',
            '88' => 'wi-snow-wind',
            '91' => 'wi-storm-showers',
            '92' => 'wi-storm-showers',
            '93' => 'wi-storm-showers',
            '94' => 'wi-storm-showers',
        );

        return $weatherTable[$code];
    }

    // проверка api ключа на соответствие
    public function isValidApiKey($apiKey, $appKey)
    {
        $ERROR_CODE = "403";

        $query = "55.75,37.61?app_id={$appKey}&app_key=" . $apiKey;

        $query_url = self::API_ENDPOINT . $query;

        $json = file_get_contents($query_url);

        $responseHttpCode = curl_getinfo($session, CURLINFO_HTTP_CODE);

        if ($responseHttpCode == '404'){
            return array("code" => self::SERVICE_UNAVAILABLE);
        }

        $weatherResult = json_decode($json, true);
        $weatherResult->headers = $http_response_header;

        $headerResponseCode = substr($http_response_header[0], 9, 3); // получаем код ошибки

        return (($headerResponseCode == $ERROR_CODE) ? array("code" => self::BAD_KEY) : array("code" => self::TRUE_KEY));
    }

    public function getWeather($latitude, $longitude, $unit)
    {
        $query = "{$latitude},{$longitude}?app_id={$this->appKey}&app_key=" . $this->apiKey;

        $query_url = self::API_ENDPOINT . $query;

        $session = curl_init($query_url);
        curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
        $json = curl_exec($session);

        $responseHttpCode = curl_getinfo($session, CURLINFO_HTTP_CODE);

        if ($responseHttpCode == '404'){
            return array("code" => self::SERVICE_UNAVAILABLE);
        }

        $weatherResult = json_decode($json, true);

        $pointPosition = array('latitude' => $latitude, 'longitude' => $longitude);

        $icon = $this->getWeatherIcon($weatherResult['wx_code']);

        if ($unit == 'metrical') {
            $temp = WF\fahrenheitToCelsius($weatherResult['temp_f']);
            $windSpeed = WF\mphToKph($weatherResult['windspd_mph']);
        } else {
            $temp = $weatherResult['temp_f'];
            $windSpeed = $weatherResult['windspd_mph'];
        }

        $windDegree = $weatherResult['winddir_deg'];
        $wind = array('windSpeed' => $windSpeed, 'windDegree' => $windDegree);

        return array('pointPos' => $pointPosition, 'icon' => $icon, 'temp' => $temp, 'wind' => $wind);
    }
}