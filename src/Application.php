<?php
namespace Jiny\Core;

use \Jiny\Core\Boot\Bootstrap;
use \Jiny\Core\Route\Route;

class Application
{
    public $_uri = [];
    // protected $_control;
    public $_controller;
    public $_action = 'index';

    public $_prams = [];

    // protected $_body;

    public $_routes = [];

    public $Config;
    public $Registry;
    public $boot;

    public $Theme;

    // 생성자 매직 매서드
    public function __construct($Registry)
    {   
        // 레지스터를 등록
        $this->Registry = $Registry;

        // 환경설정 객체를 로드합니다.    
        $this->Config = $this->Registry->get("CONFIG");        

        // 부트스트래핑
        $boot = new Bootstrap($this);   
        
        // 라우트를 처리합니다.
        new Route($this);     
        
        
        // 테마 클래스를 생성합니다.
        // Registry pool에 등록합니다
        $this->Theme = $this->Registry->createInstance("\Jiny\Theme\Theme","Theme", $this);


        if (!empty($this->_controller)) {
            // 컨트롤러 호출
            $this->controller();

        } else {
            // URI가 비어 있는 경우 동작.
            // echo "컨트롤러가 설정되어 있지 않습니다.<br>";
            $this->pageController();       
        }

    }

    /**
     * root 접속시 초기페이지
     */
    public function index()
    {
        $this->_controller = new \Jiny\Core\IndexController;
        $this->_action = "index";
        call_user_func_array([$this->_controller, $this->_action], $this->_prams);
    }

    /**
     * 컨트롤러를 체크, 호출합니다.
     */
    public function controller()
    {
        // 컨트롤러 클래스 파일이 존재여부를 확인후에 처리함
        // CONTROLLERS
        $filename = "../App/Controllers/".$this->_controller. ".php";
        //echo "컨트롤러 = ".$filename. "를 로드합니다.<br>";
        if (file_exists($filename)) {
            
            // 인스턴스 생성, 재저장 합니다.
            //echo "컨트롤러 인스턴스를 생성합니다.<br>";            
            $this->_controller = $this->instanceFactory("\App\Controllers\\".$this->_controller);

            $this->method();                       

        } else {
            //echo "컨트롤러 파일이 존재하지 않습니다.<br>";
            // echo "기본 컨트롤러로 대체하여 실행이 됩니다.<br>";
            $this->pageController();                             
        }
    }

    public function method()
    {
        //echo $this->_action." 액션 매소드를 호출합니다.<br>";
        if (method_exists($this->_controller, $this->_action)) {
            //echo "메서드 호출";

            // 콜백함수로 클래스의 메서드를 호출합니다.
            call_user_func_array([$this->_controller, $this->_action], $this->_prams);
        } else {
           // echo "컨트롤러에 메서드가 존재하지 않습니다.";
        } 

        return $this;
    }

    /**
     * 기본 컨트롤러
     * pageController
     */
    private function pageController()
    {
        $this->_controller = $this->instanceFactory("\Jiny\Pages\Page");
        $this->_body = $this->_controller->index();  
    }

    public function instanceFactory($name)
    {
        return new $name($this);
    }
    
}