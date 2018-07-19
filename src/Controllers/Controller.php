<?php

namespace Jiny\Core\Controllers;

use \Jiny\Core\Registry\Registry;

class Controller
{
    // App 인스턴스
    protected $App;

    protected $_view;
    protected $_model;

    protected $View;

    public function getApp()
    {
        // \TimeLog::set(__METHOD__);
        return $this->App;
    }

    public function setApp($app)
    {
        // \TimeLog::set(__METHOD__);
        if (isset($app)) {
            $this->App = $app;
        }
    }




    /**
     * 뷰 객체를 생성합니다.
     * 진행합니다.
     */

     /*
    public function view($viewName=Null, $datas=[])
    {
        \TimeLog::set(__METHOD__);

        // $this->View = new \Jiny\Core\Views\View($this);
        $this->View = $this->viewFactory();

        // 뷰 데이터를 설정합니다.
        // $this->View->appendViewData("datas", $datas);
        $this->View->view_data["datas"] = _array_KeyAppend($this->View->view_data,"datas", $datas);

        
        // 뷰를 처리합니다.
        return $this->View->process($viewName);        
    }
    */


    /**
     * 뷰 인스턴스를 생성합니다.
     * 레지스트리에 저장합니다.
     */

     /*
    public function viewFactory()
    {
        //echo "뷰 객체를 생성합니다.<br>";
        return Registry::create(\Jiny\Views\View::class, "view", $this);
    }
    */



    public function viewPath()
    {
        // \TimeLog::set(__METHOD__);
        /*
        $this->viewFile = $this->getPath();

        return $this->viewFile;
        */
    }

    /**
     * 정적 페이지의 파일 경로를 재생성합니다.
     */
    public function getPath()
    {
        // \TimeLog::set(__METHOD__);
        $url = $this->App->Boot->URL();
        if ($url) {
            $path = DS;
            foreach ($url as $value) {
                $path .= $value. DS;
            }
            return $path;
        } else {
            // Root 접속
            return DS;
        }
        
    }



    /**
     * 데이터베이스 모델
     */
    public function model($modelName, $data=[])
    {
        // \TimeLog::set(__METHOD__);
        if (file_exists(MODELS. $modelName. '.php')) {
            // require MODEL. $modelName. '.php';
            // 오토로드를 통하여 파일 자동로드
            $this->_model = new $modelName;
        }
    }


    /**
     * 
     */

}