<?php
class CProvider{
    private $_name;
    private $_api_key;
    private $_app_key;

    public function __construct($name, $apiKey = null, $appKey = null, $activity = false)
    {
        $this->_name = $name;
        $this->_api_key = $apiKey;
        $this->_app_key = $appKey;
        $this->_activity = $activity;
    }

    public function getName(){
        return $this->_name;
    }

    public function setName($name){
        $this->_name =  $name;
    }

    public function getApiKey(){
        return $this->_api_key;
    }

    public function setApiKey($apiKey = null){
        $this->_api_key =  $apiKey;
    }

    public function getAppKey(){
        return $this->_app_key;
    }

    public function setAppKey($appKey = null){
        $this->_app_key =  $appKey;
    }

    public function getActivity(){
        return $this->_activity;
    }

    public function setActivity($activity){
        $this->_activity =  $activity;
    }

    public function toJson(){
        return [
          'name' => $this->_name,
          'api_key' => $this->_api_key,
          'app_key' => $this->_app_key,
          'activity' => $this->_activity,
          'valid' => true,
        ];
    }
}