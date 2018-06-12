<?php

namespace Jiny\Core\Registry;

/**
 * 싱글톤 방식으로 동작합니다.
 * 프레임워크에서 필요로 하는 인스턴스를 동적으로 관리합니다.
 */

class Registry
{
    private function __construct()
    {
        // 싱글톤 비활성화로 처리합니다.
    }

    private function __clone()
    {
        // 싱글톤 비활성화로 처리합니다.
    }


    /**
     * 인스턴스를 관리하는 pool 변수 입니다.
     */
    private static $_instances=[];

   
    /**
     * 레지스트리를 초기화 합니다.
     */
    public static function init($list=NULL)
    {
        self::$_instances = [];

        // 필수 인스턴스를 생성하여 저장을 합니다.
        foreach ($list as $key => $value) {
            self::createInstance($value, $key);
        }

        // 싱글톤 객체 생성 반환처리
        return new self();
    }

    /**
     * 인스턴스를 생성 등록합니다.
     */
    public function createInstance($name, $key=NULL, $instance=NULL)
    {
        // echo __METHOD__."<br>";
        // echo $name."=".$key."<br>";

        // 싱글톤 생성호출 메소드 존재 여부를 확인합니다.
        if (method_exists($name, "instance")) {
            // 싱글톤 메서드 호출
            $obj = $name::instance(); 
        } else {
            // 인스턴스 생성
            if($instance){
                $obj = new $name ($instance);
            } else {
                $obj = new $name;
            }
            
        }

        if ($key) {
            // 키 값이 있는 경우, 키를 이용하여 저장합니다.
            self::$_instances[$key] = $obj;
        } else {
            // 키 값이 없는 경우 클래스, 네임스페이스 결합으로 사용합니다.
            self::$_instances[$name] = $obj;
        }

        // 생성한 객체를 반환합니다.
        return $obj;
    }

    /**
     * 저장된 인스턴스를 반환합니다.
     */
    public function get($key, $default=NULL)
    {
        if (isset(self::$_instances[$key])) {            
            return self::$_instances[$key];
        } else {
            // 인스턴스가 존재하지 않습니다.
            // echo "인스턴가 존재하지 않습니다.";
            return $default;
        }
    }

    /**
     * 인스턴스를 저장합니다.
     */
    public function set($key, $instance){
        self::$_instances[$key] = $instance;
    }

    public function setInstance($key, $instance)
    {
        $this->_instances[$key] = $instance;
    }

    /**
     * 인스턴스를 삭제합니다.
     */
    public function erase()
    {
        unset(self::$_instances[$key]);
    }

    /**
     * 인스턴스 목록
     * 키값만 추출하여 반환합니다.
     */
    public function lists()
    {
        $arr = [];
        foreach ($this->_instances as $key => $value) {
            array_push($arr,$key);
        }
        return $arr;
    }

    /**
     * 전체 인스턴스
     */
    public function instances()
    {
        return $this->_instances;
    }


}