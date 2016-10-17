<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();

use TL\weather\weather_functions as WF;

IncludeModuleLangFile(__FILE__);

global $APPLICATION;
global $CACHE_MANAGER;

$widgetId = $arParams['WIDGET'];

require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/weather_service/defines.php");
require_once FUNCTIONS_ROOT . '/functions.php';

$sModuleId = 'weather_service';
CJSCore::Init(array("jquery"));

$APPLICATION->SetAdditionalCSS("/bitrix/css/weather_service/main/style.css");
$APPLICATION->SetAdditionalCSS("/bitrix/css/weather_service/component/style.css");
$APPLICATION->SetAdditionalCSS("/bitrix/css/weather_service/weather-icons/weather-icons.min.css");
$APPLICATION->SetAdditionalCSS("/bitrix/css/weather_service/weather-icons/weather-icons-wind.min.css");

$latitudeSelector = 'latitude_' . $widgetId;
$longitudeSelector = 'longitude_' . $widgetId;
$newWeatherProviderSelector = 'weather_provider_' . $widgetId;
$widgetTitleSelector = 'widget_title_' . $widgetId;
$wundergroundApiKeySelector = 'wunderground_api_key_' . $widgetId;
$forecastioApiKeySelector = 'forecastio_api_key_' . $widgetId;
$weathertriggerApiKeySelector = 'weathertrigger_api_key_' . $widgetId;
$weathertriggerAppKeySelector = 'weathertrigger_app_key_' . $widgetId;
$apixuApiKeySelector = 'apixu_api_key_' . $widgetId;
$openweatherApiKeySelector = 'openweather_api_key_' . $widgetId;
$widgetUpdateIntervalSelector = 'widget_update_interval_' . $widgetId;

$latitude = COption::GetOptionString($sModuleId, $latitudeSelector);
$longitude = COption::GetOptionString($sModuleId, $longitudeSelector);
$widgetTitle = COption::GetOptionString($sModuleId, $widgetTitleSelector);
$newWeatherProvider = COption::GetOptionString($sModuleId, $newWeatherProviderSelector, DEFAULT_WEATHER_PROVIDER);
$wundergroundApiKey = COption::GetOptionString($sModuleId, $wundergroundApiKeySelector);
$forecastioApiKey = COption::GetOptionString($sModuleId, $forecastioApiKeySelector);
$weathertriggerApiKey = COption::GetOptionString($sModuleId, $weathertriggerApiKeySelector);
$weathertriggerAppKey = COption::GetOptionString($sModuleId, $weathertriggerAppKeySelector);
$apixuApiKey = COption::GetOptionString($sModuleId, $apixuApiKeySelector);
$openweatherApiKey = COption::GetOptionString($sModuleId, $openweatherApiKeySelector);
$widgetUpdateInterval = COption::GetOptionString($sModuleId, $widgetUpdateIntervalSelector, DEFAULT_UPDATE_INTERVAL);
$widgetUpdateInterval = $widgetUpdateInterval ? $widgetUpdateInterval : DEFAULT_UPDATE_INTERVAL;
$parameters = array(
    'latitude' => $latitude,
    'longitude' => $longitude,
    'provider' => $newWeatherProvider,
    'widget_title' => $widgetTitle,
    'wunderground_api_key' => $wundergroundApiKey,
    'forecastio_api_key' => $forecastioApiKey,
    'weathertrigger_api_key' => $weathertriggerApiKey,
    'weathertrigger_app_key' => $weathertriggerAppKey,
    'apixu_api_key' => $apixuApiKey,
    'openweather_api_key' => $openweatherApiKey,
    'update-interval' => $widgetUpdateInterval,
);

$obCache = new CPHPCache();
$lifeTime = 60 * $widgetUpdateInterval;
$cacheID = md5(serialize($widgetId . $sModuleId));
$cacheDir = "/" . $cacheID;

// если есть кеш и он ещё акутален
if ($obCache->InitCache($lifeTime, $cacheID, $cacheDir)) {
    $weather = $obCache->GetVars();
} else { // иначе получаем новые данные
    $weather = json_decode(WF\getWeather($parameters['provider'], $parameters[$parameters['provider'] . '_api_key'], $parameters[$parameters['provider'] . '_app_key'], $parameters['latitude'], $parameters['longitude'], 'metrical'),
        true);
}

if ($obCache->StartDataCache()): // тагируем кеш
    $CACHE_MANAGER->StartTagCache($cacheDir);
    $CACHE_MANAGER->RegisterTag($cacheDir);
    $CACHE_MANAGER->EndTagCache();

    $obCache->EndDataCache($weather);
endif;

$icon = $weather["icon"];
$temp = $weather["temp"];

$windSpeed = $weather["wind"]["windSpeed"];
$windDegree = $weather["wind"]["windDegree"];
$windDegreeInt = WF\roundToInt($windDegree);

$windDirectionMessage = GetMessage(WF\getWindDirection($windDegree));
$windSpeedUnit = GetMessage('WIND_SPEED_KPH');

//$OUTPUT .= "<h1 class='provider-t'>
//    Current weather provider is $weatherProvider
//</h1>";
if ($icon == null || $temp == null) {
    global $USER;
    if ($USER->IsAdmin()) {
        $OUTPUT .= "<div class=\"error-block\">
            <p class='message'>
                Проверьте api-ключи для погодного провайдера<br>
            </p>
        </div>";
    } else {
        $OUTPUT .= "<div class=\"error-block\">
    <p class='message'>
        Погоды сегодня не будет,<br> погода заболела
    </p>
</div>";
    }
} else {
    $OUTPUT .= "<div id='weather-block-$widgetId' class='b-widget v_2_1 clearfix' title='Ветер $windDirectionMessage'>
      <span class='title'>$widgetTitle</span>
      <div class='weather-row clearfix'>
          <div class='weather-cell condition'>
              <i class='weather-condition wi $icon'></i>
          </div>
          <div class='weather-cell temperature'>
              $temp <i class='wi wi-celsius'></i>
          </div>
      </div>
    </div>
";
}
echo $OUTPUT;
