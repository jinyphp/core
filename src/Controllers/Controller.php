<?php

namespace Jiny\Core\Controllers;

class Controller
{
    // Application 인스턴스
    protected $Application;

    protected $_view;
    protected $_model;

    protected $View;

    public function getApp()
    {
        return $this->Application;
    }

    public function setApp($app)
    {
        if (isset($app)) {
            $this->Application = $app;
        }
    }

    /**
     * 뷰 객체를 생성합니다.
     */
    public function view($viewName, $data=[])
    {
        //echo "View 객체를 생성합니다.<br>";
        $this->_view = new \Jiny\Core\Views\View($viewName, $data);
        return $this->_view;
    }

    public function viewFactory($userController)
    {
        //echo "View 객체를 생성합니다.<br>";
        $this->View = new \Jiny\Core\Views\View($userController);
        return $this->View;
    }

    public function viewPath()
    {
        $this->viewFile = $this->getPath();

        return $this->viewFile;
    }

    /**
     * 정적 페이지의 파일 경로를 재생성합니다.
     */
    public function getPath()
    {
        $url = $this->Application->boot->URL();
        if ($url) {
            foreach ($url as $value) {
                $path .= $value. DS;
            }
            return $path;
        } else {
            // Root 접속
            return "/";
        }
        
    }



    /**
     * 데이터베이스 모델
     */
    public function model($modelName, $data=[])
    {
        if (file_exists(MODELS. $modelName. '.php')) {
            // require MODEL. $modelName. '.php';
            // 오토로드를 통하여 파일 자동로드
            $this->_model = new $modelName;
        }
    }





}