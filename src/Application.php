<?php
namespace Jiny\Core;

use \Jiny\Core\Registry\Registry;
use \Jiny\Core\Packages\Package;
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


    /**
     * 인스턴스
     */
    public $Config;
    public $Registry;
    public $boot;

    public $Theme;
    public $Packages;

    public $_Country, $_Language;

    // 생성자 매직 매서드
    public function __construct()
    {   
        \TimeLog::set(__CLASS__."가 생성이 되었습니다.");

        // 설치된 페키지 목록을 저장합니다.
        // 오류 방지를 위하여 설치 사전에 체크
        $this->Packages = new Package ($this);
          
        // 인스턴스 레지스터
        // 클래스 인스턴스 pool 초기화
        $this->registerInit();
        
        // 레지스터에 Application을 등록합니다.
        $this->Registry->setInstance("App",$this);       
        $this->Registry->setInstance("Packages", $this->Packages);
        
        // 환경설정 객체를 로드합니다. 
        $this->configInit();        
        
        // 부트스트래핑
        $this->boot = new Bootstrap($this);
        $this->boot->parser();
     
        // 라우트를 처리합니다.
        new Route($this);     
        
        // 테마 클래스를 생성합니다.
        // Registry pool에 등록합니다
        if ($this->Packages->isPackage("jiny/theme")) {
            $this->Registry->createInstance("\Jiny\Theme\Theme","Theme", $this);
            $this->Theme = $this->Registry->get("Theme");
        }

        if (!empty($this->_controller)) {
            // 컨트롤러 호출
            $this->controller();

        } else {
            // URI가 비어 있는 경우 동작.
            // echo "컨트롤러가 설정되어 있지 않습니다.<br>";
            $this->default();       
        }
    }

    private function registerInit()
    {
        \TimeLog::set(__METHOD__);
        
        $init = [];
        if ($this->Packages->isPackage("jiny/config")) {
            $init['CONFIG'] = \Jiny\Config\Config::class;
        }

        if ($this->Packages->isPackage("jiny/frontmatter")) {
            $init['FrontMatter'] = \Jiny\Frontmatter\FrontMatter::class;
        } 

        $this->Registry = Registry::init($init);
        unset($init);
    }

    private function configInit()
    {
        // \TimeLog::set(__METHOD__);
        $this->Config = $this->Registry->get("CONFIG");

        $this->Config->autoUpFiles();
        $this->Config->parser();
        
        // \Jiny\Core\Registry::get("CONFIG")->setLoad("site.ini");
        // \Jiny\Core\Registry::get("CONFIG")->setLoad("conf.php");
        //echo "<hr>";
    }

    /**
     * 컨트롤러를 호출합니다.
     */
    public function controller()
    {
        // \TimeLog::set(__METHOD__);
        // 컨트롤러 클래스 파일이 존재여부를 확인후에 처리함
        // CONTROLLERS
        $filename = ROOT."/App/Controllers/".$this->_controller. ".php";
        //echo "컨트롤러 = ".$filename. "를 로드합니다.<br>";
        if (file_exists($filename)) {
            
            // 인스턴스 생성, 재저장 합니다.
            //echo "컨트롤러 인스턴스를 생성합니다.<br>";            
            $this->_controller = $this->instanceFactory("\App\Controllers\\".$this->_controller);

            $this->method();                       

        } else {
            //echo "컨트롤러 파일이 존재하지 않습니다.<br>";
            // echo "기본 컨트롤러로 대체하여 실행이 됩니다.<br>";
            $this->default();                             
        }
    }

    /**
     * 매서드를 호출합니다.
     */
    public function method()
    {
        // \TimeLog::set(__METHOD__);
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
    private function default()
    {
        // \TimeLog::set(__METHOD__);
        $this->_controller = $this->instanceFactory("\Jiny\Pages\Page");
        $this->_body = $this->_controller->index();  
    }

    public function instanceFactory($name)
    {
        // \TimeLog::set(__METHOD__);
        return new $name($this);
    }


    
}