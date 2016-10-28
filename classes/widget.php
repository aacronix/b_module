<?php
namespace TL\weather\main;
require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/weather_service/classes/option.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/weather_service/classes/CWidget.php");

class CWeatherWidget
{
    const DATABASE = 'b_weather_widget';

    public function __construct($name)
    {
    }

    public static function DeleteWidgetById($widgetId)
    {
        global $DB;

        $strSql = "DELETE FROM `" . self::DATABASE . "` WHERE WIDGET_ID='$widgetId'";

        $DB->StartTransaction();
        $result = $DB->Query($strSql, true);
        $response = true;

        if ($result){
            $result = CWeatherOption::DeleteOptionsByWidgetId($widgetId);

            if (!$result){
                $response = false;
                $DB->Rollback();
            }
            $DB->Commit();
        } else {
            $response = false;
            $DB->Rollback();
        }

        return $response;
    }

    public static function RenameWidget($widgetId, $newName)
    {
        global $DB;
        $strSql = "UPDATE `" . self::DATABASE . "` SET NAME='$newName' WHERE WIDGET_ID='$widgetId'";

        $res = $DB->Query($strSql, false, "FILE: " . __FILE__ . "<br> LINE: " . __LINE__);

        return $newName;
    }

    public static function UpdateWidgetTemplateName($widgetId, $templateName)
    {
        global $DB;
        $strSql = "UPDATE `" . self::DATABASE . "` SET TEMPLATE_NAME='$templateName' WHERE WIDGET_ID='$widgetId'";

        $res = $DB->Query($strSql, false, "FILE: " . __FILE__ . "<br> LINE: " . __LINE__);

        return $templateName;
    }

    public static function InsertNewWidget($name)
    {
        global $DB;
        $strSql = "INSERT INTO `" . self::DATABASE . "` (ACTIVE, NAME, SUPER) VALUES (1, '$name', false)";

        $res = $DB->Query($strSql, false, "FILE: " . __FILE__ . "<br> LINE: " . __LINE__);

        $lastId = $DB->LastID();

        $widgetId = "w_" . $lastId;

        $strSql = "UPDATE `" . self::DATABASE . "` SET WIDGET_ID='$widgetId' WHERE ID=$lastId";

        $res = $DB->Query($strSql, false, "FILE: " . __FILE__ . "<br> LINE: " . __LINE__);

        return $widgetId;
    }

    public static function SelectWeatherWidgetsList()
    {
        global $DB;

        $strSql = "SELECT * FROM b_weather_widget";

        $res = $DB->Query($strSql, false, "FILE: " . __FILE__ . "<br> LINE: " . __LINE__);

        return $res;
    }

    public static function GetWeatherWidgetByWidgetId($widgetId)
    {
        global $DB;

        $strSql = "SELECT * FROM b_weather_widget WHERE WIDGET_ID=" . "'$widgetId'";;

        $res = $DB->Query($strSql, false, "FILE: " . __FILE__ . "<br> LINE: " . __LINE__);

        $widget = NULL;

        while ($option = $res->Fetch()) {
            $widget = new \CWidget($widgetId, $option["ACTIVE"], $option["NAME"], $option["SUPER"], ($option["TEMPLATE_NAME"] == '' ? 'elementary' : $option["TEMPLATE_NAME"]));
        }

        return $widget;
    }
}