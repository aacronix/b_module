<?php
namespace TL\weather\main;
require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/weather_service/classes/option.php");

class CWeatherWidget
{
    const DATABASE = 'b_weather_widget';

    public function __construct($name)
    {
    }

    public static function DeleteWidgetByWidgetId($widgetId)
    {
        global $DB;

        $strSql = "DELETE FROM `" . self::DATABASE . "` WHERE WIDGET_ID='$widgetId'";

        $res = $DB->Query($strSql, false, "FILE: " . __FILE__ . "<br> LINE: " . __LINE__);

        CWeatherOption::DeleteOptionsByWidgetId($widgetId);

        return $res;
    }

    public static function RenameWidget($widgetId, $newName)
    {
        global $DB;
        $strSql = "UPDATE `" . self::DATABASE . "` SET NAME='$newName' WHERE WIDGET_ID='$widgetId'";

        $res = $DB->Query($strSql, false, "FILE: " . __FILE__ . "<br> LINE: " . __LINE__);

        return $newName;
    }

    public static function InsertNewWidget($name)
    {
        global $DB;
        $strSql = "INSERT INTO `" . self::DATABASE . "` (ACTIVE, NAME, SUPER) VALUES (1, '$name', 0)";

        $res = $DB->Query($strSql, false, "FILE: " . __FILE__ . "<br> LINE: " . __LINE__);

        $lastId = $DB->LastID();

        $widgetId = "w_" . $lastId;

        $strSql = "UPDATE `" . self::DATABASE . "` SET WIDGET_ID='$widgetId' WHERE ID=$lastId";

        $res = $DB->Query($strSql, false, "FILE: " . __FILE__ . "<br> LINE: " . __LINE__);

        return $widgetId;
    }

    public static function SelectWeatherWidgetsList(&$by, &$order, $arFilter = array())
    {
        global $DB;

        $strSql = "SELECT * FROM b_weather_widget";

        $res = $DB->Query($strSql, false, "FILE: " . __FILE__ . "<br> LINE: " . __LINE__);

        return $res;
    }
}