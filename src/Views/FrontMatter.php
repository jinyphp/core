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
 
        $this->view_data['Page'] = $document->getData();
        // array_merge();
        //$this->_data = $document->getData();

        // 머리말 데이터를 글로벌 설정으로 저장
        Registry::get("CONFIG")->set("page", $this->_data);        

        $this->_body = $document->getContent();

        return $this;  
    }


}