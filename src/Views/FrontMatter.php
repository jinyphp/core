<?php

namespace Jiny\Core\Views;

use \Jiny\Core\Registry\Registry;

trait FrontMatter
{

    /**
     * front matter
     * 컴포저 페키지 webuni/front-matter
     */
    public function frontMatter($doc)
    {
        // \TimeLog::set(__METHOD__);

        // echo "문서에서 머리말을 분리합니다.<br>";
        $document = Registry::get("FrontMatter")->parse($doc);

        $path = ROOT.$this->conf->data('ENV.path.view');
        $dataYMAL = $path. DS. $this->view_file."index.yaml";
        if (file_exists($dataYMAL)){
            //echo "ymal 데이터가 존재합니다.<br>";
            $str = file_get_contents($dataYMAL);
         
            $data = $this->conf->Drivers['Yaml']->parser($str);
           
            $this->view_data['Page'] = $data;
            Registry::get("CONFIG")->set("Page", $data); 

        } else {
            // echo "ymal 데이터가 없습니다.<br>";
            // 머리말 데이터를 글로벌 설정으로 저장
            //echo $document[0];
            $docDATA = $document->getData();
            $this->view_data['Page'] = $docDATA;        
            Registry::get("CONFIG")->set("Page", $docDATA);
        }
        
        $this->_body = $document->getContent();

        return $this;  
    }





}