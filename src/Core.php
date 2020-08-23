<?php
/*
 * This file is part of the jinyPHP package.
 *
 * (c) hojinlee <infohojin@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Jiny\Core;

class Core 
{
    // 싱글턴 초기화
    use \Jiny\Petterns\Singleton;
    private function init()
    {

    }
    
    public function __construct()
    {
        echo __CLASS__;
    }

    
    

}