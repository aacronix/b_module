<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
header('Content-Type: application/x-javascript; charset=' . LANG_CHARSET);

use TL\weather\main;

require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/weather_service/classes/widget.php");

if (isset($_REQUEST['widgetId']) && isset($_REQUEST['newName'])){
    $widgetId = $_REQUEST['widgetId'];
    $newName = $_REQUEST['newName'];

    echo main\CWeatherWidget::RenameWidget($widgetId, $newName);
} else {
    echo '{code: 0}';
}