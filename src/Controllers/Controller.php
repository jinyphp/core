<?php
/*
 * This file is part of the jinyPHP package.
 *
 * (c) hojinlee <infohojin@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Jiny\Core\Controllers;

use \Jiny\Core\Registry\Registry;

class Controller
{
    // App 인스턴스
    protected $App;

    protected $_view;
    protected $_model;

    protected $View;

    /**
     * 
     */
    public function getApp()
    {
        return $this->App;
    }


    /**
     * 
     */
    public function setApp($app)
    {
        if (isset($app)) {
            $this->App = $app;
        }
    }


    /**
     * 뷰 객체를 생성합니다.
     * 진행합니다.
     */
    /*
    public function view($name=Null, $datas=[])
    {
        if ($name) {
            return view($name, $datas);
        }
    }
    */


    /**
     * 뷰 인스턴스를 생성합니다.
     * 레지스트리에 저장합니다.
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
        $url = $this->App->Request->_uri;

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