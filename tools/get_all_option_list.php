<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/weather_service/defines.php");

use TL\weather\main;

require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/weather_service/classes/widget.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/weather_service/classes/option.php");
header('Content-Type: application/x-javascript; charset=' . LANG_CHARSET);

$optionsList = main\CWeatherOption::GetOptionList($_REQUEST['id']);;

if (count($optionsList) > 0) {
    foreach ($optionsList as $option) {
        $widgetId = $option->getName();
        $widget = main\CWeatherWidget::GetWeatherWidgetByWidgetId($widgetId);

        $widgetsParametres[] = ['widget' => $widget->toJson(), 'options' => $option->toJson()];
    }
}

echo(json_encode($widgetsParametres));