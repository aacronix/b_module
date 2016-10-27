<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/weather_service/defines.php");

$templateName = $_REQUEST['template_name'];

$response = '';

function getTemplate($name)
{
    return file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/weather_service/templates/" . $name . "/memplate.mstch");
}

$template = getTemplate($templateName);

if (strlen($template) > 0){
    $response = ['code' => 1, 'content' => $template];
} else {
    $response = ['code' => 0, 'content' => ''];
}

echo(json_encode($response));