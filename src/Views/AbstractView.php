<?php
namespace Jiny\Core\Views;

use \Jiny\Core\Registry\Registry;

abstract class AbstractView
{
    protected $view_file;
    public $view_data;

    public function setViewFile($file)
    {
        $this->view_file = $file;
    }

    public function setViewData($data)
    {
        $this->view_data = $data;
    }

    public function mergeViewData($arr)
    {
        //echo __METHOD__."를 호출합니다.<br>";
        if (\is_array($this->view_data)){
            //echo "배열을 병합합니다.<br>";
            //print_r($arr);
            array_merge($this->view_data, $arr);
            //echo "<br><br>";
        } else {
            $this->view_data = $data; 
        }        
    }

    public function getViewData()
    {
        return $this->view_data;
    }

    protected $conf;
    protected $Config;
    protected $Theme;

    /**
     * 필요한 인스턴스를 재설정합니다.
     * 메소드 호출 빈도를 줄여 줍니다.
     */
    protected function instanceInit()
    {
        // 객체참조 개선을 위해서 임시저장합니다.        
        $this->conf = Registry::get("CONFIG");
        $this->Config = $this->conf;

        // 매소드 호출 횟수를 줄이기 위해서 임시변수
        $this->Theme = Registry::get("Theme");
    }
}