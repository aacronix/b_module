<?php
class CWidget{
    private $_widget_id;
    private $_active;
    private $_name;
    private $_super;

    public function __construct($widgetId, $activity = true, $name, $super = false)
    {
        $this->_widget_id = $widgetId;
        $this->_active= $activity;
        $this->_name= $name;
        $this->_super = $super;
    }

    public function getWidgetId(){
        return $this->_widget_id;
    }

    public function setWidgetId($widgetId){
        $this->_name =  $widgetId;
    }

    public function getActive(){
        return $this->_active;
    }

    public function setActive($activity = true){
        $this->_active =  $activity;
    }

    public function getName(){
        return $this->_name;
    }

    public function setName($name){
        $this->_name =  $name;
    }

    public function getSuper(){
        return $this->_super;
    }

    public function setSuper($super = false){
        $this->_super =  $super;
    }

    public function toJson(){
        return [
          'widget_id' => $this->_widget_id,
          'active' => $this->_active,
          'name' => $this->_name,
          'super' => $this->_super
        ];
    }
}