<?php

namespace Jiny\Core\Views;

use \Jiny\Core\Registry\Registry;

trait ViewFile
{
    /**
     * 뷰(view) 파일을 읽어옵니다.
     */
    public function loadViewFile()
    {
        //echo __METHOD__."<br>";

        $path = $this->conf->data('ENV.path.view');
        $this->_body = $this->viewFile( $path. DS. $this->view_file );

    }

    /**
     * htm, md 처리순으로 파일을 읽어옵니다.
     */
    public function viewFile($filename)
    {
        //echo __METHOD__."<br>";
        
        // htm 파일을 처리합니다.
        if (file_exists($filename.".htm")) {
            $this->_pageType = "htm";
            return file_get_contents($filename.".htm");

        } else 
        // md 파일을 반환합니다.
        if (file_exists($filename.".md")) {            
            $this->_pageType = "md";
            return file_get_contents($filename.".md");

        } else {
            return NULL;
        }
    }
}