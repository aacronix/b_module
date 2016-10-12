<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/weather_service/defines.php");

header('Content-Type: application/x-javascript; charset=' . LANG_CHARSET);

$action = $_REQUEST['action'];
$provider = $_REQUEST['provider'];
$apiKey = $_REQUEST['apiKey'];
$appKey = $_REQUEST['appKey'];
$latitude = 55.75;
$longitude = 37.62;
$unit = 'metrical';
if ($action == 'get-weather') {
    $latitude = $_REQUEST['latitude'];
    $longitude = $_REQUEST['longitude'];
    $unit = $_REQUEST['unit'];
}
use TL\weather\CFactory as factory;

require_once(CLASSES_ROOT . "/CFactory.php");
$factory = new factory\CFactory();
$provider = $factory->createWeatherProvider($provider, $apiKey, $appKey);
if ($action == 'get-weather') {
    echo json_encode($provider->getWeather($latitude, $longitude, $unit));
}
if ($action == 'validateKey') {
    echo json_encode($provider->isValidApiKey($apiKey, $appKey));
}