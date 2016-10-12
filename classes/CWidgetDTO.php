<?php
class CWidgetDTO{
    public function __construct(CWidget $widget)
    {
        $this->_name = $widget->getName();
        $this->_latitude = $widget->getLatitude();
        $this->_longitude = $widget->getLongitude();
        $this->_weather_provider = $widget->getWeatherProvider();
        $this->_title = $widget->getTitle();
        $this->_wunderground_api_key = $widget->getWundergroundApiKey();
        $this->_forecastio_api_key = $widget->getForecastioApiKey();
        $this->_weathertrigger_api_key= $widget->getWeathertriggerApiKey();
        $this->_weathertrigger_app_key= $widget->getWeathertriggerAppKey();
        $this->_apixu_api_key = $widget->getApixuApiKey();
        $this->_openweather_api_key = $widget->getApixuApiKey();
        $this->_background_color = $widget->getBackgroundColor();
        $this->_major_text_color = $widget->getMajorTextColor();
        $this->_extra_text_color = $widget->getExtraTextColor();
        $this->_update_interval = $widget->getUpdateInterval();
        $this->_provider_info = $widget->getProviderInfo();
    }

    public function getName(){
        return $this->_name;
    }

    public function getLatitude(){
        return $this->_latitude;
    }

    public function getLongitude(){
        return $this->_longitude;
    }

    public function getWeatherProvider(){
        return $this->_weather_provider;
    }

    public function getTitle(){
        return $this->_title;
    }

    public function getWundergroundApiKey(){
        return $this->_wunderground_api_key;
    }

    public function getForecastioApiKey(){
        return $this->_forecastio_api_key;
    }

    public function getWeathertriggerApiKey(){
        return $this->_weathertrigger_api_key;
    }

    public function getWeathertriggerAppKey(){
        return $this->_weathertrigger_app_key;
    }

    public function getApixuApiKey(){
        return $this->_apixu_api_key;
    }

    public function getOpenWeatherApiKey(){
        return $this->_openweather_api_key;
    }

    public function getBackgroundColor(){
        return $this->_background_color;
    }

    public function getMajorTextColor(){
        return $this->_major_text_color;
    }

    public function getExtraTextColor(){
        return $this->_extra_text_color;
    }

    public function getUpdateInterval(){
        return $this->_update_interval;
    }

    public function getProviderInfo(){
        return $this->_provider_info;
    }
}