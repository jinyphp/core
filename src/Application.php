<?php
namespace Jiny\Core;

use \Jiny\Core\Registry\Registry;
use \Jiny\Core\Packages\Package;
use \Jiny\Core\Http\Bootstrap;
use \Jiny\Core\Route\Routers;

use \Jiny\Core\Http\Response;

class Application
{
    public $_uri = [];

    // protected $_control;
    public $_controller;
    public $_action = 'index';

    public $_viewFile;
    public $_data;


    public $_prams = [];

    // protected $_body;

    public $_routes = [];

    /**
     * 인스턴스
     */
    public $Config;
    public $Registry;
    public $Boot;

    public $Theme;
    public $Route;
    public $_routeAction;

    public $Packages;

    // 
    public $_Country, $_Language;

    use AppRun;
 
    // 생성자 매직 매서드
    public function __construct()
    {   
        \TimeLog::set(__CLASS__."가 생성이 되었습니다.");

        // 설치된 페키지 목록을 저장합니다.
        // 오류 방지를 위하여 설치 사전에 체크
        $this->Packages = new Package ($this);
          
        // 인스턴스 레지스터
        $this->registry();
        
        // 환경설정 객체를 로드합니다. 
        if ($this->Config = Registry::get("CONFIG")) {
            // 사용자 커스텀 설정 로드
            $customConf = "./app/conf/"."config.php";
            require $customConf;        
            $this->Config->loadFiles();

        } else {
            echo "환경설정 파일을 읽어 올 수 없습니다.";
            exit;
        }


        // HTTP Request 인스턴스를 생성합니다.
        if ($Request = Registry::create(\Jiny\Core\Http\Request::class, "Request", $this)) {

            // 부트스트래핑
            // $Boot = Registry::create(\Jiny\Core\Http\Bootstrap::class, "Boot", $Request);
            $this->Boot = new Bootstrap($Request);
            Registry::set("Boot", $this->Boot);

        } else {
            echo "HTTP Request 요청을 처리할 수 없습니다.";
            exit;
        }

        
        // 라우트를 처리합니다.
        if ($this->Packages->isPackage("jiny/core")) {
            $this->Route = Registry::create(\Jiny\Router\Routers::class, "Router", $this);
            // echo "라우트 매핑을 처리합니다.<br>";
            $response = $this->Route->routing();

        } else {
            if (!empty($this->Boot->_urlReals)) {   
                // 컨트롤러 선택합니다.
                $this->_controller = $this->Boot->setController('\Jiny\Core\IndexController');
    
                // 실행 매서드를 선택합니다.
                $this->_action = $this->Boot->setMethod('index');
    
                // 파라미터
                $this->_prams = $this->Boot->parmURL();
    
            } else {
                // root 접속일 경우, URI가 비어있을 수 있습니다.
            } 

            // 라우트가 설치되어 있지 않은 경우
            // 기본 처리기로 동작합니다.
            $response = $this->run();
        }


        if($response) {

            $res = new Response('Content', Response::HTTP_OK, array('content-type' => 'text/html'));
            //$res = new Response();            

            // 캐쉬방지 처리 해더 전송            
            if (isset($cache)) {
                if (!$cache) {
                    header('Cache-Control: no-cache, no-store, must-revalidate'); // HTTP 1.1.
                    header('Pragma: no-cache'); // HTTP 1.0.
                    header('Expires: 0'); // Proxies.
                }
            }

            if (is_string($response)) {
                // Text 출력
                $res->setContent($response);
            }
            // 배열 출력 
            else if(is_array($response)) {
                //echo "Array 입니다.";
                $res->setContent(json_encode($response));
            }
           
            $res->send();
            
        }
    

    
    }


    private function registry()
    {
        \TimeLog::set(__METHOD__);

        // 클래스 인스턴스 pool 초기화
        $init = [];
        if ($this->Packages->isPackage("jiny/config")) {
            $init['CONFIG'] = \Jiny\Config\Config::class;
        } else {
            echo "jiny/config 패키지가 설치되어 있지 않습니다.";
            exit;
        }

        if ($this->Packages->isPackage("jiny/frontmatter")) {
            $init['FrontMatter'] = \Jiny\Frontmatter\FrontMatter::class;
        } else {
            echo "jiny/frontmatter 패키지가 설치되어 있지 않습니다.";
            exit;
        }

        $this->Registry = Registry::init($init);
        unset($init);
        
        // 레지스터에 Application을 등록합니다.
        $this->Registry->setInstance("App", $this);

        $this->Registry->setInstance("Packages", $this->Packages);
        
        return $this;
    }

    /**
     * 
     */
}