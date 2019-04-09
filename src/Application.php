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

use \Jiny\Core\Route\Routers;


use \Jiny\Core\Http\Response;

class Application extends Core
{
    // protected $_control;
    public $_controller;
    public $_action = 'index';
    public $_prams = [];

    public $_viewFile;
    public $_data;

    /**
     * 인스턴스
     */
    public $Config;
    public $Registry;
    public $Request;

    public $Theme;
    public $Route;
    public $_routeAction;

    public $Packages;

    // 
    // public $_Country, $_Language;

    use AppRun;
 

    /**
     * 생성자 매직 매서드
     */
    public function __construct()
    {   
        // 설치된 페키지 목록을 저장합니다.
        // 오류 방지를 위하여 설치 사전에 체크
        $this->Packages = new Package ($this);
          
        // 인스턴스 레지스터
        $this->registry();
        
        // 환경설정 객체를 로드합니다. 
        if ($this->Config = Registry::get("CONFIG")) {
            $Config = $this->Config;
                        

        } else {
            echo "환경설정 파일을 읽어 올 수 없습니다.";
            exit;
        }


        // HTTP Request 인스턴스를 생성합니다.
        if ($this->Request = Registry::create(\Jiny\Core\Http\Request::class, "Request", $this)) {

            // 부트스트래핑
            $boot = new Bootstrap($this->Request);
            config_set("req", $boot->resource);

            config_set("req.uri", $this->Request->_uri);
            config_set("req.string", $this->Request->urlString());


            // 사용자 커스텀 설정 로드
            $customConf = "./app/conf/"."config.php";
            if(file_exists($customConf)){
                require $customConf;
            } else {
                $this->Config->autoSet();
            }
            $this->Config->loadFiles();
            


            // 부트스트래핑을 통하여 uri분석 처리가 되어 있어야 합니다.
            if (!empty($this->Request->_uri)) {           
                $this->_controller = $this->Request->getController('\Jiny\Core\IndexController');    
                $this->_action = $this->Request->getMethod('index');   
                $this->_prams = $this->Request->parm(); 
                
            } else {
                // root 접속일 경우, URI가 비어있을 수 있습니다.
            } 

        } else {
            echo "HTTP Request 요청을 처리할 수 없습니다.";
            exit;
        }


        // 라우트를 처리합니다.
        if ($this->Packages->isPackage("jiny/core")) {

            // 라우팅
            $Route = new \Jiny\Router\Routers;
            $info = $Route->routing();

            // 라우트에서 뷰파일을 설정한 경우 읽어옴.
            if($Route->_viewFile){
                $this->_viewFile = $Route->_viewFile;
            }

            // 라우트 메모리 해제
            unset($Route);
        } 


        // 브라우저로 반환될 response가 있을 경우
        if($response = $this->run($info)) {

            $res = new Response('Content', Response::HTTP_OK, array('content-type' => 'text/html'));         

            // 캐쉬방지 처리 해더 전송
            /*         
            if (isset($cache)) {
                if (!$cache) {
                    header('Cache-Control: no-cache, no-store, must-revalidate'); // HTTP 1.1.
                    header('Pragma: no-cache'); // HTTP 1.0.
                    header('Expires: 0'); // Proxies.
                }
            }
            */



            if (is_string($response)) {
                // Text 출력
                $res->setContent($response);
            }
            // 배열 출력 
            else if(is_array($response)) {
                //echo "Array 입니다.";
                $res->setContent(json_encode($response));
            } else {
                
            }
           
            $res->send();
            
        } else {
            echo "no response";
        }
 
        //
    }


    /**
     * 
     */
    private function registry()
    {
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
     * 설정된 뷰 파일을 반환합니다.
     */
    public function getViewFile()
    {
        return $this->_viewFile;
    }

    /**
     * 뷰를 설정합니다.
     */
    public function setViewFile($file)
    {
        $this->_viewFile = $file;
    }


    /**
     * 
     */
}