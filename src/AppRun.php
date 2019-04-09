<?php
/*
 * This file is part of the jinyPHP package.
 *
 * (c) hojinlee <infohojin@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Jiny\Core;

use \Jiny\Core\Registry\Registry;
use \Jiny\Core\Packages\Package;
use \Jiny\Core\Http\Bootstrap;
use \Jiny\Core\Route\Router;
use \Jiny\Router\Dispatcher;

trait AppRun
{
    /**
     * 실행합니다.
     * 컨트롤러, 매소드를 호출합니다.
     */
    public function run($route=[])
    {

      
        if (isset($route[0])) {
            switch ($route[0]) {
                case Dispatcher::FOUND:
                    // 라우터 처리동작
                    $handler = $route[1];
                    $vars = $route[2];
    
                    // 익명함수 호출
                    if(is_callable($handler)){
                        return $handler($vars);
                    } 
                    // 문자열
                    else if(is_string($vars)){
                        return $handler;
                    }
    
                    break;
    
                case Dispatcher::NOT_FOUND:
                    // ... 404 Not Found
                    //echo "route ... 404 Not Found<br><br>";
                    break;
    
                case Dispatcher::METHOD_NOT_ALLOWED:
                    $allowedMethods = $route[1];
                    // ... 405 Method Not Allowed
                    //echo "route ... 405 Method Not Allowed<br><br>";
                    break;
            }
        }
      
        // 컨트롤러 호출
        // 컨트롤러가 없는 경우 기본 컨트롤러 동작      
        if (!empty($this->_controller)) {
            return $this->controller();
        } else {
            //output("컨트롤러가 비여 있습니다.\n");
            return $this->default();       
        } 

    }


    /**
     * 컨트롤러를 호출합니다.
     */
    public function controller()
    {
        // 컨트롤러 클래스 파일이 존재여부를 확인후에 처리함
        $controllerPath = DS."App".DS."Controllers".DS;
        $extention = ".php";
        $_ = "..".DS."..".DS."..".DS."..";
        $filename = __DIR__.DS.$_.$controllerPath.$this->_controller.$extention;
        // echo $filename;
 
        if (file_exists($filename)) {           
            // 인스턴스 생성, 재저장 합니다.
            $name = "\App\Controllers\\".$this->_controller;
            $this->_controller = new $name ($this);
            
            Registry::set("controller", $this->_controller);
            
            // 메서드 실행 반환
            return $this->method();

        } else {
            // echo "$filename 컨트롤러 파일이 존재하지 않습니다.<br>";
            return $this->default();                             
        }
    }


    /**
     * 매서드를 호출합니다.
     * 콜백함수을 이용하여 호출.
     */
    public function method()
    {
        if (method_exists($this->_controller, $this->_action)) {
            return call_user_func_array(
                [
                    $this->_controller, 
                    $this->_action
                ], 
                $this->_prams);
             
            
        } else {
            if (method_exists($this->_controller, "__invoke")) {
                // __invoke() 함수를 호출합니다.
                return call_user_func_array(
                    $this->_controller, 
                    $this->_prams);
            } else {
                echo "컨트롤러에 메서드가 존재하지 않습니다. ";
                exit;
            }
           
        } 


    }

    
    /**
     * 기본 컨트롤러
     * pageController
     */
    private function default()
    {
        $this->_controller = new \Jiny\Pages\PageController($this);
        
        Registry::set("controller", $this->_controller);
        return $this->_controller->index();  
    }

    /**
     * 
     */
}