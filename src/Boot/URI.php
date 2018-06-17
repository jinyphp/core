<?php

namespace Jiny\Core\Boot;

use \Jiny\Core\Registry\Registry;

trait URI
{
    private $_urls = [];
    private $_urlReals = [];
    private $_locale;

    public function URL()
    {
        // \TimeLog::set(__METHOD__);
        return $this->_urlReals;
    }

    private function uriREAL($urls)
    {   
        // \TimeLog::set(__METHOD__);    
        // 로케일을 제외한 url를 반환
        if ( $this->isLocale($urls) ) {
            //echo "로케일을 설정합니다.<br>";
            $arr = [];
            unset($urls[0]);
            foreach ($urls as $value) {
                //echo $value."<br>";
                array_push($arr, $value);
            }
            return $arr;
        } else {
            // 로케일이 없는 경우
            //echo "locale 설정이 없습니다.<br>";
            return $urls;
        }
    }

    /**
     * URI를 이용하여 로테일을 분석합니다.
     */
    private function isLocale($url)
    {
        // \TimeLog::set(__METHOD__);

        if (isset($url[0])) {

            if ( $this->Locale->isCountry($url[0]) ) {
                $this->_Country = $url[0];
                //echo "국가설정 = ".$this->_Country."<br>";
                $this->Locale->setAppCountry($url[0]);

                return TRUE;

            } else if ( $this->Locale->isLanguage($url[0]) ) {
                $this->_Language = $url[0];
                //echo "언어설정 = ".$this->_Language."<br>";
                $this->Locale->setAppLanguage($url[0]);

                return TRUE;

            } else if ( $this->Locale->isCulture($url[0]) ) {
                //echo "컬처설정 = ".$url[0]."<br>";
                $code = explode("-",$url[0]);
                
                $this->_Language = $code[0];
                //echo "언어설정 = ".$this->_Language."<br>";
                $this->Locale->setAppLanguage($code[0]);

                $this->_Country = $code[1];
                //echo "국가설정 = ".$this->_Country."<br>";
                $this->Locale->setAppCountry($code[1]);

                return TRUE;

            }           

        }

        return FALSE;
    }

    /**
     * 입력한 URI를 구분하여, 배열화 합니다.
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

        } else {
            // $this->Application->_uri = NULL;
            // $uris = NULL;
            return NULL;
        }
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

}