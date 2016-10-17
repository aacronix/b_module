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
background: {$widget->getBackgroundColor()};
}

#widget-wrapper-$widgetId .b-widget .condition, #widget-wrapper-$widgetId .b-widget .text, #widget-wrapper-$widgetId .b-widget .temp{
color: {$widget->getMajorTextColor()};
}

#widget-wrapper-$widgetId .b-widget .by-provider{
color: {$widget->getExtraTextColor()};
}
</style>

<div id='widget-wrapper-$widgetId'>
    <div class='b-widget astronaut clearfix' title='$widgetTitle. Ветер $windDirectionMessage'>
        <div class='weather-row clearfix'>
            <div class='weather-cell condition'>
            <i class='weather-condition wi $icon main-font'></i>
            </div>
            <span class='v-delimetr'></span>
            <div class='weather-cell text'>
                <p class='text-line first-line'>{$widget->getTitle()}</p>
                <p class='text-line second-line time'></p>
            </div>
        </div>
        <div class='weather-row'>
            <div class='temp'><span class='sign'>&nbsp;</span>$temp<span class='measure'>&deg;$tempUnit</span></div>
        </div>
        $providerInfo
</div></div>
";
}