<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
header('Content-Type: application/x-javascript; charset=' . LANG_CHARSET);

use TL\weather\main;

require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/weather_service/classes/widget.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/weather_service/classes/option.php");

$insertId = main\CWeatherWidget::InsertNewWidget('Copy');

$latitudeSelector = 'latitude';
$longitudeSelector = 'longitude';
$newWeatherProviderSelector = 'weather_provider';
$widgetTitleSelector = 'widget_title';
$wundergroundApiKeySelector = 'wunderground_api_key';
$forecastioApiKeySelector = 'forecastio_api_key';
$weathertriggerApiKeySelector = 'weathertrigger_api_key';
$weathertriggerAppKeySelector = 'weathertrigger_app_key';
$apixuApiKeySelector = 'apixu_api_key';
$openweatherApiKeySelector = 'openweather_api_key';
$backgroundColorKeySelector = 'background_color';
$majorTextColorSelector = 'major_text_color';
$extraTextColorSelector = 'extra_text_color';
$updateIntervalSelector = 'update_interval';
$providerInfoSelector = 'show_provider_info';

main\CWeatherOption::InsertOption($insertId, "$latitudeSelector",                          $_REQUEST["latitude"]);
main\CWeatherOption::InsertOption($insertId, "$longitudeSelector",                         $_REQUEST["longitude"]);
main\CWeatherOption::InsertOption($insertId, "$widgetTitleSelector",                       $_REQUEST["widgetTitle"]);
main\CWeatherOption::InsertOption($insertId, "$newWeatherProviderSelector",                $_REQUEST["weatherProvider"]);
main\CWeatherOption::InsertOption($insertId, "$wundergroundApiKeySelector",                $_REQUEST["wundergroundApiKey"]);
main\CWeatherOption::InsertOption($insertId, "$forecastioApiKeySelector",                  $_REQUEST["forecastioApiKey"]);
main\CWeatherOption::InsertOption($insertId, "$weathertriggerApiKeySelector",              $_REQUEST["weathertriggerApiKeySelector"]);
main\CWeatherOption::InsertOption($insertId, "$weathertriggerAppKeySelector",              $_REQUEST["weathertriggerAppKeySelector"]);
main\CWeatherOption::InsertOption($insertId, "$apixuApiKeySelector",                       $_REQUEST["apixuApiKey"]);
main\CWeatherOption::InsertOption($insertId, "$openweatherApiKeySelector",                 $_REQUEST["openweatherApiKey"]);
main\CWeatherOption::InsertOption($insertId, "$backgroundColorKeySelector",                $_REQUEST["backgroundColorKey"]);
main\CWeatherOption::InsertOption($insertId, "$majorTextColorSelector",                    $_REQUEST["majorTextColor"]);
main\CWeatherOption::InsertOption($insertId, "$extraTextColorSelector",                    $_REQUEST["extraTextColor"]);
main\CWeatherOption::InsertOption($insertId, "$updateIntervalSelector",                    $_REQUEST["updateInterval"]);
main\CWeatherOption::InsertOption($insertId, "$providerInfoSelector",                      $_REQUEST["showProviderInfo"]);