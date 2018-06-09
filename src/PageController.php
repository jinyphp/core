<?php
namespace Jiny\Core;

class PageController extends Controller
{
    private $Application;

    public function __construct($app)
    {
        //echo __CLASS__." 객체를 생성하였습니다.<br>";
        $this->Application = $app;
    }

    // 기본실행 메서드
    public function index()
    {
        //echo __METHOD__."를 호출합니다.<br>";        
        $pagepath = $this->pagePath();
        $indexpage = $pagepath."index";

        $data = [
            'name'=>$name,
            'id'=>$id
        ];

        // 뷰 객체를 생성합니다.
        $this->view($indexpage, $data);

        // 페이지를 처리
        $this->_view->create();

        // 화면출력
        $this->_view->show();
    }

    /**
     * 정적 페이지의 파일 경로를 재생성합니다.
     */
    public function pagePath()
    {
        foreach ($this->Application->_uri as $value) {
            $pagepath .= $value. DS;
        }

        return $pagepath;
    }

}