<?php

$backgroundColor = $widget->getBackgroundColor();
$majorTextColor = $widget->getMajorTextColor();
$majorTextSize = $widget->getMajorTextSize();
$extraTextColor = $widget->getExtraTextColor();
$extraTextSize = $widget->getExtraTextSize();
$temperatureTextSize = $widget->getTemperatureTextSize();
$temperatureIconSize = $widget->getTemperatureIconSize();
$extraTextSize = $widget->getExtraTextSize();
$weatherIconSize = $widget->getWeatherIconSize();
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
    'majorTextSize' => $majorTextSize,
    'extraTextColor' => $extraTextColor,
    'extraTextSize' => $extraTextSize,
    'temperatureTextSize' => $temperatureTextSize,
    '$temperatureIconSize' => $temperatureIconSize,
    'weatherIconSize' => $weatherIconSize,
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