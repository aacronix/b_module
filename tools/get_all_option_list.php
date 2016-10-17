<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/weather_service/defines.php");

use TL\weather\main;

require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/weather_service/classes/option.php");
header('Content-Type: application/x-javascript; charset=' . LANG_CHARSET);

$optionsList = main\CWeatherOption::GetOptionList();;

if (count($optionsList) > 0) {
	foreach ($optionsList as $widget) {
		$widgetId = $widget->getName();

		$widgetsParametres[] = $widget->toJson();
	}
}

echo(json_encode($widgetsParametres));