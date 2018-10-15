<?php
/*
 * This file is part of the jinyPHP package.
 *
 * (c) hojinlee <infohojin@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Jiny\Core\Http;

class Request
{
    private $App;

    public $_base;

    // URI,Query 정보
    public $_url = [];
    public $_query = [];

    public $_language;
    public $_country;
    public $_uri;

    /**
     * 초기화
     * 인스턴스 주입
     */
    public function __construct($app)
    {
        $this->App = $app;
    }


    /**
     * url을 문자열로 반환합니다.
     */
    public function urlString()
    {
        $string = "";
        if($this->_uri){
            foreach ($this->_uri as $value) $string .= "/".$value;
            return $string;
        }        
    }


    /**
     * URI에서 컨트롤러를 추출합니다.
     */
    public function getController($default=NULL)
    {
        if (!empty($this->_uri)) {
            
            // 컨트롤러는 URI의 첫번째 단어 입니다.
            if (isset($this->_uri[0])) {
                // 첫글자 대문자
                // 낙타스타일
                return ucwords($this->_uri[0])."Controller";
            } else {
                // 값이 없는 경우 기본으로 설정합니다.
                return $default;
            }
        }
    }


    /**
     * URI에서 메소드를 추출합니다.
     */
   public function getMethod($default=NULL)
    {
        if (!empty($this->_uri)) {
            
            // 메소드는 URI의 두번째 단어 입니다.
            if (isset($this->_uri[1])) {
                return $this->_uri[1];
            } else {
                return $default;
            }
        }
    }


    /**
     * 파라미터를 선택합니다.
     */
    public function parm()
    {
        $url = $this->_uri;

        // 추가 $url 배열값이 없는 경우 비어있는 [] 배열을 저장
        unset($url[0],$url[1]);
        
        return !empty($url) ? array_values($url): [];
    }


    /**
     * 로케일 국가를 설정합니다.
     */
    public function setCountry($country)
    {
        $this->_country = $country;

        // 글로벌 설정값
        config_set("country", $country);
        config_set("req.country", $country);
    }

    
    /**
     * 로케일 언어를 설정합니다.
     */
    public function setLanguage($language)
    {
        $this->_language = $language;
        
        // 글로벌 설정값
        config_set("language", $language);
        config_set("req.language", $language);
    }

    /**
     * 
     */
}