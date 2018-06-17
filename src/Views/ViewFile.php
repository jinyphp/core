<?php

namespace Jiny\Core\Views;

use \Jiny\Core\Registry\Registry;

trait ViewFile
{
    protected $_pageType;
    protected $DOCX;

    protected $_tempFile;

    /**
     * 뷰(view) 파일을 읽어옵니다.
     */
    public function loadViewFile()
    {
        // \TimeLog::set(__METHOD__);

        // 설정한 경로에서 파일을 읽어 옵니다.
        $path = str_replace("/", DS, $this->conf->data('ENV.path.view'));
        $path = ROOT.$path;
        if ($this->view_file == "/") {
            $filepath = $path. DS;
        } else {
            $filepath = $path. DS. $this->view_file;
        }

        $body = $this->viewFile( $filepath );
        
        
        return $body;

    }

    /**
     * Indexs 순서에 맞에 파일을 읽어옵니다.
     * .env.php 설정을 참고합니다.
     */
    public function viewFile($path)
    {
        // \TimeLog::set(__METHOD__);
        $indexs = $this->Config->data("ENV.Resource.Indexs");
        foreach ($indexs as $name) {
            if (file_exists($path.$name)) {
                $arr = \explode(".",$name);

                $this->_pageType = isset($arr[1])? $arr[1]: NULL;

                if ($this->isFileUpdate($path.$name)) {
                    //echo "원본처리<br>";
                    if ($this->_pageType == "docx") {
                        $body = $this->getDocx($path.$name);
                    } else {
                        $body = $this->getFile($path.$name);
                    }

                    $this->tempFile($path.$name, $body);
                    return $body;

                } else {
                    //echo "캐쉬로 대체합니다.<br>";
                    return $this->getFile($path.$name.".tmp");
                }
                
            }
        }
    }

    public function isFileUpdate($name)
    {
        $origin = filemtime($name);
        if (file_exists($name.".tmp")) {
            $temp = filemtime($name.".tmp");
            //echo "케쉬=".$temp."<br>";

            if( $temp > $origin ) return FALSE; else return TRUE;

        } else {
            return TRUE;
        }
    }

    public function getDocx($name)
    {
        $this->DOCX = new \Docx_reader\Docx_reader();
        //echo "doc file = ".$path.$name."<br>";
        $this->DOCX->setFile($name);

        if(!$this->DOCX->get_errors()) {
            $html = $this->DOCX->to_html();
            $plain_text = $this->DOCX->to_plain_text();

            return $html;
        } else {
            // echo implode(', ',$doc->get_errors());
        }
    }

    public function getFile($name)
    {
        //echo $name."<br>";
        return file_get_contents($name);
    }

    public function tempFile($name, $body)
    {
        file_put_contents($name.".tmp", $body);
    }




}