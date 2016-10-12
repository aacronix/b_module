DROP TABLE if exists b_weather_widget;
DROP TABLE if exists b_weather_widget_option;

DELETE FROM `b_option` WHERE MODULE_ID='weather_service';

DROP TRIGGER `delete_childs`;