<?php

namespace Jiny\Core;

class BASE
{
    public function sysOut($msg)
    {
        // \TimeLog::set(__METHOD__);
        echo $msg."<br>";
    }
}