<?php

namespace TL\weather\CWunderground;
use TL\weather\IWeatherProvider;

use TL\weather\weather_functions as WF;

require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/weather_service/defines.php");

require_once FUNCTIONS_ROOT . '/functions.php';

class CWunderground implements IWeatherProvider
{
    const API_ENDPOINT = 'http://api.wunderground.com/api/';
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
            'chanceflurries' => 'wi-snow',
            'chancerain' => 'wi-rain',
            'chancesleet' => 'wi-sleet',
            'chancesnow' => 'wi-snow',
            'clear' => 'wi-day-sunny',
            'chancetstorms' => 'wi-thunderstorm',
            'flurries' => 'wi-snow',
            'fog' => 'wi-fog',
            'hazy' => 'wi-day-haze',
            'mostlycloudy' => 'wi-day-cloudy',
            'mostlysunny' => 'wi-day-cloudy',
            'partlycloudy' => 'wi-day-cloudy-high',
            'partlysunny' => 'wi-day-cloudy-high',
            'sleet' => 'wi-day-sleet',
            'rain' => 'wi-rain',
            'snow' => 'wi-snow',
            'sunny' => 'wi-day-sunny',
            'tstorms' => 'wi-thunderstorm',
            'cloudy' => 'wi-cloudy',
        );

        return $weatherTable[$code];
    }

    // проверка api ключа на соответствие
    public function isValidApiKey($apiKey){
        $ERROR_CODE = "keynotfound";

        $queryTail = "geolookup/conditions/q";
        $query = $apiKey ."/{$queryTail}/55.75,37.61.json";

        $query_url = self::API_ENDPOINT . $query;

        $session = curl_init($query_url);
        curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
        $json = curl_exec($session);

        $responseHttpCode = curl_getinfo($session, CURLINFO_HTTP_CODE);

        if ($responseHttpCode == '404'){
            return array("code" => self::SERVICE_UNAVAILABLE);
        }

        $response = json_decode($json, true);

        return (($response["response"]["error"]["type"] == $ERROR_CODE)? array("code" => self::BAD_KEY) : array("code" => self::TRUE_KEY));
    }

    public function getWeather($latitude, $longitude, $unit)
    {
        $queryTail = "geolookup/conditions/q";
        $query = $this->apiKey ."/{$queryTail}/{$latitude},{$longitude}.json";

        $query_url = self::API_ENDPOINT . $query;

        $session = curl_init($query_url);
        curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
        $json = curl_exec($session);

        $responseHttpCode = curl_getinfo($session, CURLINFO_HTTP_CODE);

        if ($responseHttpCode == '404'){
            return array("code" => self::SERVICE_UNAVAILABLE);
        }

        $weatherResult = json_decode($json, true);

        $code = $weatherResult["current_observation"]["icon"];
        $temp = $weatherResult["current_observation"]["temp_f"];

        $pointPosition = array("latitude" => $latitude, "longitude" => $longitude);
        $icon = $this->getWeatherIcon($weatherResult["current_observation"]["icon"]);

        if ($unit == 'metrical') {
            $temp = WF\fahrenheitToCelsius($weatherResult["current_observation"]["temp_f"]);
            $windSpeed = WF\mphToKph($weatherResult["current_observation"]["wind_mph"]);
        } else {
            $temp = $weatherResult["current_observation"]["temp_f"];
            $windSpeed = $weatherResult["current_observation"]["wind_mph"];
        }

        $windDegree = $weatherResult["current_observation"]["wind_degrees"];
        $wind = array("windSpeed" => $windSpeed, "windDegree" => $windDegree);

        return array('pointPos' => $pointPosition, 'icon' => $icon, 'temp' => $temp, 'wind' => $wind);
    }
}