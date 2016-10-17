<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();

define('HEADER', $_SERVER['DOCUMENT_ROOT'] . "/bitrix/components/weather_service/weather_service.widget/parts/header.php");

require HEADER;

if ($icon == null || $temp == null) {
    global $USER;
    if ($USER->IsAdmin()) {
        require ERROR_PAGE_ADMIN;
    } else {
        require ERROR_PAGE_USER;
    }
} else {
    echo "
<style>
#widget-wrapper-$widgetId{
    float: left;
    clear: both;
}

#widget-wrapper-$widgetId .b-widget{
background: $parameters[$backgroundColorKeySelector];
}

#widget-wrapper-$widgetId .b-widget .condition, #widget-wrapper-$widgetId .b-widget .text, #widget-wrapper-$widgetId .b-widget .temp{
color: $parameters[$majorTextColorSelector];
}

#widget-wrapper-$widgetId .b-widget .by-provider{
color: $parameters[$extraTextColorSelector];
}
</style>

<div id='widget-wrapper-$widgetId'>
    <div class='b-widget elementary clearfix' title='$widgetTitle. Ветер $windDirectionMessage'>
        <div class='weather-row clearfix'>
            <div class='weather-cell condition'>
            <i class='weather-condition wi $icon main-font'></i>
            </div>
            <div class='weather-cell text'>
                <p class='text-line first-line'>$widgetTitle</p>
                <div class='temp'>$temp<span class='measure'>&deg;C</span></div>
            </div>
</div></div></div>
";
}