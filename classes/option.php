<?php
namespace TL\weather\main;
require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/weather_service/classes/CWidget.php");

class CWeatherOption
{
    const DATABASE = 'b_weather_widget_option';

    public function __construct($name)
    {
    }

    public static function GetOptionList($id = false)
    {
        global $DB;

        $strSql = "SELECT * FROM `" . self::DATABASE. "`";
        
        if ($id){
            $strSql .= " WHERE WIDGET_ID='" . $id."'";
        }

        $res = $DB->Query($strSql, false, "FILE: " . __FILE__ . "<br> LINE: " . __LINE__);

        $optionList = [];

        while ($option = $res->Fetch()) {
            $optionList[$option["WIDGET_ID"]][$option["NAME"]] = $option["VALUE"];
        }

        $objects = [];

        foreach ($optionList as $key => $value){
            $objects[] = new \CWidget($key, $value);
        }

        return $objects;
    }

    public static function hasResult($result)
    {
        if ($row = $result->fetch())
        {
            return true;
        }

        return false;
    }

    public static function DeleteOptionsByWidgetId($widgetId){
        global $DB;

        $strSqlDelete = sprintf("WIDGET_ID='%s'", $widgetId);

        $strSql = "DELETE FROM `" . self::DATABASE . "` WHERE " . $strSqlDelete;

        $res = $DB->Query($strSql, true);

        return true;
    }

    public static function InsertOption($widgetId, $name, $value){
        global $DB;

        $strSqlWhere = sprintf(
            "WIDGET_ID = '%s' AND NAME = '%s'", $widgetId, $name
        );

        $strSql = "SELECT * FROM `" . self::DATABASE . "` WHERE " . $strSqlWhere;

        $res = $DB->Query($strSql, true);

        $hasResult = self::hasResult($res);

        if ($hasResult)
        {
            $strSql = "UPDATE `" . self::DATABASE . "` SET VALUE='" . $value . "' WHERE " .$strSqlWhere;
            $DB->Query($strSql);
        }
        else
        {
            $strSqlInsert = sprintf("VALUES('%s', '%s', '%s')", $widgetId, $name, $value);
            $strSql = "INSERT INTO `" . self::DATABASE . "` (WIDGET_ID, NAME, VALUE) " . $strSqlInsert;
            $DB->Query($strSql);
        }
    }
}