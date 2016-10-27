<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/weather_service/defines.php");
require_once FUNCTIONS_ROOT . '/functions.php';
use TL\weather\weather_functions as WF;

$arResult = array();
if ($this->StartResultCache())
{
    $widgetId = $arParams['WIDGET'];
    $templateName = $arParams['COMPONENT_TEMPLATE'];

    WF\updateTemplateName($widgetId, $templateName);

    $this->IncludeComponentTemplate();
}
