<?php

namespace Jiny\Core\Route;

class Route extends RouteAbstract
{
    public function __construct($app)
    {
        // \TimeLog::set(__CLASS__."가 생성이 되었습니다.");
        $this->Application = $app;

        
    }

    public function route()
    {
        // \TimeLog::set(__METHOD__);
    }

    /*
    public function isRoute($route, $key)
    {
        if(is_array($route)){
            echo "Array OK!<br>";
            return $this->isRouteCheck($route, $key);
        } else {
            // overflow
            echo "Error, 입력 uri가 더 많습니다!<br>";

        }
    }

    public function isRouteNum($route, $key)
    {
        echo "Vlidation 입력값은 숫자이어야 합니다.<br>";
        var_dump($key);
        if (is_int($key)) {
            echo "$key = 정수 입니다.<br>";
        } else {
            echo "$key = 정수가 아닙니다.<br>";
        }
        return $route[':num'];
    }

    public function isRouteAny($route, $key)
    {
        echo "Vlidation 입력값은 Any이어야 합니다.<br>";
        var_dump($key);
        if (is_string($key)) {
            echo "$key = 문자 입니다.<br>";
        } else {
            echo "$key = 문자가 아닙니다.<br>";
        }
        return $route[':any'];
    }

    public function isRouteCheck($route, $key)
    {
        if ($route[$key]) {
            echo "url 키값이 있습니다.<br>";             
            return $route[$key];
        } else {
            echo "키값이 없는 경우는 서브를 조사함.<br>";
            if (\is_array($route)){
                foreach ($route as $name => $value) {
                    echo "name= ".$name." ";
                    if ($name == ":num") {
                        return $this->isRouteNum($route, $key);
                    }
                    if ($name == ":any") {
                        return $this->isRouteAny($route, $key);
                    }
                }

            } else {
                echo "오류, 배열이 아닙니다.";
            }           
        }
       
    }


    public function route1()
    {
        echo "<hr>";
        echo "Route를 체크합니다...............<br>";
        require "../App/Route/Router.php";        
        
        for ($i=0;$i<count($this->_uri);$i++){
            $key = $this->_uri[$i];
            echo $key." >> ";
            print_r($route);
            echo "<br>";
            $route = $this->isRoute($route, $key);
            echo "<hr>";
        }

        var_dump($route);
        if (\is_array($route)) {
            // underflow
            echo "입력한 url값이 모자랍니다.<br>";
        } else {
            echo "정상입니다.<br>";
            echo $route."<br>";
        }
        


        echo "<hr>";
    }
    */



}