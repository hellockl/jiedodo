<?php
class View{
    function __set($key, $value){
        $az = AZ_Controller::get_instance();
        $az->viewData[$key] = $value;
    }
}