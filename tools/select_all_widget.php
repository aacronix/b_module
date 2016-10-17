<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/weather_service/defines.php");

use TL\weather\main;

require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/weather_service/classes/widget.php");
header('Content-Type: application/x-javascript; charset=' . LANG_CHARSET);

$dbWidgets = main\CWeatherWidget::SelectWeatherWidgetsList();
while ($arWidget = $dbWidgets->Fetch()) {
    $arWidgets[] = $arWidget;
}

echo json_encode($arWidgets);