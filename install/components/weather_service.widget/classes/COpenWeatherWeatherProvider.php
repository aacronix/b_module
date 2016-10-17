<?php

namespace TL\weather\COpenWeatherWeatherProvider;
use TL\weather\IWeatherProvider;
use TL\weather\weather_functions as WF;

require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/weather_service/defines.php");

require_once FUNCTIONS_ROOT. '/functions.php';

class COpenWeatherWeatherProvider implements IWeatherProvider
{

    const API_ENDPOINT = "http://api.openweathermap.org/data/2.5/weather";
    const TRUE_KEY = 1;
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
            200 => 'wi-thunderstorm',
            201 => 'wi-thunderstorm',
            202 => 'wi-thunderstorm',
            210 => 'wi-thunderstorm',
            211 => 'wi-thunderstorm',
            212 => 'wi-thunderstorm',
            221 => 'wi-thunderstorm',
            230 => 'wi-thunderstorm',
            231 => 'wi-thunderstorm',
            232 => 'wi-thunderstorm',
            300 => 'wi-rain-mix',
            301 => 'wi-rain-mix',
            302 => 'wi-rain-mix',
            310 => 'wi-rain-mix',
            311 => 'wi-rain-mix',
            312 => 'wi-rain-mix',
            313 => 'wi-rain-mix',
            314 => 'wi-rain-mix',
            321 => 'wi-rain-mix',
            500 => 'wi-day-rain',
            501 => 'wi-day-rain',
            502 => 'wi-day-rain',
            503 => 'wi-day-rain',
            504 => 'wi-day-rain',
            511 => 'wi-rain',
            520 => 'wi-rain',
            521 => 'wi-rain',
            522 => 'wi-rain',
            531 => 'wi-rain',
            600 => 'wi-snow',
            601 => 'wi-snow',
            602 => 'wi-snow',
            611 => 'wi-snow',
            612 => 'wi-snow',
            615 => 'wi-snow',
            616 => 'wi-snow',
            620 => 'wi-snow',
            621 => 'wi-snow',
            622 => 'wi-snow',
            701 => 'wi-fog',
            711 => 'wi-fog',
            721 => 'wi-fog',
            731 => 'wi-fog',
            741 => 'wi-fog',
            751 => 'wi-fog',
            761 => 'wi-fog',
            762 => 'wi-fog',
            771 => 'wi-fog',
            781 => 'wi-fog',
            800 => 'wi-day-sunny',
            801 => 'wi-day-sunny',
            802 => 'wi-cloudy',
            803 => 'wi-cloudy',
            804 => 'wi-cloudy',
            900 => 'wi-tornado',
            901 => 'wi-thunderstorm',
            902 => 'wi-hurricane',
            903 => 'wi-snowflake-cold',
            904 => 'wi-hot',
            905 => 'wi-windy',
            906 => 'wi-hail',
            951 => 'wi-na',
            952 => 'wi-windy',
            953 => 'wi-windy',
            954 => 'wi-windy',
            955 => 'wi-windy',
            956 => 'wi-strong-wind',
            957 => 'wi-strong-wind',
            958 => 'wi-strong-wind',
            959 => 'wi-strong-wind',
            960 => 'wi-strong-wind',
            961 => 'wi-strong-wind',
            962 => 'wi-hurricane',
        );

        return $weatherTable[$code];
    }
    
    // проверка api ключа на соответствие
    public function isValidApiKey($apiKey, $appKey=null){
        $query = "?lat=55.75&lon=37.61&units=imperial&appid=";

        $query_url = self::API_ENDPOINT . $query . $apiKey;

        $session = curl_init($query_url);
        curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
        $json = curl_exec($session);
        $responseHttpCode = curl_getinfo($session, CURLINFO_HTTP_CODE);

        if ($responseHttpCode == '404'){
            return array("code" => self::SERVICE_UNAVAILABLE);
        }

        $response = json_decode($json, true);

        return (($response == null) ? array("code" => self::BAD_KEY) : array("code" => self::TRUE_KEY));
    }


    public function getWeather($latitude, $longitude, $unit)
    {
        $query = "?lat={$latitude}&lon={$longitude}&units=imperial&appid=";

        $query_url = self::API_ENDPOINT . $query . $this->apiKey;

        $session = curl_init($query_url);
        curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
        $json = curl_exec($session);

        $responseHttpCode = curl_getinfo($session, CURLINFO_HTTP_CODE);

        if ($responseHttpCode == '404'){
            return array("code" => self::SERVICE_UNAVAILABLE);
        }

        $weatherResult = json_decode($json, true);

        $pointPosition = array("latitude" => $latitude, "longitude" => $longitude);
        $icon = $this->getWeatherIcon($weatherResult["weather"][0]["id"]);

        if ($unit == 'metrical'){
            $temp = WF\fahrenheitToCelsius($weatherResult["main"]["temp"]);
            $windSpeed = WF\mphToKph($weatherResult["wind"]["speed"]);
        } else {
            $temp = $weatherResult["main"]["temp"];
            $windSpeed = $weatherResult["wind"]["speed"];
        }

        $windDegree = $weatherResult["wind"]["deg"];
        $wind = array("windSpeed" => $windSpeed, "windDegree" => $windDegree);
//        return $query_url;

        return array("pointPos" => $pointPosition, "icon" => $icon, "temp" => $temp, "wind" => $wind);
    }
}