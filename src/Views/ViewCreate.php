<?php

namespace Jiny\Core\Views;

use \Jiny\Core\Registry\Registry;

/**
 * 뷰의 페이지를 처리합니다.
 */

trait ViewCreate
{
    public $Template;
    /**
     * 머리말 처리
     */
    use FrontMatter;
    use MarkDown;
    use Prefix;

    public $_body;
    public $_data=[];

    /**
     * View HTML파일을 로드합니다.
     * 읽어온 내용은 _body에 저장을 합니다.
     * HTMLS 경로디렉토리
     */
    public function create($data=[])
    {
        // echo __METHOD__."를 호출합니다.<br>";

        // 뷰파일을 읽어 옵니다.
        $body = $this->loadViewFile();

        // 머리말을 체크합니다.
        // 문서에서 머리말을 분리합니다.
        $this->frontMatter($body);

        // 문서를 변환합니다.
        // 문서 타입
        $this->convert($this->_pageType);

        // 지니 
        if ( $this->conf->data("ENV.Tamplate.PreProcess") ) {
            // $this->_body = pageRender($this->_body);
        }

        // 템플릿을 처리합니다
        if ( $this->conf->data("ENV.Tamplate.Engine") ) {            
            $this->Template = new \Jiny\Template\Template($this);
        }
        
        // 페이지 레이아웃 처리
        $this->layoutRender();

        return $this;
    }
    

    /**
     * 문서가 markdown, word 파일일때 변환처리르 합니다.
     */
    public function convert($type)
    {
        // echo __METHOD__."를 호출합니다.<br>";

        // 페이지를 처리합니다.
        switch ($type) {
            case 'htm':
                //echo "html 파일을 출력합니다.<br>";             
                break;

            case 'md':
                //echo "md 파일을 출력합니다.<br>";
                // 내용을 마크다운 -> html로 변환합니다.
                $this->markDown();
                break;

            case 'docx':
                // 워드 문서를 변환합니다.
                break;    
        }

        return $this;
    }

    /**
     * 머리말 레이아웃
     */
    public function layoutRender()
    {
        // echo __METHOD__."<br>";
        if($this->_data['layout']){
            $layout = $this->Theme->loadFile( $this->_data['layout'] );
            if ($layout) {
                // echo "레이아웃을 적용합니다.<br>";
                $this->_body = str_replace("{{ content }}", $this->_body, $layout);
            }
        }
    }
    


















        public function pageRender($body)
        {
            $prefixdCode = $this->Theme->setPrefix(self::PREFIX_START, self::PREFIX_END)->preFixs($body);
            foreach ($prefixdCode as $value) {
    
                switch ($value[0]) {
                    case '#':
                      
                        // 환경변수의 값을 출력합니다.
                        $data = $this->conf->data( substr($value, 1) );
                        $body = str_replace(
                            self::PREFIX_START." ".$value." ".self::PREFIX_END, 
                            $data, 
                            $body);
                           
                        break;
                }
    
            }
        
    
            return $body;
        }
    
    
    

}