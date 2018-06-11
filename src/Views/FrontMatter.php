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
        // $frontMatter = new \Webuni\FrontMatter\FrontMatter();
        // $frontMatter = new \Jiny\FrontMatter\FrontMatter();
        $frontMatter = Registry::get("FrontMatter");

        $document = $frontMatter->parse($doc);
 
        // 머리말 데이터를 글로벌 설정으로 저장
        $this->_frontMatter = $document->getData();
        Registry::get("CONFIG")->set("page", $this->_frontMatter);

        $this->_body = $document->getContent();

        return $this;  
    }


}