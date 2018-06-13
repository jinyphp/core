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
        // echo "문서에서 머리말을 분리합니다.<br>";
        $document = Registry::get("FrontMatter")->parse($doc);

        $path = ROOT.$this->conf->data('ENV.path.view');
        //echo $path. DS. $this->view_file;
        if (file_exists($path. DS. $this->view_file."index.yaml")){
            //echo "ymal 데이터가 존재합니다.<br>";
            $str = file_get_contents($path. DS. $this->view_file."index.yaml");
            //echo $str;
            //echo "<br>";
            $data = $this->conf->Drivers['Yaml']->parser($str);
            // print_r($data);
            $this->view_data['Page'] = $data;
            Registry::get("CONFIG")->set("page", $data); 

        } else {
            // echo "ymal 데이터가 없습니다.<br>";
            // 머리말 데이터를 글로벌 설정으로 저장
            $this->view_data['Page'] = $document->getData();        
            Registry::get("CONFIG")->set("page", $this->_data);
        }
        
        //echo "<pre>";
        //print_r($this->view_data['Page']);
        //echo "</pre>";

        $this->_body = $document->getContent();

        return $this;  
    }


}