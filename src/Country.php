<?php
namespace Jiny\Core;

class Country
{
    public $_countryData;

    private $Application;

    public function __counstruct($app)
    {
        $this->Application = $app;

        echo __CLASS__." 를 생성합니다.<br>";

        $filename = "../data/country.json";
        if (\file_exists($filename)) {
        
            $str = file_get_contents($filename);           
            $this->_countryData = json_decode($str);  
        }
    }

    public function isCountry($code)
    {

    }

    public function Country()
    {

    }
}