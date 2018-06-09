<?php
namespace Jiny\Core\Boot;

class Bootstrap
{
    private $Application;

    private $_REQUEST_URI;
    private $_baseNamespace = "\Jiny\Core\\";
    private $_language, $_country;

    const LANGUAGE = TRUE;
    const COUNTRY = TRUE;

    private $_configSite;

    public function __construct($app)
    {
        // echo __CLASS__."객체를 생성합니다.<br>";
        $this->Application = $app;

        // 시작 url 위치를 지정합니다.
        // 지정한 값은 제외되고 url이 분석이 됩니다.
        $this->_configSite = $this->Application->Config->data("site");
        //echo "skip uriBase = ".$uriBase."<br>";

        // ReWrite로 전달된 부스트래핑 URL을 분석합니다. 
        $this->uri();
        $this->explodeURI();
        //->prepareURL();
    }

    /**
     * 요청한 URI값을 읽어 옵니다.
     * $base는 url 시작이 부분을 스킵처리 합니다.
     */
    private function uri()
    {
        // GET 처리
        // ? 를 기준으로 URI를 분리합니다.
        $REQUEST = explode('?', $_SERVER['REQUEST_URI']);

        if ( $this->_configSite['uriBase'] ) {
            $this->_REQUEST_URI = str_replace($this->_configSite['uriBase'], "", $REQUEST[0]);
        } else {
            $this->_REQUEST_URI = $REQUEST[0];
        }

        //echo "기본 URL 시작은 ". $this->_REQUEST_URI. " 입니다.<br>";
        // echo $base." 는 제외합니다.<br>";

        return $this;
    }

    /**
     * 입력한 URI를 구분하여, 배열화 합니다.
     */
    protected function explodeURI()
    {
        // echo "URL을 분석하여 explod합니다.<br>";
        $request = trim($this->_REQUEST_URI, '/');
        if (!empty($request)) {
            $uris = explode('/', $request);
        } else {
            $this->Application->_uri = NULL;
        }

        // URI 첫번째 인자가 언어-국가 설정
        if ($this->_configSite['uriLANGUAGE'] || $this->_configSite['uriCOUNTRY']) {
            
            $this->setParserLanguage($uris[0])->setParserCountry($uris[0]);

            for ($i=1, $j=0; $i<count($uris); $i++, $j++) {
                $this->Application->_uri[$j] = $uris[$i];
            }

        } else {
            $this->Application->_uri = $uris;
        }

        return $this;
    }

    /**
     * uri 첫번째 인자에서 언어 코드를 분리합니다.
     */
    private function setParserLanguage($code)
    {
        $lang = explode('-', $code);
        if (isset($lang[0])) {
            $this->_country = $this->isLanguage($lang[0]);
            //echo "언어설정=".$this->_country."를 설정합니다.<br>";         
        }
        return $this;
    }

    public function isLanguage($code)
    {
        if($code == "ko") return $code;
        else return NULL;
    }

    /**
     * uri 첫번째 인자에서 국가 코드를 분리합니다.
     */
    private function setParserCountry($code)
    {
        $lang = explode('-', $code);
        if (isset($lang[1])) {
            $this->_language = $this->isCountry($lang[1]);
            //echo "국가설정=".$this->_language."를 설정합니다.<br>";         
        }
        return $this;
    }

    public function isCountry($code)
    {
        if($code == "kr") return $code;
        else return NULL;
    }

    /**
     * uri 에서 컨트롤러 매소드를 분리합니다.
     * 분석한 URL은 _controller, _action, _param에 저장됩니다.
     */
    protected function prepareURL()
    {
        if (!empty($this->Application->_uri)) {
            $url = $this->Application->_uri;

            // 컨트롤러 선택합니다.
            $this->controllerURL('\Jiny\Core\IndexController');

            // 실행 매서드를 선택합니다.
            $this->methodURL('index');

            // 파라미터
            $this->parmURL();

        } else {
            // root 접속일 경우, URI가 비어있을 수 있습니다.
            // echo "URI가 비어 있습니다.<br>";
        }

        return $this;
    }

    /**
     * 컨트롤러와 매서드를 선택합니다.
     */
    private function controllerURL($default=NULL)
    {
        if (!empty($this->Application->_uri)) {
            
            if (isset($this->Application->_uri[0])) {
                $this->Application->_controller = ucwords($this->Application->_uri[0])."Controller";
            } else {
                $this->Application->_controller = $default;
            }

            //echo $this->Application->_controller." 컨트롤러를 선택합니다.<br>";
        }
    
        return $this;
    }

    /**
     * 매서드를 선택합니다.
     */
    private function methodURL($default=NULL)
    {
        if (!empty($this->Application->_uri)) {
            
            if (isset($this->Application->_uri[1])) {
                $this->Application->_action = $this->_uri[1];
            } else {
                $this->Application->_action = $default;
            }

            //echo $this->Application->_action." 매소드를 선택합니다.<br>";
        }
    
        return $this;
    }

    /**
     * 파라미터를 선택합니다.
     */
    private function parmURL()
    {
        $url = $this->Application->_uri;

        // 추가 $url 배열값이 없는 경우 비어있는 [] 배열을 저장
        unset($url[0],$url[1]);
        $this->Application->_prams = !empty($url) ? array_values($url): [];

        return $this;
    }


}