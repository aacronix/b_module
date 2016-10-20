<?php

namespace TL\weather\CApixu;

use TL\weather\IWeatherProvider;
use TL\weather\weather_functions as WF;

require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/weather_service/defines.php");

require_once FUNCTIONS_ROOT . '/functions.php';

class CApixu implements IWeatherProvider
{
    const API_ENDPOINT = 'http://api.apixu.com/v1/current.json';
    const TRUE_KEY = 1;
    const LESS_KEY = 2;
    const BAD_KEY = 0;
    const SERVICE_UNAVAILABLE = 3;

    private $apiKey;
    private $appKey;

    public function __construct($initApiKey, $initAppKey = null)
    {
        $this->apiKey = $initApiKey;
        $this->appKey = $initAppKey;
    }

    public function getWeatherIcon($code)
    {
        $weatherTable = array(
            1000 => 'wi-day-sunny',
            1003 => 'wi-day-cloudy',
            1006 => 'wi-day-cloudy',
            1009 => 'wi-day-sunny-overcast',
            1030 => 'wi-day-fog',
            1063 => 'wi-rain',
            1066 => 'wi-snow',
            1069 => 'wi-sleet',
            1072 => 'wi-snow',
            1087 => 'wi-thunderstorm',
            1114 => 'wi-snow',
            1117 => 'wi-sandstorm',
            1135 => 'wi-fog',
            1147 => 'wi-fog',
            1150 => 'wi-snow',
            1153 => 'wi-snow',
            1168 => 'wi-snow',
            1171 => 'wi-snow',
            1180 => 'wi-rain',
            1183 => 'wi-rain',
            1186 => 'wi-rain',
            1189 => 'wi-rain',
            1192 => 'wi-rain',
            1195 => 'wi-rain',
            1198 => 'wi-rain',
            1201 => 'wi-rain',
            1204 => 'wi-sleet',
            1207 => 'wi-sleet',
            1210 => 'wi-snow',
            1213 => 'wi-snow',
            1216 => 'wi-snow',
            1219 => 'wi-snow',
            1222 => 'wi-snow',
            1225 => 'wi-snow',
            1237 => 'wi-snow',
            1240 => 'wi-rain',
            1243 => 'wi-rain',
            1246 => 'wi-rain',
            1249 => 'wi-sleet',
            1252 => 'wi-sleet',
            1255 => 'wi-snow',
            1258 => 'wi-snow',
            1261 => 'wi-snow',
            1264 => 'wi-snow',
            1273 => 'wi-storm-showers',
            1276 => 'wi-storm-showers',
            1279 => 'wi-storm-showers',
            1282 => 'wi-storm-showers',
        );

        return $weatherTable[$code];
    }

    // проверка api ключа на соответствие
    public function isValidApiKey($apiKey, $appKey = null)
    {
        if (strlen($apiKey) < 1) return array("code" => self::LESS_KEY);

        $query_url = self::API_ENDPOINT . "?key={$apiKey}&q=55.75,37.61";

        $session = curl_init($query_url);
        curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
        $json = curl_exec($session);

        $responseHttpCode = curl_getinfo($session, CURLINFO_HTTP_CODE);

        if ($responseHttpCode == '404'){
            return array("code" => self::SERVICE_UNAVAILABLE);
        }

        $response = json_decode($json, true);

        $response_code = $response["error"]["code"];

        $error_codes = [2006, 2007, 2008];


        foreach ($error_codes as &$error_code) {
            if ($response_code == $error_code) {
                return array("code" => self::BAD_KEY);
            }
        }

        return array("code" => self::TRUE_KEY);
    }

    public function getWeather($latitude, $longitude, $unit)
    {
        $query = "?key={$this->apiKey}&q={$latitude},{$longitude}";

        $query_url = self::API_ENDPOINT . $query;

        $session = curl_init($query_url);
        curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
        $json = curl_exec($session);

        $responseHttpCode = curl_getinfo($session, CURLINFO_HTTP_CODE);

        if ($responseHttpCode == '404'){
            return array("code" => self::SERVICE_UNAVAILABLE);
        }

        $weatherResult = json_decode($json, true);

        $response_code = $weatherResult["error"]["code"];

        $error_codes = [2006, 2007, 2008];

        foreach ($error_codes as &$error_code) {
            if ($response_code == $error_code) {
                return array("reaction" => self::SERVICE_UNAVAILABLE);
            }
        }

        $pointPosition = array("latitude" => $latitude, "longitude" => $longitude);
        $icon = $this->getWeatherIcon($weatherResult["current"]["condition"]["code"]);

        if ($unit == 'metrical') {
            $temp = WF\fahrenheitToCelsius($weatherResult["current"]["temp_f"]);
            $windSpeed = WF\mphToKph($weatherResult["current"]["wind_mph"]);
        } else {
            $temp = $weatherResult["current"]["temp_f"];
            $windSpeed = $weatherResult["current"]["wind_mph"];
        }

        $windDegree = $weatherResult["current"]["wind_degree"];
        $wind = array("windSpeed" => $windSpeed, "windDegree" => $windDegree);

//        return $query_url;
        return array("pointPos" => $pointPosition, "icon" => $icon, "temp" => $temp, "wind" => $wind);
    }
}