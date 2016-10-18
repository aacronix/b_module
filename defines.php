<?
define('WEATHER_SERVICE_MODULE_ID', 'weather_service');
define('CONSTANTS_ROOT', $_SERVER['DOCUMENT_ROOT'] . "/bitrix/components/weather_service/weather_service.widget/utils");
define('FUNCTIONS_ROOT', $_SERVER['DOCUMENT_ROOT'] . "/bitrix/components/weather_service/weather_service.widget/functions");
define('ERROR_PAGE_USER', $_SERVER['DOCUMENT_ROOT'] . "/bitrix/components/weather_service/weather_service.widget/parts/error-user.php");
define('ERROR_PAGE_ADMIN', $_SERVER['DOCUMENT_ROOT'] . "/bitrix/components/weather_service/weather_service.widget/parts/error-admin.php");
define('HEADER', $_SERVER['DOCUMENT_ROOT'] . "/bitrix/components/weather_service/weather_service.widget/parts/header.php");
define('CLASSES_ROOT', $_SERVER['DOCUMENT_ROOT'] . "/bitrix/components/weather_service/weather_service.widget/classes");
define('DEFAULT_WEATHER_PROVIDER', 'yahooweather');
define('WEATHER_TOOLS_RELATIVE', "/bitrix/tools/" . WEATHER_SERVICE_MODULE_ID);
define('WEATHER_TOOLS', $_SERVER['HTTP_HOST'] . WEATHER_TOOLS_RELATIVE);
define('COPY_TAB', "copy_tab");
define('LOG', $_SERVER['DOCUMENT_ROOT'] . "/bitrix/log/");

define('WUNDERGROUND', 'wunderground');
define('FORECASTIO', 'forecastio');
define('WEATHERTRIGGER', 'weathertrigger');
define('APIXU', 'apixu');
define('OPENWEATHER', 'openweather');
define('YAHOOWEATHER', 'yahooweather');
define('DEFAULT_PROVIDER', YAHOOWEATHER);
define('DEFAULT_MEASUREMENT_SYSTEM', 'metrical');
define('DEFAULT_SHOW_PROVIDER_INFO', false);
define('DEFAULT_WIDGET_TITLE', 'default');

define('DEFAULT_UPDATE_INTERVAL', 120);
define('DEFAULT_BACKGROUND_COLOR', 'rgba(0, 0, 0, 1)');
define('DEFAULT_FONT_COLOR', '#ffffff');
define('DEFAULT_TAB', "w_0");

define("NAME_SELECTOR", "name");
define("LATITUDE_SELECTOR", "latitude");
define("LONGITUDE_SELECTOR", "longitude");
define("WEATHER_PROVIDER_SELECTOR", "weather_provider");
define("WIDGET_TITLE_SELECTOR", "widget_title");
define("WUNDERGROUND_API_KEY_SELECTOR", "wunderground_api_key");
define("FORECASTIO_API_KEY_SELECTOR", "forecastio_api_key");
define("WEATHERTRIGGER_API_KEY_SELECTOR", "weathertrigger_api_key");
define("WEATHERTRIGGER_APP_KEY_SELECTOR", "weathertrigger_app_key");
define("APIXU_API_KEY_SELECTOR", "apixu_api_key");
define("OPENWEATHER_API_KEY_SELECTOR", "openweather_api_key");
define("BACKGROUND_COLOR_SELECTOR", "background_color");
define("MAJOR_TEXT_COLOR_SELECTOR", "major_text_color");
define("EXTRA_TEXT_COLOR_SELECTOR", "extra_text_color");
define("UPDATE_INTERVAL_SELECTOR", "update_interval");
define("SHOW_PROVIDER_INFO_SELECTOR", "show_provider_info");
define("MEASUREMENT_SYSTEM_SELECTOR", "measurement_system");
