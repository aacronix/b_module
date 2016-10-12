<?php

use TL\weather\weather_functions as WF;
use TL\weather\main;

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
$activeProvider = $widget->getActiveProviderRef();

$obCache = new CPHPCache();
$lifeTime = 60 * $widget->getUpdateInterval();
$cacheID = md5(serialize($widgetId . $sModuleId));
$cacheDir = "/" . $cacheID;
$from = 'server';

//// если есть кеш и он ещё акутален и если сервис доступен был ранее
//if ($obCache->InitCache($lifeTime, $cacheID, $cacheDir)/* && $parameters[$providerAvailabilitySelector] == 'Y'*/) {
//    $weather = $obCache->GetVars();
//    $from = 'cache';
//} else { // иначе получаем новые данные

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
//}
//
//if ($obCache->StartDataCache()/*  && $parameters[$providerAvailabilitySelector] == 'Y'*/): // тагируем кеш
//    $CACHE_MANAGER->StartTagCache($cacheDir);
//    $CACHE_MANAGER->RegisterTag($cacheDir);
//    $CACHE_MANAGER->EndTagCache();
//
//    $obCache->EndDataCache($weather);
//endif;

$icon = $weather["icon"];
$temp = $weather["temp"];

$windSpeed = $weather["wind"]["windSpeed"];
$windDegree = $weather["wind"]["windDegree"];
$windDegreeInt = WF\roundToInt($windDegree);

$windDirectionMessage = GetMessage(WF\getWindDirection($windDegree));
$windSpeedUnit = GetMessage('WIND_SPEED_KPH');

if ($widget->getProviderInfo() == 'Y') {
    $providerInfo = '<p class=\'by-provider\'>get from ' . $from . '. provided by ' . $activeProvider->getName() . '</p>';
} else {
    $providerInfo = '';
}