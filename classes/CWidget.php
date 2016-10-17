<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/weather_service/classes/CProvider.php");

class CWidget
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
    private $_major_text_color;
    private $_extra_text_color;
    private $_update_interval;
    private $_provider_info;
    private $_measurement_system;
    private $_providers_list;
    private $_active_provider_ref;

    public function toJson()
    {
        return [
            'name' => $this->_name,
            'latitude' => floatval($this->_latitude),
            'longitude' => floatval($this->_longitude),
            'weather_provider' => $this->_weather_provider,
            'widget_title' => $this->_title,
            'wunderground_api_key' => $this->_wunderground_api_key,
            'forecastio_api_key' => $this->_forecastio_api_key,
            'weathertrigger_api_key' => $this->_weathertrigger_api_key,
            'weathertrigger_app_key' => $this->_weathertrigger_app_key,
            'apixu_api_key' => $this->_apixu_api_key,
            'openweather_api_key' => $this->_openweather_api_key,
            'background_color' => $this->_background_color,
            'major_text_color' => $this->_major_text_color,
            'extra_text_color' => $this->_extra_text_color,
            'update_interval' => $this->_update_interval,
            'show_provider_info' => $this->_provider_info,
            'measurement_system' => $this->_measurement_system,
            'providers_list' => $this->getJsonProviderList(),
            'active_provider_ref' => $this->_active_provider_ref
        ];
    }

    public function __construct($name, $properties)
    {
        $updateInterval = $properties[UPDATE_INTERVAL_SELECTOR] != '' ? $properties[UPDATE_INTERVAL_SELECTOR] : DEFAULT_UPDATE_INTERVAL;
        $extraTextColor = $properties[EXTRA_TEXT_COLOR_SELECTOR] != '' ? $properties[EXTRA_TEXT_COLOR_SELECTOR] : DEFAULT_FONT_COLOR;
        $majorTextColor = $properties[MAJOR_TEXT_COLOR_SELECTOR] != '' ? $properties[MAJOR_TEXT_COLOR_SELECTOR] : DEFAULT_FONT_COLOR;
        $backgroundColor = $properties[BACKGROUND_COLOR_SELECTOR] != '' ? $properties[BACKGROUND_COLOR_SELECTOR] : DEFAULT_BACKGROUND_COLOR;
        $weatherProvider = $properties[WEATHER_PROVIDER_SELECTOR] != '' ? $properties[WEATHER_PROVIDER_SELECTOR] : DEFAULT_PROVIDER;
        $measurementSystem = $properties[MEASUREMENT_SYSTEM_SELECTOR] != '' ? $properties[MEASUREMENT_SYSTEM_SELECTOR] : DEFAULT_MEASUREMENT_SYSTEM;

        $this->_name = $name;
        $this->_latitude = $properties[LATITUDE_SELECTOR];
        $this->_longitude = $properties[LONGITUDE_SELECTOR];
        $this->_weather_provider = $weatherProvider;
        $this->_title = $properties[WIDGET_TITLE_SELECTOR];
        $this->_wunderground_api_key = $properties[WUNDERGROUND_API_KEY_SELECTOR];
        $this->_forecastio_api_key = $properties[FORECASTIO_API_KEY_SELECTOR];
        $this->_weathertrigger_api_key = $properties[WEATHERTRIGGER_API_KEY_SELECTOR];
        $this->_weathertrigger_app_key = $properties[WEATHERTRIGGER_APP_KEY_SELECTOR];
        $this->_apixu_api_key = $properties[APIXU_API_KEY_SELECTOR];
        $this->_openweather_api_key = $properties[OPENWEATHER_API_KEY_SELECTOR];
        $this->_background_color = $backgroundColor;
        $this->_major_text_color = $majorTextColor;
        $this->_extra_text_color = $extraTextColor;
        $this->_update_interval = $updateInterval;
        $this->_provider_info = $properties[SHOW_PROVIDER_INFO_SELECTOR];
        $this->_measurement_system = $measurementSystem;

        $this->_providers_list[] = new CProvider(WUNDERGROUND, $this->_wunderground_api_key, null, ($weatherProvider == WUNDERGROUND));
        $this->_providers_list[] = new CProvider(FORECASTIO, $this->_forecastio_api_key, null, ($weatherProvider == FORECASTIO));
        $this->_providers_list[] = new CProvider(WEATHERTRIGGER, $this->_weathertrigger_api_key, $this->_weathertrigger_app_key, ($weatherProvider == WEATHERTRIGGER));
        $this->_providers_list[] = new CProvider(APIXU, $this->_apixu_api_key, null, ($weatherProvider == APIXU));
        $this->_providers_list[] = new CProvider(OPENWEATHER, $this->_openweather_api_key, null, ($weatherProvider == OPENWEATHER));
        $this->_providers_list[] = new CProvider(YAHOOWEATHER, null, null, ($weatherProvider == YAHOOWEATHER));

        $this->setActiveProviderRef();
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

    public function getMajorTextColor()
    {
        return $this->_major_text_color;
    }

    public function setMajorTextColor($majorTextColor)
    {
        $this->_major_text_color = $majorTextColor;
    }

    public function getExtraTextColor()
    {
        return $this->_extra_text_color;
    }

    public function setExtraTextColor($extraTextColor)
    {
        $this->_extra_text_color = $extraTextColor;
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

    public function getJsonProviderList(){
        $providersList = '';

        foreach ($this->_providers_list as $provider) {
            $providersList[] = $provider->toJson();
        }

        return $providersList;
    }

    public function getActiveProviderRef()
    {
        return $this->_active_provider_ref;
    }
}