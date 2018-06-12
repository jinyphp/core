<?php

namespace Jiny\Core\Views;

use \Jiny\Core\Registry\Registry;

/**
 * 뷰의 페이지를 처리합니다.
 */

trait ViewShow
{
    /**
     * 화면을 출력합니다.
     */
    public function show()
    {
        //echo __METHOD__."를 호출합니다.<br>";
        // 해더를 생성합니다.
        $this->Theme->header();
        $this->_header = $this->Theme->headerRender($this->view_data);
        if ($this->Template) {
            //echo "해더 템플릿.<br>";
            $this->_header = $this->Template->Liquid->Liquid($this->_header, $this->view_data);
        } else {
            //echo "템플릿이 없습니다.";
        }
    

        // 푸터를 생성합니다.
        $this->_footer = $this->Theme->footer()->footerRender($this->view_data);
        if ($this->Template) {
            //echo "푸터 템플릿.<br>";
            $this->_footer = $this->Template->Liquid->Liquid($this->_footer, $this->view_data);
        } else {
            //echo "템플릿이 없습니다.";
        }
        
        // 레이아웃을 체크합니다.
        $body = $this->Layout($this->Theme->_env['layout']);

        // 랜더링을 처리합니다.
        $body = $this->pageRender($body);

        echo $body;          
             
    }


    /**
     * 페이지 레이아웃이 설정되어 있는 경우
     * 레이아웃으로 래핑합니다.
     */
    public function Layout($layout=NULL)
    {
        if (isset($layout)) {
            // 레이아웃 파일을 읽어옵니다.
            $layoutBody = $this->Theme->layout();

            // 해더를 치환합니다.
            $layoutBody = str_replace(
                $this->Theme->_env['_header'], 
                $this->_header, 
                $layoutBody);

            // 푸터를 치환합니다.
            $layoutBody = str_replace(
                $this->Theme->_env['_footer'], 
                $this->_footer, 
                $layoutBody);

            // 본문을 치환합니다.    
            $layoutBody = str_replace(
                $this->Theme->_env['_content'], 
                $this->_body, 
                $layoutBody);

        } else {
            // 레이아웃이 없는 경우 바로 출력합니다.
            $layoutBody = $this->_header.$this->_body.$this->_footer;         
        }
        return $layoutBody;
    }

    public function getAction()
    {
        // echo __METHOD__."를 호출합니다.<br>";
        // 반환되는 값은 배열타입 입니다.
        // 배열의 1을 반환합니다.
        // echo "view_file = ". $this->view_file. "<br>";
        return (explode('\\', $this->view_file)[1]);
    }
}