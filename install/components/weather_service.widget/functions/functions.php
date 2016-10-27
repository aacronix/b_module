<?php
namespace TL\weather\weather_functions;

function fahrenheitToCelsius($inpTemp)
{
    return round(($inpTemp - 32) * 5 / 9);
}

function celsiusToFahrenheit($inpTemp)
{
    return round(($inpTemp * 9 / 5) + 32);
}

function mphToKph($mph)
{
    return (round(($mph * 1.60934) * 10) / 10);
}

function kphToMph($kph)
{
    return (round(($kph / 1.60934) * 10) / 10);
}

function roundToInt($value)
{
    return round($value);
}

function getWindDirection($degree)
{
    $direction = 'WD_NESW';
    if ($degree > 337.5 || $degree <= 22.5) {
        $direction = 'WD_N';
    } else if ($degree > 22.5 && $degree <= 67.5) {
        $direction = 'WD_NE';
    } else if ($degree > 67.5 && $degree <= 112.5) {
        $direction = 'WD_E';
    } else if ($degree > 112.5 && $degree <= 157.5) {
        $direction = 'WD_SE';
    } else if ($degree > 157.5 && $degree <= 202.5) {
        $direction = 'WD_S';
    } else if ($degree > 202.5 && $degree <= 247.5) {
        $direction = 'WD_SW';
    } else if ($degree > 247.5 && $degree <= 292.5) {
        $direction = 'WD_W';
    } else if ($degree > 292.5 && $degree <= 337.5) {
        $direction = 'WD_NW';
    }

    return $direction;
}

function getWeather($provider, $apiKey, $appKey, $latitude, $longitude, $unit)
{
    global $APPLICATION;

    $data = array('action' => 'get-weather', 'provider' => $provider, 'apiKey' => $apiKey, 'appKey' => $appKey, 'latitude' => $latitude, 'longitude' => $longitude, 'unit' => $unit);
    $query_url = WEATHER_TOOLS . '/weather_api.php' . '/?' . http_build_query($data);

    $out = "-1";
    if ($curl = curl_init()) {
        curl_setopt($curl, CURLOPT_URL, $query_url);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
        $out = curl_exec($curl);
        writeLog(curl_error($curl), 'curl errors ');
        curl_close($curl);
    }

    return $out;
}

function keyIsValid($provider, $apiKey, $appKey)
{
    $data = array('action' => 'validateKey', 'provider' => $provider, 'apiKey' => $apiKey, 'appKey' => $appKey);
    $query_url = WEATHER_TOOLS . '/weather_api.php' . '/?' . http_build_query($data);
    $out = "-1";
    if ($curl = curl_init()) {
        curl_setopt($curl, CURLOPT_URL, $query_url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $out = curl_exec($curl);
        curl_close($curl);
    }

    return $out;
}

function writeLog($content, $from)
{
    $file = LOG . 'log.txt';

    $current = file_get_contents($file);
    $current .= $from . "  ";
    $current .= print_r($content, true);
    $current .= "\n";
    file_put_contents($file, $current);
}

function getTemplate($name)
{
    global $APPLICATION;

    $data = array('template_name' => $name);
    $query_url = WEATHER_TOOLS . '/get_page_template.php' . '/?' . http_build_query($data);

    $out = "-1";
    if ($curl = curl_init()) {
        curl_setopt($curl, CURLOPT_URL, $query_url);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
        $out = curl_exec($curl);
        writeLog(curl_error($curl), 'curl errors ');
        curl_close($curl);
    }

    return $out;
}

function updateTemplateName($widgetId, $name)
{
    global $APPLICATION;

    $data = array('WIDGET_ID' => $widgetId, 'TEMPLATE_NAME' => $name);
    $query_url = WEATHER_TOOLS . '/update_template_name.php' . '/?' . http_build_query($data);

    $out = "-1";
    if ($curl = curl_init()) {
        curl_setopt($curl, CURLOPT_URL, $query_url);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
        $out = curl_exec($curl);
        writeLog(curl_error($curl), 'curl errors ');
        curl_close($curl);
    }

    return $out;
}

//TODO: добавить функцию по выбору единиц измерения по стране, сейчас это реализовано так, что в каждом провайдере делается отдельная выборка