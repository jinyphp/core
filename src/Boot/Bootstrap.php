<?php
namespace Jiny\Core\Boot;

class Bootstrap
{
    // 인스턴스
    private $Application;
    private $Locale;

    // 변수
    private $_REQUEST_URI;
    private $_REQUEST;
    private $_baseNamespace = "\Jiny\Core\\";
    private $_language, $_country;

    const LANGUAGE = TRUE;
    const COUNTRY = TRUE;

    private $_configSite;

    // trait
    use URI;

    public function __construct($app)
    {
        // echo __CLASS__."객체를 생성합니다.<br>";
        $this->Application = $app;

        // 시작 url 위치를 지정합니다.
        // 지정한 값은 제외되고 url이 분석이 됩니다.
        $this->_configSite = $this->Application->Config->data("site");
        //echo "skip uriBase = ".$uriBase."<br>";

        $this->Locale = new \Jiny\Locale\Locale($app);

        // ReWrite로 전달된 부스트래핑 URL을 분석합니다. 
        // REQUEST 값을 읽어 옵니다.
        $REQUEST = $this->getREQUEST();

        // baseurl을 제외한 처리 URL을 반환합니다.
        $baseurl = $this->_configSite['BASEURL'];
        $this->_REQUEST = $this->getURL($REQUEST, $baseurl);
        //echo "REQUEST = ".$this->_REQUEST."<br>";

        // URI를 배열화 합니다.
        $this->_urls = $this->explodeURI( $this->_REQUEST );        
        // $this->_locale = $this->isLocale($this->_urls);
        $this->_urlReals = $this->uriREAL($this->_urls);

        //echo "<pre>";
        //print_r($this->_urls);
        //echo "<hr>";
        //print_r($this->_urlReals);
        //echo "</pre>";


    }

    /**
     * uri 에서 컨트롤러 매소드를 분리합니다.
     * 분석한 URL은 _controller, _action, _param에 저장됩니다.
     */
    public function parser()
    {
        //echo __METHOD__."<br>";
 
        if (!empty($this->_urlReals)) {
            //echo "컨트롤러를 생성합니다..<br>";

            // 컨트롤러 선택합니다.
            $this->setController('\Jiny\Core\IndexController');

            // 실행 매서드를 선택합니다.
            $this->setMethod('index');

            // 파라미터
            $this->parmURL();

        } else {
            // root 접속일 경우, URI가 비어있을 수 있습니다.
            //echo "URI가 비어 있습니다.<br>";
        }     

        return $this;
    }

    /**
     * 컨트롤러와 매서드를 선택합니다.
     */
    private function setController($default=NULL)
    {
        if (!empty($this->_urlReals)) {
            
            if (isset($this->_urlReals[0])) {
                // 첫번째 인자는 컨트롤러
                $this->Application->_controller = ucwords($this->_urlReals[0])."Controller";
            } else {
                // 값이 없는 경우 기본으로 설정합니다.
                $this->Application->_controller = $default;
            }

            //echo $this->Application->_controller." 컨트롤러를 선택합니다.<br>";
        }
    
        return $this;
    }


    /**
     * 매서드를 선택합니다.
     */
    private function setMethod($default=NULL)
    {
        if (!empty($this->_urlReals)) {
            
            if (isset($this->_urlReals[1])) {
                $this->Application->_action = $this->_urlReals[1];
            } else {
                $this->Application->_action = $default;
            }

            // echo $this->Application->_action." 매소드를 선택합니다.<br>";
        }
    
        return $this;
    }

    /**
     * 파라미터를 선택합니다.
     */
    private function parmURL()
    {
        $url = $this->_urlReals;

        // 추가 $url 배열값이 없는 경우 비어있는 [] 배열을 저장
        unset($url[0],$url[1]);
        $this->Application->_prams = !empty($url) ? array_values($url): [];

        return $this;
    }


}