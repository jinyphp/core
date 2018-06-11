<?php

namespace Jiny\Core\Views;

use \Jiny\Core\Registry\Registry;
use Liquid\Template;

trait Liquid
{
    public function Liquid($body, $data)
    {
        $this->$Liquid->parse($body);
        return $this->$Liquid->render($data);
    }
}