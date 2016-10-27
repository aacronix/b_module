<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/weather_service/classes/widget.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/weather_service/defines.php");

use TL\weather\main;

$widgetId = $_REQUEST['WIDGET_ID'];
$templateName = $_REQUEST['TEMPLATE_NAME'];

main\CWeatherWidget::UpdateWidgetTemplateName($widgetId, $templateName);

return true;
