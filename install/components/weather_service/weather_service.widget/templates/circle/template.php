<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();

require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/weather_service/defines.php");

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
    <div class='b-widget circle clearfix' title='$widgetTitle. Ветер $windDirectionMessage'>
        <div class='weather-row clearfix'>
            <div class='weather-cell condition'>
                <i class='weather-condition wi $icon main-font'></i>
            </div>
        </div>
        <div class='weather-row'>
            <div class='temp'>$temp<span class='measure'>&deg;C</span></div>
        </div>
</div></div>
";
}
