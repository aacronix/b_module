<?php

namespace TL\weather\CYahooWeatherProvider;
use TL\weather\IWeatherProvider;

use TL\weather\weather_functions as WF;

require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/weather_service/defines.php");

require_once FUNCTIONS_ROOT . '/functions.php';

class CYahooWeatherProvider implements IWeatherProvider
{
    const API_ENDPOINT = "http://query.yahooapis.com/v1/public/yql";
    const TRUE_KEY = 1;
    const BAD_KEY = 0;
    const SERVICE_UNAVAILABLE = 3;
    const LESS_KEY = 2;

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
            0 => 'wi-tornado',
            1 => 'wi-thunderstorm',
            2 => 'wi-hurricane',
            3 => 'wi-thunderstorm',
            4 => 'wi-thunderstorm',
            5 => 'wi-rain-mix',
            6 => 'wi-rain-mix',
            7 => 'wi-rain-mix',
            8 => 'wi-snow',
            9 => 'wi-rain-mix',
            10 => 'wi-rain-wind',
            11 => 'wi-showers',
            12 => 'wi-showers',
            13 => 'wi-snow',
            14 => 'wi-snow',
            15 => 'wi-sandstorm',
            16 => 'wi-snow',
            17 => 'wi-hail',
            18 => 'wi-sleet',
            19 => 'wi-dust',
            20 => 'wi-fog',
            21 => 'wi-day-haze',
            22 => 'wi-smoke',
            23 => 'wi-strong-wind',
            24 => 'wi-strong-wind',
            25 => 'wi-snowflake-cold',
            26 => 'wi-cloudy',
            27 => 'wi-night-alt-cloudy-high',
            28 => 'wi-day-cloudy',
            29 => 'wi-night-alt-partly-cloudy',
            30 => 'wi-day-cloudy',
            31 => 'wi-night-clear',
            32 => 'wi-day-sunny',
            33 => 'wi-night-alt-cloudy-high',
            34 => 'wi-day-cloudy-high',
            35 => 'wi-rain-mix',
            36 => 'wi-hot',
            37 => 'wi-thunderstorm',
            38 => 'wi-thunderstorm',
            39 => 'wi-thunderstorm',
            40 => 'wi-rain',
            41 => 'wi-snow',
            42 => 'wi-snow',
            43 => 'wi-snow',
            44 => 'wi-day-cloudy',
            45 => 'wi-thunderstorm',
            46 => 'wi-rain-mix',
            47 => 'wi-thunderstorm',
            3200 => 'weather-error'
        );

        return $weatherTable[$code];
    }

    public function isValidApiKey($apiKey = null){
        $yql_query = "select * from weather.forecast where woeid in (SELECT woeid FROM geo.places WHERE text='(55.75,37.61)')";

        $yql_query_url = self::API_ENDPOINT . "?q=" . urlencode($yql_query) . "&format=json";

        $session = curl_init($yql_query_url);
        curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
        $json = curl_exec($session);

        $responseHttpCode = curl_getinfo($session, CURLINFO_HTTP_CODE);

        if ($responseHttpCode == '404'){
            return array("code" => self::SERVICE_UNAVAILABLE);
        }
        return array("code" => self::TRUE_KEY);
    }

    public function getWeather($latitude, $longitude, $unit)
    {
        $yql_query = "select * from weather.forecast where woeid in (SELECT woeid FROM geo.places WHERE text='({$latitude},{$longitude})')";

        $yql_query_url = self::API_ENDPOINT . "?q=" . urlencode($yql_query) . "&format=json";

        $session = curl_init($yql_query_url);
        curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
        $json = curl_exec($session);

        $responseHttpCode = curl_getinfo($session, CURLINFO_HTTP_CODE);

        if ($responseHttpCode == '404'){
            return array("code" => self::SERVICE_UNAVAILABLE);
        }

        $weatherResult = json_decode($json, true);

        $pointPosition = array("latitude" => $latitude, "longitude" => $longitude);
        $code = $weatherResult["query"]["results"]["channel"]["item"]["condition"]["code"];
        $temp = $weatherResult["query"]["results"]["channel"]["item"]["condition"]["temp"];


        $icon = $this->getWeatherIcon($weatherResult["query"]["results"]["channel"]["item"]["condition"]["code"]);

        if ($unit == 'metrical') {
            $temp = WF\fahrenheitToCelsius($weatherResult["query"]["results"]["channel"]["item"]["condition"]["temp"]);
            $windSpeed = WF\mphToKph($weatherResult["query"]["results"]["channel"]["wind"]["speed"]);
        } else {
            $temp = $weatherResult["query"]["results"]["channel"]["item"]["condition"]["temp"];
            $windSpeed = $weatherResult["query"]["results"]["channel"]["wind"]["speed"];
        }

        $windDegree = $weatherResult["query"]["results"]["channel"]["wind"]["direction"];
        $wind = array('windSpeed' => $windSpeed, 'windDegree' => $windDegree);

        return array('pointPos' => $pointPosition, 'icon' => $icon, 'temp' => $temp, 'wind' => $wind);
    }
}