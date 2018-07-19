<?php
namespace Jiny\Core\Http;

use \Jiny\Core\Registry\Registry;

class Bootstrap
{
    // 인스턴스
    private $Request;
    private $Locale;

    // 변수
    private $_REQUEST_URI;
    private $_REQUEST;
    private $_baseNamespace = "\Jiny\Core\\";
    private $_language, $_country;

    const LANGUAGE = TRUE;
    const COUNTRY = TRUE;

    private $_configSite;


    public function __construct($request)
    {
        $this->Request = $request;

        // ReWrite로 전달된 부스트래핑 URL을 분석합니다. 
        // REQUEST 값을 읽어 옵니다.
        $REQUEST = $this->getREQUEST();

        // baseurl을 제외한 처리 URL을 반환합니다.
        $baseurl = conf("site.BASEURL");
        $this->_REQUEST = $this->getURL($REQUEST, $baseurl);

        // URI를 배열화 합니다.
        $this->_urls = $this->explodeURI( $this->_REQUEST );
        
        $this->_urlReals = $this->uriREAL($this->_urls);

    }

    /**
     * 컨트롤러와 매서드를 선택합니다.
     */
    private function setController($default=NULL)
    {
        if (!empty($this->_urlReals)) {
            
            if (isset($this->_urlReals[0])) {
                // 첫번째 인자는 컨트롤러
                return ucwords($this->_urlReals[0])."Controller";
            } else {
                // 값이 없는 경우 기본으로 설정합니다.
                return $default;
            }
        }
    }


    /**
     * 매서드를 선택합니다.
     */
    private function setMethod($default=NULL)
    {
        // \TimeLog::set(__METHOD__);
        if (!empty($this->_urlReals)) {
            
            if (isset($this->_urlReals[1])) {
                return $this->_urlReals[1];
            } else {
                return $default;
            }
        }
    }

    /**
     * 파라미터를 선택합니다.
     */
    private function parmURL()
    {
        // \TimeLog::set(__METHOD__);
        $url = $this->_urlReals;

        // 추가 $url 배열값이 없는 경우 비어있는 [] 배열을 저장
        unset($url[0],$url[1]);
        return !empty($url) ? array_values($url): [];
    }

    private $_urls = [];
    private $_urlReals = [];
    private $_locale;

    /**
     * URL 배열을 반환합니다.
     */
    public function URL()
    {
        // \TimeLog::set(__METHOD__);
        return $this->_urlReals;
    }

    /**
     * url을 문자열로 반환합니다.
     */
    public function urlString()
    {
        $string = "";
        if($this->_urlReals){
            foreach ($this->_urlReals as $value) $string .= "/".$value;
            return $string;
        }        
    }

    /**
     * 실제 URL을 정리합니다.
     * 로케일 부분은 제외 합니다.
     */
    private function uriREAL($urls)
    {   
        // \TimeLog::set(__METHOD__);    
        // 로케일을 제외한 url를 반환
        if ( $this->isLocale($urls) ) {
            // 로케일이 있는 경우 url 배열을 정리합니다.
            $arr = [];
            unset($urls[0]);
            foreach ($urls as $value) array_push($arr, $value);            
            return $arr;

        } else {
            // 로케일이 없는 경우
            return $urls;
        }
    }

    /**
     * URI 로테일을 분석합니다.
     */
    private function isLocale($urls)
    {
        // \TimeLog::set(__METHOD__);
        if (isset($urls[0])) {
            // 로케일 코드값은 2글자 또는 5글자 입니다.
            $lenLC = strlen($urls[0]);
            if($lenLC == 2 || $lenLC == 5) {
                $Locale = Registry::create(\Jiny\Locale\Locale::class, "Locale", $this);
                return $Locale->parser($urls);
             
            } else {
                // 로케일 글자수가 다른 경우에는
                // 로케일 처리를 하지 않습니다.
            }                      
        }

        return FALSE;
    }

    /**
     * 입력한 URI를 구분하여, 배열화 합니다.
     * return: array
     */
    private function explodeURI($request)
    {
        // \TimeLog::set(__METHOD__);

        // URL의 마지막 '/'를 제거합니다.
        // 마지막 공백의 배열생성을 방지합니다.
        $string = trim($request, '/');

        if (!empty($string)) {
            // URL값이 있는 경우, 구분화 합니다.
            return explode('/', $string);
        }
        return NULL;
    }

    /**
     * 요청한 URI값을 읽어 옵니다.
     * $base는 url 시작이 부분을 스킵처리 합니다.
     */
    private function getURL($request, $base=NULL)
    {
        // \TimeLog::set(__METHOD__);
        if ($base) {
            return str_replace($base, "", $request);
        } else {
            return $request;
        }
    }

    /**
     * URL에서 GET부분을 제외한 실제 URL부분만 가지고 옵니다.
     */
    private function getREQUEST()
    {
        // \TimeLog::set(__METHOD__);
        return explode('?', $_SERVER['REQUEST_URI'])[0];
    }

    /**
     * 
     */

}