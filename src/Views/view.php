<?php
namespace Jiny\Core\Views;

use \Jiny\Core\Registry\Registry;

class View {
    protected $view_file;
    protected $view_data;

    protected $_body;
    protected $_pageType;

    protected $_header;
    protected $_footer;

    protected $Menu;

    private $Theme;
    private $_themeENV;

    private $conf;
    private $userController;

    public function __construct($userController)
    {
        // echo "클래스 ".__CLASS__." 객체 인스턴스가 생성이 되었습니다.<br>";
        $this->userController = $userController;

        $this->view_file = $this->userController->viewFile;
        $this->view_data = $this->userController->viewData;

        // 객체참조 개선을 위해서 임시저장합니다.
        $this->conf = Registry::get("CONFIG");

        // 매소드 호출 횟수를 줄이기 위해서 임시변수
        $this->Theme = Registry::get("Theme");
        $this->view_data['menu'] = $this->userController->Menu->getTree();

    }

    // View HTML파일을 로드합니다. 읽어온 내용은 _body에 저장을 합니다.
    // HTMLS 경로디렉토리
    public function create($data=[])
    {
        // echo __METHOD__."를 호출합니다.<br>";
        $this->loadViewFile();

        // 머리말을 체크합니다.
        // 문서에서 머리말을 분리합니다.
        $this->frontMatter($this->_body);

        // 페이지를 처리합니다.
        switch ($this->_pageType) {
            case 'htm':
                //echo "html 파일을 출력합니다.<br>";
                break;
            case 'md':
                //echo "md 파일을 출력합니다.<br>";
                // 내용을 마크다운 -> html로 변환합니다.
                $this->markDown();
                break;
        }

        // 페이지 레이아웃 처리
        if($this->_frontMatter['layout']){
            $pageLayout = $this->Theme->loadFile( $this->_frontMatter['layout'] );
            if ($pageLayout) {
                echo "레이아웃을 적용합니다.<br>";
                $this->_body = str_replace("{{ content }}", $this->_body, $pageLayout);
            }
        }

        return $this;
    }


    public function loadViewFile()
    {
        $filename = HTMLS. $this->view_file;
        $this->_body = $this->pageFile($filename);
    }



    const PREFIX_START = "{%%";
    const PREFIX_END = "%%}";
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

    /**
     * 머리말 처리
     */
    use FrontMatter;


    /**
     * 화면을 출력합니다.
     */
    public function show()
    {
        // echo __METHOD__."를 호출합니다.<br>";
        // 해더를 생성합니다.
        $this->Theme->header();
        $this->_header = $this->Theme->headerRender($this->view_data);

        // 푸터를 생성합니다.
        $this->_footer = $this->Theme->footer()->footerRender();
        
        // 레이아웃을 체크합니다.
        $layoutBody = $this->Layout($this->Theme->_env['layout']);

        // 랜더링을 처리합니다.
        $layoutBody = $this->pageRender($layoutBody);

        echo $layoutBody;          
             
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