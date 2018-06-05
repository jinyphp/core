<?php

namespace Jiny\Core;

/**
 * 싱글톤 방식으로 동작합니다.
 * 프레임워크에서 필요로 하는 인스턴스를 동적으로 관리합니다.
 */
class Registry
{
    // 인스턴스를 관리하는 pool 변수 입니다.
    private static $_instance=[];

    private function __construct()
    {
        // 싱글톤 비활성화로 처리합니다.
    }

    private function __clone()
    {
        // 싱글톤 비활성화로 처리합니다.
    }

    // 저장된 인스턴스를 요구합니다.
    public function name($key, $default=NULL)
    {
        if (isset(self::$_instance[$key])) {
            return self::$_instance[$key];
        } else {
            // 인스턴스가 존재하지 않습니다.
            return $default;
        }
    }

    // 전달받은 객체로 인스턴스를 저장합니다. 
    public function set($key, $instance){
        self::$_instance[$key] = $instance;
    }

    // 레지스트리에서 생성된 인스턴스를 삭제합니다.
    public function erase()
    {
        unset(self::$_instance[$key]);
    }

    // 저장되어 있는 레지스트리 목록을 반환합니다.
    public function lists()
    {
        $arr = [];
        foreach (self::$_instance as $key => $value) {
            array_push($arr,$key);
        }
        return $arr;
    }

    public function create($classname, $key=NULL)
    {
        if ($classname) {
            Debug::out("Registry = ".$classname." 객체를 생성등록 합니다.");
            if ($key) {
                self::$_instance[$key] = $classname::instance(); 
                return self::$_instance[$key];

            } else {
                self::$_instance[$classname] = $classname::instance();
                return self::$_instance[$classname];
            }
                       
        } else {
            // 생성할 객체의 이름이 없습니다.
            return NULL;
        }
    }
}