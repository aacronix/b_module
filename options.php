<?
global $APPLICATION;

require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/weather_service/defines.php");

CModule::IncludeModule(WEATHER_SERVICE_MODULE_ID);

$APPLICATION->SetAdditionalCSS("/react/public/css/style.css");
$APPLICATION->SetAdditionalCSS("/react/public/css/rc-slider/style.css");
$APPLICATION->AddHeadScript('/react/public/script/microevent.js');
$APPLICATION->AddHeadScript('/react/public/script/jquery.js');
$APPLICATION->AddHeadScript('/react/public/script/deepcopy.min.js');

$APPLICATION->SetAdditionalCSS("/bitrix/css/weather_service/component/style.css");
$APPLICATION->SetAdditionalCSS("/bitrix/css/weather_service/weather-icons/weather-icons.min.css");
$APPLICATION->SetAdditionalCSS("/bitrix/css/weather_service/weather-icons/weather-icons-wind.min.css");
?>
<script>
    function getParameterByName(name, url) {
        if (!url) url = window.location.href;
        name = name.replace(/[\[\]]/g, "\\$&");
        var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
            results = regex.exec(url);
        if (!results) return null;
        if (!results[2]) return '';
        return decodeURIComponent(results[2].replace(/\+/g, " "));
    }

    window.pageLang = getParameterByName('lang');
</script>
<div id="weather-container"></div>
<script src="/react/resources/bundle.js"></script>