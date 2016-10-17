<?
global $APPLICATION;

require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/weather_service/defines.php");

CModule::IncludeModule(WEATHER_SERVICE_MODULE_ID);

$APPLICATION->SetAdditionalCSS("https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css");
$APPLICATION->SetAdditionalCSS("/react/public/css/style.css");
$APPLICATION->AddHeadScript('/react/public/script/microevent.js');
$APPLICATION->AddHeadScript('/react/public/script/jquery.js');
?>
<div id="weather-container"></div>
<script src="/react/resources/bundle.js"></script>