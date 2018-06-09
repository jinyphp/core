<?php

namespace Jiny\Core;

use \Jiny\Core\Registry\Registry;

trait FrontMatter
{

    public function pageFile($filename)
    {
        if (file_exists($filename.".htm")) {
            // htm 파일을 반환합니다.
            $this->_pageType = "htm";
            return file_get_contents($filename.".htm");
        } else if (file_exists($filename.".md")) {
            // md 파일을 반환합니다.
            $this->_pageType = "md";
            return file_get_contents($filename.".md");
        } else {
            return NULL;
        }
    }


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

    // _Body 마크다운 변환을 처리합니다.
    public function markDown()
    {
        // 마크다운 변환
        // 컴포저 패키지 참고
        $Parsedown = new \Parsedown();            
        $this->_body = $Parsedown->text($this->_body);
        return $this;
    }
}