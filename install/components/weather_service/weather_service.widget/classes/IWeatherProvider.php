<?php

namespace TL\weather;

interface IWeatherProvider
{
    public function __construct($initApiKey, $initAppKey);

    public function getWeatherIcon($code);

    public function getWeather($latitude, $longitude, $unit);
}