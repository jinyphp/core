<?php

namespace Jiny\Core\Views;

use \Jiny\Core\Registry\Registry;

/**
 * jiny
 * 뷰파일을 읽어 처리를 합니다.
 */
trait ViewFile
{
    public $_filepath;

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
        $path = $this->filePath();

        if ($filename = $this->fileCheck($path)) {
            //echo "파일을 처리합니다. $filename";
            return $this->getFile($filename);
          
        } else {
            echo "페이지 파일이 없습니다.";
            exit;        
        }
    
    }

    public function fileCheck($path)
    {
        // 우선순위 파일명칭
        $indexs = $this->Config->data("ENV.Resource.Indexs");
        
        // 확장자 배열을 생성합니다.
        $exts = $this->getExts($indexs); 
        
        // 입력한 URL에 index가 있는지를 검사합니다.
        if ($name = $this->isIndex($path.$this->view_file, $indexs)) {           
            //echo $path.$this->view_file."<br>";
            return $name;
        } else {
            $filepath = $path.DS;

            $dd = \explode(DS, \trim($this->view_file, DS) );
            //print_r($dd);
            //echo "<br>";

            foreach ($dd as $value) {
                $filepath .= $value;
                //echo $filepath;
                if(is_dir($filepath)){
                    //echo " = 디렉토리<br>";
                    // 디렉토리인 경우 다음 경로를 찾습니다.
                    $filepath .= DS;
                    continue;
                } else {
                    //echo "== 파일<br>";
                    if ($name = $this->isExt($filepath, $exts)) {
                        // 디렉토리명과 일치한 파일이 있는 경우
                        // 해당 파일로 정의합니다.
                        return $name;
                    }
                }

                $filepath .= ".";
            }

        }
    
        return NULL;
    }


    public function isIndex($path, $indexs)
    {
        foreach ($indexs as $name) {
            if (file_exists($path.$name)) {
                $key = \explode(".", $name);
                if(isset($key[1])) $this->_pageType = $key[1];
                return $path.$name;
            }
        }
        return NULL;
    }

    public function isExt($filepath, $exts)
    {
        foreach ($exts as $ext) {
            if (file_exists($filepath.".".$ext)) {
                $this->_pageType = $ext;            
                return $filepath.".".$ext;
            } 
        }
        return NULL;
    }

    public function getExts($indexs)
    {
        $exts=[];
        foreach ($indexs as $name) {
            $key = \explode(".", $name);
            if(isset($key[1])){
                array_push($exts, $key[1]);
            }            
        }
        return $exts;
    }




    /**
     * 뷰파일을 읽어올 경로를 확인합니다.
     */
    public function filePath()
    {
        //기본 환경설정의 경로를 확인합니다.
        $path = $this->conf->data('ENV.path.view');

        // directory 구분자를 변경처리 합니다.
        $path = str_replace("/", DIRECTORY_SEPARATOR, $path);

        // 프로퍼티에 저장을 합니다.
        $this->_filepath = ROOT.$path;

        return $this->_filepath;
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
        // return file_get_contents($name);
        if ($this->_pageType == "docx") {
            $body = $this->getDocx($name);
        } else {
            $body = file_get_contents($name);;
        }

        return $body;
    }

    public function tempFile($name, $body)
    {
        file_put_contents($name.".tmp", $body);
    }




}