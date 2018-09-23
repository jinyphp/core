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
    //private $_language, $_country;

    const LANGUAGE = TRUE;
    const COUNTRY = TRUE;

    private $_configSite;

    

    public function __construct($request)
    {
        $this->Request = $request;

        // 시작 url을 기반으로 uri를 분석합니다.
        $this->Request->_base = conf("site.BASEURL");
        $this->url($this->Request->_base);
        $this->Request->_uri = $this->uri($this->Request->_url);

    }

    public function url($base)
    {
        // ?쿼리스트링 분리
        $url = explode('?', $_SERVER['REQUEST_URI']);

        // uri 분리
        if($url[0] != "/"){
            $this->Request->_url = explode('/', $this->base($url[0], $base));
        }        
        
        // query 분리
        if(isset($url[1])) {
            $query = explode('&', $url[1]);
            foreach ($query as $value) {
                $k = explode('=', $value);
                $arr[ $k[0] ] = $k[1];
            }
            $this->Request->_query = $arr;
        }
        

        return $this;
    }

    /**
     * $base는 url 시작이 부분을 스킵처리 합니다.
     */
    private function base($url, $base=NULL)
    {
        if ($base) {
            return trim(str_replace($base, "", $url), '/');
        } else {
            return trim($url, '/');
        }
    }

    /**
     * 실제 URI을 식별자를 정리합니다.
     */
    private function uri($urls)
    {      
        // 로케일 검사
        if ( $locale = $this->isLocale($urls) ) {
            $this->Request->_country = $locale['country'];
            $this->Request->_language = $locale['language'];

            // 로케일이 있는 경우
            // uri 배열을 생성합니다.
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
     * URI 로케일을 분석합니다.
     */
    private function isLocale($uris)
    {
        // 로케일을 처리하기 위해서는 1단어 이상의 url이 필요합니다.
        // `/`와 같이 입력을 한경우에는 로케일 분석처리할 수 없습니다.
        if (isset($uris[0])) {

            // 로케일 코드값은 2글자 또는 5글자 입니다.
            $len = strlen($uris[0]);
            if($len == 2 || $len == 5) {

                // 인스턴스를 생성, 처리후에 자동적으로 메모리를 해제합니다.
                $Locale = new \Jiny\Locale\Locale;
                return $Locale->parser($uris);

            } else {
                // 로케일 글자수가 다른 경우에는
                // 로케일 처리를 하지 않습니다.
            }                      
        }

        return FALSE;
    }

    /**
     * 
     */

}