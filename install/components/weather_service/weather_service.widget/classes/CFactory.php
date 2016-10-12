<?php

namespace TL\weather\CFactory;

use TL\weather\COpenWeatherWeatherProvider as openwp;
use TL\weather\CYahooWeatherProvider as yhwp;
use TL\weather\CForecastIO as fiowp;
use TL\weather\CWunderground as wuwp;
use TL\weather\CWeatherTrigger as wtwp;
use TL\weather\CApixu as awp;
require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/weather_service/defines.php");

require_once(CLASSES_ROOT . "/IWeatherProvider.php");
require_once(CLASSES_ROOT . "/COpenWeatherWeatherProvider.php");
require_once(CLASSES_ROOT . "/CYahooWeatherProvider.php");
require_once(CLASSES_ROOT . "/CForecastIO.php");
require_once(CLASSES_ROOT . "/CWunderground.php");
require_once(CLASSES_ROOT . "/CWeatherTrigger.php");
require_once(CLASSES_ROOT . "/CApixu.php");

class CFactory
{
    public function createWeatherProvider($weatherProvider, $apiKey, $appKey)
    {
        if ($weatherProvider === "yahooweather") {
            $provider = new yhwp\CYahooWeatherProvider(null, null);
        } else if ($weatherProvider == "forecastio") {
            $provider = new fiowp\CForecastIO($apiKey, null);
        } else if ($weatherProvider == "openweather") {
            $provider = new openwp\COpenWeatherWeatherProvider($apiKey, null);
        } else if ($weatherProvider == "wunderground") {
            $provider = new wuwp\CWunderground($apiKey, null);
        } else if ($weatherProvider == "weathertrigger") {
            $provider = new wtwp\CWeatherTrigger($apiKey, $appKey);
        } else if ($weatherProvider == "apixu") {
            $provider = new awp\CApixu($apiKey, null);
        } else {
            $provider = new yhwp\CYahooWeatherProvider(null, null);
        }

        return $provider;
    }
}