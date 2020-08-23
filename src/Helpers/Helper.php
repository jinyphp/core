<?php
/*
 * This file is part of the jinyPHP package.
 *
 * (c) hojinlee <infohojin@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
use \Jiny\Core\Registry\Registry;




if (! function_exists('print_ln')) {
    function print_ln($msg)
    {
        print($msg."\n");
    }
}

if (! function_exists('println')) {
    function println($msg)
    {
        print($msg."\n");
    }
}

if (! function_exists('print_br')) {
    function print_br($msg)
    {
        print($msg."<br>");
    }
}

if (! function_exists('printbr')) {
    function printbr($msg)
    {
        if (\is_array($msg) || \is_object($msg)) {
            echo "<pre>";
            echo "<div style='border:1px solid #ccc;padding:5px;'>";
            print_r($msg);
            echo "</div>";
            echo "</pre>";
        } else {
            print($msg."<br>");
        }
        
    }
}



if (! function_exists('windows_os')) {
    /**
     * 
     */
    function windows_os()
    {
        return strtolower(substr(PHP_OS, 0, 3)) === 'win';
    }
}


if (! function_exists('output')) {
    /**
     * 
     */
    function output($string)
    {
        echo nl2br($string);
    }
}


if (! function_exists('urlString')) {
    /**
     * 
     */
    function urlString() {        
        if ($Request = Registry::get("Request")) {
            return $Request->urlString();               
        } else {
            echo "인스턴스를 확인할 수 없습니다.";
        }
    }
}


/**
 * locale 언어를 반환합니다.
 */
if (! function_exists('language')) {

    function language() {        
        if ($Request = Registry::get("Request")) {
            return $Request->_language;               
        } else {
            echo "인스턴스를 확인할 수 없습니다.";
        }
    }
}


/**
 * locale 국가를 반환합니다.
 */
if (! function_exists('country')) {

    function country() {        
        if ($Request = Registry::get("Request")) {
            return $Request->_country;               
        } else {
            echo "인스턴스를 확인할 수 없습니다.";
        }
    }
}



require "Jiny.php";


