<?php

namespace Jiny\Core;

/**
 * 싱글톤 방식으로 동작합니다.
 */
class Config 
{
    private function __construct()
    {
        // 싱글톤
    }

    private function __clone()
    {
        // 싱글톤
    }

    // 인스턴스 저장 프로퍼티
    private static $_instance;

    // 설정값을 저장하는 프로퍼티
    private static $_config=[];
    private static $_load=[];

    // 싱글톤 인스턴스를 생성
    // 메소드 처리입니다.
    public function instance()
    {
        if (!isset(self::$_instance)) {
            
            // 인스턴스를 생성합니다.
            // 자기 자신의 인스턴스를 생성합니다.
            self::$_instance = new self();

            Debug::out("기본 환경 설정값을 읽어 옵니다.");
            self::$_instance->_config['BASE'] = self::loadPHPReturn(".env", "..".DS);

            return self::$_instance;

        } else {
            // 인스턴스가 중복
        }
    }    

    public function __invoke()
    {
        return $this->_config;
    }

    public function data()
    {
        return $this->_config;
    }

    // 전체 설정값을 읽어 옵니다.
    public function load()
    {
        Debug::out("설정파일을 읽어옵니다.");
        $path = rtrim($this->_config['BASE']['conf'], "/")."/";
        foreach ($this->_load as $key => $value) {
            switch ($value) {
                case 'ini':
                    $this->_config[$key] = $this->loadINI($key, $path);
                    break;
                case 'php':
                    $this->_config[$key] = $this->loadPHPReturn($key, $path);
                    break;    
            }
        }
        
        return $this;
    }

    /**
     * 읽어올 환경설정 파일을 설정합니다.
     */
    public function setLoad($filename){
        Debug::out($filename." 설정파일을 등록합니다.");

        $parts = pathinfo($filename);
        $this->_load[ $parts['filename'] ] = $parts['extension'];
        return $this;
    }

    // 설정파일을 자동으로 찾아 로드합니다.
    public function autoSetUp()
    {
        echo "환경설정 파일<Br>";
        $pathDir = $this->_config['BASE']['conf'];

        if (is_dir($pathDir)) {
            echo "OK] $pathDir 디렉터리 작업이 가능합니다.<br>";
            $dirARR = scandir($pathDir);
            
            for ($i=0;$i<count($dirARR);$i++) {
                // _로 시작하는 파일은 제외합니다.
                if ($dirARR[$i][0] == "_" || $dirARR[$i][0] == ".") {
                    echo "파일을 제외합니다.".$dirARR[$i]."<br>";
                } else {
                    echo $dirARR[$i]."<br>";
                    $this->setLoad($dirARR[$i]);
                }
                
            }

        } else {
            echo "Err] $pathDir 디렉터리가 존재하지 않습니다.<br>";
        }
    }


    /**
     * PHP Return 배열로 된 설정값을 읽어 옵니다.
     * [
     *  "name"=>"aaa"
     * ]
     */
    public function loadPHPReturn($name, $path=NULL)
    {
        if ($name) {
            if ($path) {
                $filename = $path.$name.".php";
            } else {
                $filename = $name;
            }

            if (file_exists($filename)) {
                Debug::out(">>>".$filename." 설정값을 읽어 옵니다.");
                return include ($filename);
            } else {
                // 파일이 존재하지 않습니다.
                Debug::out(">>>파일이 존재하지 않습니다.");
            }            
        } else {
            // 파일 이름이 없습니다.
        }
    }

    // ini 설정파일을 로드합니다.
    public function loadINI($name, $path=NULL)
    {
        if ($name) {
            if ($path) {
                $filename = $path.$name.".ini";
            } else {
                $filename = $name;
            }

            if (\file_exists($filename)) {
                Debug::out(">>>".$name." 설정값을 읽어 옵니다.");
                $str = file_get_contents($filename);           
                return \parse_ini_string($str);       
            } else {
                // 파일이 없습니다.
                Debug::out(">>>파일이 존재하지 않습니다.");
            }            
        } else {
            // 이름이 없습니다.
        }
        
        return $this;
    }

}