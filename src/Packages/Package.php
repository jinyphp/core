<?php
/*
 * This file is part of the jinyPHP package.
 *
 * (c) hojinlee <infohojin@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Jiny\Core\Packages;

class Package
{
    private $Application;
    private $_packages;

    /**
     * 
     */
    public function __construct($app)
    {
        // 
        $this->Application = $app;

        // 컴포저 패키지 정보를 읽어옵니다.
        $this->set();
    }


    /**
     * 컴포저 패키지 정보를 읽어옵니다.
     */
    private function set()
    {
        // \TimeLog::set(__METHOD__);
        $filename = ROOT.DS."composer.json";
        $Packages = file_get_contents($filename);
        $this->_packages = \json_decode($Packages);

        return $this;
    }


    /**
     * 패지키 전체 정보값을 반환합니다.
     */
    public function get()
    {
        // \TimeLog::set(__METHOD__);
        return $this->_packages;
    }


    /**
     * 패키지 require 부분을 반환합니다.
     */
    public function getPackages()
    {
        // \TimeLog::set(__METHOD__);
        return $this->_packages->require;
    }


    public function isPackage($name=NULL)
    {
        // \TimeLog::set(__METHOD__);
        if ($name) {
            if(isset($this->_packages->require->$name))
                return $this->_packages->require->$name;
        }

        return NULL;
    }

    /**
     * 
     */
}