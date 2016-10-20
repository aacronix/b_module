<?php

namespace TL\weather\CForecastIO;

use TL\weather\IWeatherProvider;
use TL\weather\weather_functions as WF;

require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/weather_service/defines.php");

require_once FUNCTIONS_ROOT . '/functions.php';

class CForecastIO implements IWeatherProvider
{
    const API_ENDPOINT = 'https://api.darksky.net/forecast/';
    const TRUE_KEY = 1;
    const BAD_KEY = 0;
    const SERVICE_UNAVAILABLE = 3;
    const MIXED_ERROR = 4;
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
            'clear-day' => 'wi-day-sunny',
            'clear-night' => 'wi-night-clear',
            'rain' => 'wi-rain',
            'snow' => 'wi-snow',
            'sleet' => 'wi-sleet',
            'wind' => 'wi-strong-wind',
            'fog' => 'wi-fog',
            'cloudy' => 'wi-cloudy',
            'partly-cloudy-day' => 'wi-day-cloudy',
            'partly-cloudy-night' => 'wi-night-cloudy',
            'hail' => 'wi-hail',
            'thunderstorm' => 'wi-thunderstorm',
            'tornado' => 'wi-tornado',
        );

        return $weatherTable[$code];
    }

    // проверка api ключа на соответствие
    public function isValidApiKey($apiKey)
    {
        if (strlen($apiKey) < 1) return array("code" => self::LESS_KEY);
        
        $ERROR_CODE = "404";

        $query = $apiKey . "/55.75,37.61";
        $exclude = "?exclude=hourly,daily,minutely,flags";

        $query_url = self::API_ENDPOINT . $query . $exclude;

        $weatherResult = file_get_contents($query_url);

        $weatherResult->headers = $http_response_header;

        $headerResponseCode = substr($http_response_header[0], 9, 3); // получаем код ошибки

        return (($headerResponseCode == $ERROR_CODE) ? array("code" => self::BAD_KEY) : array("code" => self::TRUE_KEY));
    }

    public function getWeather($latitude, $longitude, $unit)
    {
        $query = $this->apiKey . "/{$latitude},{$longitude}";
        $exclude = "?exclude=hourly,daily,minutely,flags";

        $query_url = self::API_ENDPOINT . $query . $exclude;

        $json = file_get_contents($query_url);
        $weatherResult = json_decode($json, true);
        $weatherResult->headers = $http_response_header;

        $headerResponseCode = substr($http_response_header[0], 9, 3); // получаем код ошибки

        if ($headerResponseCode == '404'){
            return array("code" => self::SERVICE_UNAVAILABLE);
        }

        $pointPosition = array("latitude" => $latitude, "longitude" => $longitude);
        $icon = $this->getWeatherIcon($weatherResult["currently"]["icon"]);

        if ($unit == 'metrical') {
            $temp = WF\fahrenheitToCelsius($weatherResult["currently"]["temperature"]);
            $windSpeed = WF\mphToKph($weatherResult["currently"]["windSpeed"]);
        } else {
            $temp = $weatherResult["currently"]["temperature"];
            $windSpeed = $weatherResult["currently"]["windSpeed"];
        }

        $windDegree = $weatherResult["currently"]["windBearing"];
        $wind = array("windSpeed" => $windSpeed, "windDegree" => $windDegree);

        return array("pointPos" => $pointPosition, "icon" => $icon, "temp" => $temp, "wind" => $wind);
    }
}