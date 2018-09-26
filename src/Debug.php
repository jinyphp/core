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

class Debug
{
    private static $_debug = FALSE;

    /**
     * 
     */
    public function on()
    {
        echo "디버거를 활성화 합니다.<br>";
        self::$_debug = TRUE;
    }


    /**
     * 
     */
    public function off()
    {
        echo "디버거를 비활성화 합니다.<br>";
        self::$_debug = FALSE;
    }


    /**
     * 
     */
    public function out($msg)
    {
        if (self::$_debug) {
            echo $msg."<br>";
        }        
    }


    /**
     * 
     */
    public function print_r($arr)
    {
        if (self::$_debug) {
            echo "<pre>";
            print_r($arr);
            echo "</pre>";
        }        
    }


    /**
     * 
     */
    public function var_dump($arr)
    {
        if (self::$_debug) {
            echo "<pre>";
            print_r($arr);
            echo "</pre>";
        }        
    }

    /**
     * 
     */
}