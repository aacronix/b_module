<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arResult = array();
if ($this->StartResultCache())
{
    $arResult['FROM_CURRENT_POSITION'] = $arParams['FROM_CURRENT_POSITION'];
    $arResult['LATITUDE'] = $arParams['LATITUDE'];
    $arResult['LONGITUDE'] = $arParams['LONGITUDE'];
    $arResult['WIDGET'] = $arParams['WIDGET'];

    $arResult['ELEMENT_COLOR'] = $arParams['ELEMENT_COLOR'];
    $arResult['CONDITION_COLOR'] = $arParams['CONDITION_COLOR'];
    $arResult['TEMP_COLOR'] = $arParams['TEMP_COLOR'];


    $this->IncludeComponentTemplate();
}
