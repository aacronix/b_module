<?php

use TL\weather\weather_functions as WF;
use TL\weather\main;

require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/weather_service/php_common/Mustache/Autoloader.php");
Mustache_Autoloader::register();

$m = new Mustache_Engine;

const SERVICE_UNAVAILABLE = 'SERVICE_UNAVAILABLE';

require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/weather_service/defines.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/weather_service/classes/CWidget.php");

require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/weather_service/classes/option.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/js/weather_service/common/common.php");

IncludeModuleLangFile(__FILE__);

global $APPLICATION;
global $CACHE_MANAGER;

$widgetId = $arParams['WIDGET'];

require_once FUNCTIONS_ROOT . '/functions.php';

$sModuleId = 'weather_service';
CJSCore::Init(array("jquery"));

$APPLICATION->SetAdditionalCSS("/bitrix/css/weather_service/main/style.css");
$APPLICATION->SetAdditionalCSS("/bitrix/css/weather_service/component/style.css");
$APPLICATION->SetAdditionalCSS("/bitrix/css/weather_service/weather-icons/weather-icons.min.css");
$APPLICATION->SetAdditionalCSS("/bitrix/css/weather_service/weather-icons/weather-icons-wind.min.css");

$optionListResponse = main\CWeatherOption::GetOptionList($widgetId);

$widget = $optionListResponse[0];
$weatherProvider = $widget->getWeatherProvider();
$activeProvider = null;

foreach ($widget->getProvidersList() as $key => $value){
    if ($value->getActivity() == $weatherProvider){
        $activeProvider = $value;
        break;
    }
}

$obCache = new CPHPCache();
$lifeTime = 60 * $widget->getUpdateInterval();
$cacheID = md5(serialize($widgetId . $sModuleId));
$cacheDir = "/" . $cacheID;
$from = 'server';

// если есть кеш и он ещё акутален и если сервис доступен был ранее
if ($obCache->InitCache($lifeTime, $cacheID, $cacheDir)/* && $parameters[$providerAvailabilitySelector] == 'Y'*/) {
    $weather = $obCache->GetVars();
    $from = 'cache';
} else { // иначе получаем новые данные

    $weather = json_decode(WF\getWeather($activeProvider->getName(),
        $activeProvider->getApiKey(),
        $activeProvider->getAppKey(),
        $widget->getLatitude(),
        $widget->getLongitude(), 'metrical'),
        true);
//    if($weather["code"] && $weather["code"] == SERVICE_UNAVAILABLE){
//        main\CWeatherOption::InsertOption($widgetId, "$providerAvailabilitySelector", 'N');
//    } else {
//        main\CWeatherOption::InsertOption($widgetId, "$providerAvailabilitySelector", 'Y');
//    }
}

if ($obCache->StartDataCache()/*  && $parameters[$providerAvailabilitySelector] == 'Y'*/): // тагируем кеш
    $CACHE_MANAGER->StartTagCache($cacheDir);
    $CACHE_MANAGER->RegisterTag($cacheDir);
    $CACHE_MANAGER->EndTagCache();

    $obCache->EndDataCache($weather);
endif;

$icon = $weather["icon"];

$measurementSystem = $widget->getMeasurementSystem();

$temp = $weather["temp"];
$windSpeed = $weather["wind"]["windSpeed"];
$tempUnit = 'C';
$windSpeedUnit = 'км/ч';

if ($measurementSystem == 'britain'){
    $temp = WF\celsiusToFahrenheit($temp);
    $windSpeed = WF\kphToMph($windSpeed);
    $tempUnit = 'F';
    $windSpeedUnit = 'mph';
}

$providerName = $activeProvider->getName();
$windDegree = $weather["wind"]["windDegree"];
$windDegreeInt = WF\roundToInt($windDegree);

$windDirectionMessage = GetMessage(WF\getWindDirection($windDegree));

