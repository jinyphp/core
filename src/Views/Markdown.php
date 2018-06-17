<?php

namespace Jiny\Core\Views;

use \Jiny\Core\Registry\Registry;

trait MarkDown
{
    // _Body 마크다운 변환을 처리합니다.
    public function markDown()
    {
        // \TimeLog::set(__METHOD__);
        // 마크다운 변환
        // 컴포저 패키지 참고
        $Parsedown = new \Parsedown();            
        $this->_body = $Parsedown->text($this->_body);
        return $this;
    }
}