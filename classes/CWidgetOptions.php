<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/weather_service/classes/CProvider.php");

class CWidgetOptions
{
    private $_name;
    private $_latitude;
    private $_longitude;
    private $_weather_provider;
    private $_title;
    private $_wunderground_api_key;
    private $_forecastio_api_key;
    private $_weathertrigger_api_key;
    private $_weathertrigger_app_key;
    private $_apixu_api_key;
    private $_openweather_api_key;
    private $_background_color;
    private $_border_color;
    private $_major_text_color;
    private $_extra_text_color;
    private $_major_text_size;
    private $_extra_text_size;
    private $_temperature_text_size;
    private $_temperature_icon_size;
    private $_weather_icon_size;
    private $_update_interval;
    private $_provider_info;
    private $_measurement_system;
    private $_providers_list;

    public function toJson()
    {
        return [
            'information' => [
                'name' => $this->_name,
                'latitude' => floatval($this->_latitude),
                'longitude' => floatval($this->_longitude),
                'weather_provider' => $this->_weather_provider,
                'widget_title' => $this->_title,
                'background_color' => $this->_background_color,
                'border_color' => $this->_border_color,
                'major_text_color' => $this->_major_text_color,
                'major_text_size' => $this->_major_text_size,
                'extra_text_color' => $this->_extra_text_color,
                'extra_text_size' => $this->_extra_text_size,
                'temperature_text_size' => $this->_temperature_text_size,
                'temperature_icon_size' => $this->_temperature_icon_size,
                'weather_icon_size' => $this->_weather_icon_size,
                'update_interval' => $this->_update_interval,
                'show_provider_info' => $this->_provider_info,
                'measurement_system' => $this->_measurement_system,
            ],
            'providers_list' => $this->getJsonProviderList(),
        ];
    }

    public function __construct($name, $properties)
    {
        $updateInterval = $properties[UPDATE_INTERVAL_SELECTOR] != '' ? $properties[UPDATE_INTERVAL_SELECTOR] : DEFAULT_UPDATE_INTERVAL;
        $extraTextColor = $properties[EXTRA_TEXT_COLOR_SELECTOR] != '' ? $properties[EXTRA_TEXT_COLOR_SELECTOR] : DEFAULT_FONT_COLOR;
        $extraTextSize = $properties[EXTRA_TEXT_SIZE_SELECTOR] != '' ? $properties[EXTRA_TEXT_SIZE_SELECTOR] : DEFAULT_TEXT_SIZE;
        $temperatureTextSize = $properties[TEMPERATURE_TEXT_SIZE_SELECTOR] != '' ? $properties[TEMPERATURE_TEXT_SIZE_SELECTOR] : DEFAULT_TEXT_SIZE;
        $temperatureIconSize = $properties[TEMPERATURE_ICON_SIZE_SELECTOR] != '' ? $properties[TEMPERATURE_ICON_SIZE_SELECTOR] : DEFAULT_TEXT_SIZE;
        $weatherIconSize = $properties[WEATHER_ICON_SIZE_SELECTOR] != '' ? $properties[WEATHER_ICON_SIZE_SELECTOR] : DEFAULT_TEXT_SIZE;
        $majorTextColor = $properties[MAJOR_TEXT_COLOR_SELECTOR] != '' ? $properties[MAJOR_TEXT_COLOR_SELECTOR] : DEFAULT_FONT_COLOR;
        $majorTextSize = $properties[MAJOR_TEXT_SIZE_SELECTOR] != '' ? $properties[MAJOR_TEXT_SIZE_SELECTOR] : DEFAULT_TEXT_SIZE;
        $backgroundColor = $properties[BACKGROUND_COLOR_SELECTOR] != '' ? $properties[BACKGROUND_COLOR_SELECTOR] : DEFAULT_BACKGROUND_COLOR;
        $borderColor = $properties[BORDER_COLOR_SELECTOR] != '' ? $properties[BORDER_COLOR_SELECTOR] : DEFAULT_BORDER_COLOR;
        $weatherProvider = $properties[WEATHER_PROVIDER_SELECTOR] != '' ? $properties[WEATHER_PROVIDER_SELECTOR] : DEFAULT_PROVIDER;
        $measurementSystem = $properties[MEASUREMENT_SYSTEM_SELECTOR] != '' ? $properties[MEASUREMENT_SYSTEM_SELECTOR] : DEFAULT_MEASUREMENT_SYSTEM;
        $showProviderInfo = $properties[SHOW_PROVIDER_INFO_SELECTOR] != '' ? $properties[SHOW_PROVIDER_INFO_SELECTOR] : DEFAULT_SHOW_PROVIDER_INFO;
        $widgetTitle = $properties[WIDGET_TITLE_SELECTOR] != '' ? $properties[WIDGET_TITLE_SELECTOR] : DEFAULT_WIDGET_TITLE;

        $this->_name = $name;
        $this->_latitude = $properties[LATITUDE_SELECTOR];
        $this->_longitude = $properties[LONGITUDE_SELECTOR];
        $this->_weather_provider = $weatherProvider;
        $this->_title = $widgetTitle;
        $this->_wunderground_api_key = $properties[WUNDERGROUND_API_KEY_SELECTOR] == null ? '' : $properties[WUNDERGROUND_API_KEY_SELECTOR];
        $this->_forecastio_api_key = $properties[FORECASTIO_API_KEY_SELECTOR] == null ? '' : $properties[FORECASTIO_API_KEY_SELECTOR];
        $this->_weathertrigger_api_key = $properties[WEATHERTRIGGER_API_KEY_SELECTOR] == null ? '' : $properties[WEATHERTRIGGER_API_KEY_SELECTOR];
        $this->_weathertrigger_app_key = $properties[WEATHERTRIGGER_APP_KEY_SELECTOR] == null ? '' : $properties[WEATHERTRIGGER_APP_KEY_SELECTOR];
        $this->_apixu_api_key = $properties[APIXU_API_KEY_SELECTOR] == null ? '' : $properties[APIXU_API_KEY_SELECTOR];
        $this->_openweather_api_key = $properties[OPENWEATHER_API_KEY_SELECTOR] == null ? '' : $properties[OPENWEATHER_API_KEY_SELECTOR];
        $this->_background_color = $backgroundColor;
        $this->_border_color = $borderColor;
        $this->_major_text_color = $majorTextColor;
        $this->_major_text_size = $majorTextSize;
        $this->_extra_text_color = $extraTextColor;
        $this->_extra_text_size = $extraTextSize;
        $this->_temperature_text_size = $temperatureTextSize;
        $this->_temperature_icon_size = $temperatureIconSize;
        $this->_weather_icon_size = $weatherIconSize;
        $this->_update_interval = $updateInterval;
        $this->_provider_info = $showProviderInfo;
        $this->_measurement_system = $measurementSystem;

        $this->_providers_list[] = new CProvider(WUNDERGROUND, $this->_wunderground_api_key, '', ($weatherProvider == WUNDERGROUND));
        $this->_providers_list[] = new CProvider(FORECASTIO, $this->_forecastio_api_key, '', ($weatherProvider == FORECASTIO));
        $this->_providers_list[] = new CProvider(WEATHERTRIGGER, $this->_weathertrigger_api_key, $this->_weathertrigger_app_key, ($weatherProvider == WEATHERTRIGGER));
        $this->_providers_list[] = new CProvider(APIXU, $this->_apixu_api_key, '', ($weatherProvider == APIXU));
        $this->_providers_list[] = new CProvider(OPENWEATHER, $this->_openweather_api_key, '', ($weatherProvider == OPENWEATHER));
        $this->_providers_list[] = new CProvider(YAHOOWEATHER, '', '', ($weatherProvider == YAHOOWEATHER));
    }

    public function getName()
    {
        return $this->_name;
    }

    public function setName($name)
    {
        $this->_name = $name;
    }

    public function getLatitude()
    {
        return $this->_latitude;
    }

    public function setLatitude($latitude)
    {
        $this->_latitude = $latitude;
    }

    public function getLongitude()
    {
        return $this->_longitude;
    }

    public function setLongitude($longitude)
    {
        $this->_longitude = $longitude;
    }

    public function getWeatherProvider()
    {
        return $this->_weather_provider;
    }

    public function setWeatherProvider($weatherProvider)
    {
        $this->_weather_provider = $weatherProvider;
    }

    public function getTitle()
    {
        return $this->_title;
    }

    public function setTitle($title)
    {
        $this->_title = $title;
    }

    public function getWundergroundApiKey()
    {
        return $this->_wunderground_api_key;
    }

    public function setWundergroundApiKey($wundergroundApiKey)
    {
        $this->_wunderground_api_key = $wundergroundApiKey;
    }

    public function getForecastioApiKey()
    {
        return $this->_forecastio_api_key;
    }

    public function setForecastioApiKey($forecastioApiKey)
    {
        $this->_forecastio_api_key = $forecastioApiKey;
    }

    public function getWeathertriggerApiKey()
    {
        return $this->_weathertrigger_api_key;
    }

    public function setWeathertriggerApiKey($weathertriggerApiKey)
    {
        $this->_weathertrigger_api_key = $weathertriggerApiKey;
    }

    public function getWeathertriggerAppKey()
    {
        return $this->_weathertrigger_app_key;
    }

    public function setWeathertriggerAppKey($weathertriggerAppKey)
    {
        $this->_weathertrigger_app_key = $weathertriggerAppKey;
    }

    public function getApixuApiKey()
    {
        return $this->_apixu_api_key;
    }

    public function setApixuApiKey($apixuApiKey)
    {
        $this->_apixu_api_key = $apixuApiKey;
    }

    public function getOpenweatherApiKey()
    {
        return $this->_openweather_api_key;
    }

    public function setOpenweatherApiKey($openweatherApiKey)
    {
        $this->_openweather_api_key = $openweatherApiKey;
    }

    public function getBackgroundColor()
    {
        return $this->_background_color;
    }

    public function setBackgroundColor($backgroundColor)
    {
        $this->_background_color = $backgroundColor;
    }

    public function getBorderColor()
    {
        return $this->_border_color;
    }

    public function setBorderColor($borderColor)
    {
        $this->_border_color = $borderColor;
    }

    public function getMajorTextColor()
    {
        return $this->_major_text_color;
    }

    public function setMajorTextColor($majorTextColor)
    {
        $this->_major_text_color = $majorTextColor;
    }

    public function getMajorTextSize()
    {
        return $this->_major_text_size;
    }

    public function setMajorTextSize($majorTextSize)
    {
        $this->_major_text_size = $majorTextSize;
    }

    public function getExtraTextColor()
    {
        return $this->_extra_text_color;
    }

    public function setExtraTextColor($extraTextColor)
    {
        $this->_extra_text_color = $extraTextColor;
    }

    public function getExtraTextSize()
    {
        return $this->_extra_text_size;
    }

    public function setExtraTextSize($extraTextSize)
    {
        $this->_extra_text_size = $extraTextSize;
    }

    public function getTemperatureTextSize()
    {
        return $this->_temperature_text_size;
    }

    public function setTemperatureTextSize($temperatureTextSize)
    {
        $this->_temperature_text_size = $temperatureTextSize;
    }

    public function getTemperatureIconSize()
    {
        return $this->_temperature_icon_size;
    }

    public function setTemperatureIconSize($temperatureIconSize)
    {
        $this->_temperature_icon_size = $temperatureIconSize;
    }

    public function getWeatherIconSize()
    {
        return $this->_weather_icon_size;
    }

    public function setWeatherIconSize($weatherIconSize)
    {
        $this->_weather_icon_size = $weatherIconSize;
    }

    public function getUpdateInterval()
    {
        return $this->_update_interval;
    }

    public function setUpdateInterval($updateInterval)
    {
        $this->_update_interval = $updateInterval;
    }

    public function getProviderInfo()
    {
        return $this->_provider_info;
    }

    public function setProviderInfo($providerInfo)
    {
        $this->_provider_info = $providerInfo;
    }

    public function getMeasurementSystem()
    {
        return $this->_measurement_system;
    }

    public function setMeasurementSystem($measurementSystem)
    {
        $this->_measurement_system = $measurementSystem;
    }

    public function getProvidersList()
    {
        return $this->_providers_list;
    }

    public function setProvidersList($providersList)
    {
        $this->_providers_list = $providersList;
    }

    private function setActiveProviderRef()
    {
        foreach ($this->_providers_list as $provider) {
            if ($provider->getActivity()) {
                $this->_active_provider_ref = &$provider;
                break;
            }
        }
    }

    public function getJsonProviderList()
    {
        $providersList = '';

        foreach ($this->_providers_list as $provider) {
            $providersList[] = $provider->toJson();
        }

        return $providersList;
    }
}