<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
header('Content-Type: application/x-javascript; charset=' . LANG_CHARSET);

require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/weather_service/classes/widget.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/weather_service/classes/option.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/weather_service/defines.php");

use TL\weather\main;

if (isset($_REQUEST["widgets"])) {
    $widgets = $_REQUEST["widgets"];

    $retrievedInformation = [];

    foreach ($widgets as $wKey => $wValue) {
        $widgetId = $wValue['widget']['widget_id'];

        foreach ($wValue["options"]["information"] as $oKey => $oValue) { // options
            $retrievedInformation[$widgetId][$oKey] = $oValue;
        }
        foreach ($wValue["options"]["providers_list"] as $oKey => $oValue) {
            if (strlen($oValue["api_key"]) > 0) {
                $retrievedInformation[$widgetId][$oValue["name"] . '_api_key'] = $oValue["api_key"];
            }
            if (strlen($oValue['app_key']) > 0) {
                $retrievedInformation[$widgetId][$oValue["name"] . '_app_key'] = $oValue["app_key"];
            }
        }
    }

    main\CWeatherOption::InsertOrUpdateOptionsList($retrievedInformation);

    foreach ($widgets as $wKey => $wValue) {
        main\CWeatherWidget::RenameWidget($wValue["widget"]["widget_id"], $wValue["widget"]["name"]);
    }

    echo true;
} else {
    echo 'missed parameters';
}
