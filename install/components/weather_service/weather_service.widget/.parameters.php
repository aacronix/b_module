<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use TL\weather\main;

require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/weather_service/classes/widget.php");

$dbWidgets = main\CWeatherWidget::SelectWeatherWidgetsList($b = "sort", $o = "asc", array("ACTIVE" => "Y"));

$widgets = array();

$widgets[] = array('id' => 'w_0', 'name' => 'Виджет по умолчанию');

while ($arWidget = $dbWidgets->Fetch()) {
    $widgets[] = array('id' => $arWidget["WIDGET_ID"], 'name' => $arWidget["NAME"]);
}

$values = array();

foreach ($widgets as $element) {
    $values[$element['id']] = $element['name'];
}

$arComponentParameters = array(
    "GROUPS" => array(
        "POSITION_GROUP" => array(
            "NAME" => GetMessage("POSITION_PARAMS_GROUP_TITLE"),
            "SORT" => 101
        ),
        "STYLE" => array(
            "NAME" => GetMessage("STYLE_GROUP_TITLE"),
            "SORT" => 101
        ),
    ),
    "PARAMETERS" => array(
        "WIDGET" => array(
            "PARENT" => "STYLE",
            "NAME" => 'Виджет',
            "TYPE" => "LIST",
            "VALUES" => $values,
            "DEFAULT" => "SORT",
        ),
    ),
);
?>
