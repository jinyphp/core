<?php
namespace Jiny\Core\Views;

use \Jiny\Core\Registry\Registry;
use Liquid\Template;

class View extends AbstractView 
{
    private $Application;

    protected $_header;
    protected $_footer;
    protected $Menu;
    
    private $_themeENV;    
    private $Controller;
    
    // trait...
    use ViewFile, ViewCreate, ViewShow;

    const PREFIX_START = "{(";
    const PREFIX_END = ")}";

    public function __construct($Controller)
    {
        // \TimeLog::set(__CLASS__."가 생성이 되었습니다.");
        $this->Controller = $Controller;

        // 컨트롤러의 데이터를 
        // 뷰로 전달합니다.
        $this->setViewFile( $this->Controller->viewFile );
        $this->setViewData( $this->Controller->viewData );

        $this->instanceInit();

        // 메뉴 데이터를 읽어옵니다.
        $this->view_data['menus'] = $this->Controller->Menu->getTree();

    }

}