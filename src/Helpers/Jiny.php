<?php
namespace jiny;


if (! function_exists('error')) {
    function error($msg)
    {
        print($msg."<br>");
    }
}


function coreType()
{
    $type = isset($_SERVER['CONTENT_TYPE']) ? $_SERVER['CONTENT_TYPE'] : null;
    if ($type == 'application/json') {
        return "api";
    } else {
        //if ($_SERVER['HTTP_CONTENT_TYPE'] == 'content-type: text/html') {
        return "html";
    }
}


function json_get_object($filename)
{
    if(\file_exists($filename)) {
        return \json_decode(\file_get_contents($filename));
    }
}

function json_get_array($filename)
{
    if(\file_exists($filename)) {
        return \json_decode(\file_get_contents($filename), true);
    }
}


function autoload_path($name)
{
    global $autoload;
    $path = $autoload->findFile($name);
    $path = str_replace("/..", DIRECTORY_SEPARATOR, $path);
    $path = str_replace(['/','\\','\\\\'], DIRECTORY_SEPARATOR, $path);
    return $path;
}

/*
function execute($obj, $param=null)
{
    echo __FUNCTION__;
    // exit;

    // 익명함수
    if (is_callable($obj)) {
        // return $obj($param);
    } else
    // 객체 
    if (is_object($obj)) {
        echo "object";
        exit;
        // return $obj->main($param);
    } else if(is_array($obj) && is_object($obj[0]) && is_string($obj[1])) {
        echo "main";
        exit;
        // return call_user_func_array($obj, $prams);
    } else if(is_string($obj)) {
        echo "없음";
        // $class = new $obj;
        //return call_user_func_array([$class, "main"], $prams);
    }
    echo "1111";
}
*/

namespace jiny\core;

function type()
{
    $type = isset($_SERVER['CONTENT_TYPE']) ? $_SERVER['CONTENT_TYPE'] : null;
    // echo "접속유형=".$type;

    if ($type == 'application/json') {
        return "json";
    } else {
        //if ($_SERVER['HTTP_CONTENT_TYPE'] == 'content-type: text/html') {
        return "html";
    }
}

/**
 * jinyPHP 응용서비스의 객체를 생성합니다.
 */
function bootstart($map=null)
{
    $type = isset($_SERVER['CONTENT_TYPE']) ? $_SERVER['CONTENT_TYPE'] : null;
    // echo "contentType=".$_SERVER['CONTENT_TYPE'];
    // 폼입력 : application/x-www-form-urlencoded

    if ($type == 'application/json') {
        return new \Jiny\API\ApplicationJson();
    } 
    
    // Web 응용 프로그램
    return new \Jiny\App\Application();
}
