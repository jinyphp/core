<?php
/*
 * This file is part of the jinyPHP package.
 *
 * (c) hojinlee <infohojin@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
// workflow2
namespace Jiny\Core;

class Boot
{    
    public function __construct()
    {
        // echo __CLASS__;
    }

    private function contentType()
    {
        return isset($_SERVER['CONTENT_TYPE']) ? $_SERVER['CONTENT_TYPE'] : null;
        // echo "contentType=".$_SERVER['CONTENT_TYPE'];
        // 폼입력 : application/x-www-form-urlencoded
    }

    function start($map=null)
    {
        $type =$this->contentType();        
        switch($type) {
            case 'application/json':
                // API 응용 프로그램
                return $this->apiApp();
            default:
                // Web 응용 프로그램
                return $this->webApp();
        }       
    }

    // Factory 
    private function apiApp()
    {
        try {
            return new \Jiny\API\ApplicationJson();
        } catch (\Throwable $ex) {
            echo "API Application 클래스 객체를 생성할 수 없습니다.";
            exit;
        }
    }

    // Factory 
    private function webApp()
    {
        try {
            return new \Jiny\App\Application();
        } catch (\Throwable $ex) {
            echo "Web Application 클래스 객체를 생성할 수 없습니다.";
            exit;
        }
    }

}