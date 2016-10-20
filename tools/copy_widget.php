<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
header('Content-Type: application/x-javascript; charset=' . LANG_CHARSET);

use TL\weather\main;

require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/weather_service/classes/widget.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/weather_service/classes/option.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/weather_service/defines.php");

if (isset($_REQUEST['name'])) {
    $newName = $_REQUEST['name'];

    $insertId = main\CWeatherWidget::InsertNewWidget($newName);

    $requestArray = [];

    foreach ($_REQUEST['information'] as $key => $value) {
        $requestArray[$key] = $value;
    }

    foreach ($_REQUEST['providers_list'] as $key => $value) {
        if (strlen($value['api_key']) > 0) {
            $requestArray[$value['name'] . '_api_key'] = $value['api_key'];
        }

        if (strlen($value['app_key']) > 0) {
            $requestArray[$value['name'] . '_app_key'] = $value['app_key'];
        }
    }

    main\CWeatherOption::InsertOptionsList($requestArray, $insertId);

    $widget = main\CWeatherWidget::GetWeatherWidgetByWidgetId($insertId);
    $options = main\CWeatherOption::GetOptionList($insertId)[0];

    $response = ['widget'=>$widget->toJson(), 'options' => $options->toJson()];

    echo (json_encode($response));
} else {
    echo 'missed parameters';
}