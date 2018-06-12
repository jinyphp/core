<?php
namespace Jiny\Core;

class IndexController extends Controller
{
    public function __construct($app=NULL)
    {
        // echo __CLASS__." 객체를 생성하였습니다.<br>";
        // 의존성주입, 상위 Application의 객체를 저장합니다.
        // $this->Application = $app;
        $this->setApp($app);
    }

    public function index($id='', $name='')
    {   
        // echo __METHOD__."를 호출합니다.<br>";

        // 뷰객체 인스턴스를 생성합니다. 컨트롤러의 팩토리패턴을 이용하여 생성을 합니다.
        // $viewFile = "home\index";
        // $viewFile = "index";
        
        // view로 전달되는 데이터 array
        $this->viewData = [
            'name'=>$name,
            'id'=>$id
        ];

        // view로 전달되는 데이터 array
        /*
        $this->view($viewFile, [
            'name'=>$name,
            'id'=>$id
        ]); 
        */

        // 메뉴를 생성합니다.
        // 인스턴스 Pool에 등록합니다.
        $this->Menu = new \Jiny\Menu\Menu($this->Application);
        $this->Application->Registry->set("Menu", $this->Menu);
        
        // 뷰 객체를 생성합니다.
        // 페지이는 뷰로 처리합니다.
        $this->viewFactory($this);

        // 뷰처리를 생성합니다.
        $this->View->create();

        // 화면출력
        $this->View->show();

        // 뷰를 페이지를 생성합니다.
        // $this->_view->create($data)->show();
        
    }


}