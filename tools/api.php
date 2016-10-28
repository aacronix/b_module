<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
header('Content-Type: application/x-javascript; charset=' . LANG_CHARSET);

use TL\weather\main;

require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/weather_service/classes/widget.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/weather_service/classes/option.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/weather_service/defines.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/weather_service/classes/CWidget.php");

$notFoundErrorMessage = ['code' => 0, 'content' => '', 'description' => 'Not Found Exception', 'http_code' => 404];
$undefinedMethodErrorMessage = ['code' => 0, 'content' => '', 'description' => 'Undefined method', 'http_code' => 400];
$missedParametresErrorMessage = ['code' => 0, 'content' => '', 'description' => 'Missed parametres', 'http_code' => 400];
$requestErrorMessage = ['code' => 0, 'content' => '', 'description' => 'Request error', 'http_code' => 400];

if (isset($_REQUEST['action'])) {
    if ($_REQUEST['action'] == "copy_widget") {
        if (isset($_REQUEST['name']) && isset($_REQUEST['information']) && isset($_REQUEST['providersList'])) {
            $name = $_REQUEST['name'];
            $information = $_REQUEST['information'];
            $providersList = $_REQUEST['providersList'];


            $requestArray = [];

            foreach ($_REQUEST['information'] as $key => $value) {
                $requestArray[$key] = $value;
            }

            foreach ($_REQUEST['providers_list'] as $key => $value) {
                if (strlen($value['api_key'])) {
                    $requestArray[$value['name'] . '_api_key'] = $value['api_key'];
                }

                if (strlen($value['app_key'])) {
                    $requestArray[$value['name'] . '_app_key'] = $value['app_key'];
                }
            }

            if ($requestArray) {
                $insertId = main\CWeatherWidget::InsertNewWidget($name);
                main\CWeatherOption::InsertOptionsList($requestArray, $insertId);
                //TODO FIXME: сделать обработку в случае неуспешного добавления опций

                $widget = main\CWeatherWidget::GetWeatherWidgetByWidgetId($insertId);
                $options = main\CWeatherOption::GetOptionList($insertId)[0];

                $response = ['code' => 1, 'content' => ['widget' => $widget->toJson(), 'options' => $options->toJson()], 'description' => 'Success', 'http_code' => 200];
                echo(json_encode($response));
            } else {
                echo(json_encode($requestErrorMessage));
            }
        } else {
            echo(json_encode($missedParametresErrorMessage));
        }
    } else if ($_REQUEST['action'] == "delete_widget") {
        if (isset($_REQUEST['id'])) {
            $widgetId = $_REQUEST['id'];

            $result = main\CWeatherWidget::DeleteWidgetById($widgetId);
            $response = ['code' => 1, 'content' => '', 'description' => 'Success', 'http_code' => 200];
            echo(json_encode($response));
        } else {
            echo(json_encode($missedParametresErrorMessage));
        }
    } else if ($_REQUEST['action'] == "get_all_options") {
        echo json_encode($successMessage);
    } else if ($_REQUEST['action'] == "get_page_template") {
        echo json_encode($successMessage);
    } else if ($_REQUEST['action'] == "set_template_name") {
        echo json_encode($successMessage);
    } else if ($_REQUEST['action'] == "get_all_widgets") {
        echo json_encode($successMessage);
    } else if ($_REQUEST['action'] == "update_widgets") {
        echo json_encode($successMessage);
    } else if ($_REQUEST['action'] == "weather_api_get_weather") {
        echo json_encode($successMessage);
    } else if ($_REQUEST['action'] == "weather_api_is_valid_key") {
        echo json_encode($successMessage);
    } else {
        echo json_encode($undefinedMethodErrorMessage);
    }
} else {
    echo json_encode($notFoundErrorMessage);
}