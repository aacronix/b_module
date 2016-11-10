<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();
require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/weather_service/defines.php");
use TL\weather\weather_functions as WF;

require HEADER;

$content = WF\getTemplate('astronaut');
$template = json_decode($content)->content;

require FOOTER;