<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();
require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/weather_service/defines.php");
use TL\weather\weather_functions as WF;

require HEADER;

$content = WF\getTemplate('astronaut');
$template = json_decode($content)->content;

$backgroundColor = $widget->getBackgroundColor();
$majorTextColor = $widget->getMajorTextColor();
$extraTextColor = $widget->getExtraTextColor();
$borderColor = $widget->getBorderColor();

if ($icon === null || $temp === null) {
    global $USER;
    if ($USER->IsAdmin()) {
        require ERROR_PAGE_ADMIN;
    } else {
        require ERROR_PAGE_USER;
    }
} else {
    echo "{$m->render($template, array('widgetTitle' => $widget->getTitle(), 
    'widgetId' => $widgetId,
    'backgroundColor' => $backgroundColor,
    'majorTextColor' => $majorTextColor,
    'extraTextColor' => $extraTextColor,
    'windDirectionMessage' => $windDirectionMessage, 
    'title' => $widget->getTitle(), 
    'temp' => $temp, 
    'tempUnit' => $tempUnit,
    'borderColor' => $borderColor,
    'icon' => $icon,
    'hasProviderInfo' => ($widget->getProviderInfo() == 'true'),
    'from' => $from,
    'providerName' => $providerName))}

";
}