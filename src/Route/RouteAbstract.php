<?php

namespace Jiny\Core\Route;

abstract class RouteAbstract
{
    public $Application;
    
    abstract public function route();
}