<?php

namespace Jiny\Core\Views;

use \Jiny\Core\Registry\Registry;

trait ViewFile
{
    protected $_pageType;

    /**
     * 뷰(view) 파일을 읽어옵니다.
     */
    public function loadViewFile()
    {
        //echo __METHOD__."<br>";
        // 설정한 경로에서 파일을 읽어 옵니다.
        $path = $this->conf->data('ENV.path.view');
        return $this->viewFile( $path. DS. $this->view_file );

    }

    /**
     * Indexs 순서에 맞에 파일을 읽어옵니다.
     * .env.php 설정을 참고합니다.
     */
    public function viewFile($path)
    {
        //echo __METHOD__."<br>";
    
        $indexs = $this->Config->data("ENV.Resource.Indexs");
        //print_r($indexs);
        //echo $path."<br>";

        foreach ($indexs as $name) {
            if (file_exists($path.$name)) {
                $arr = \explode(".",$name);
                $this->_pageType = isset($arr[1])? $arr[1]: NULL;
                return file_get_contents($path.$name);
            }
        }
    }
}