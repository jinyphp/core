<?php

namespace Jiny\Core\Controllers;

class Controller
{
    protected $_view;
    protected $_model;

    protected $View;

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

    public function model($modelName, $data=[])
    {
        if (file_exists(MODELS. $modelName. '.php')) {
            // require MODEL. $modelName. '.php';
            // 오토로드를 통하여 파일 자동로드
            $this->_model = new $modelName;
        }
    }

}