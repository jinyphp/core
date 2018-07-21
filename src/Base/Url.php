<?php
namespace Jiny\Core\Base;

class Url
{
    /**
     * url 경로를 파일path로 변경을 합니다.
     */
    public static function path($url)
    {
        return str_replace("/", DIRECTORY_SEPARATOR, $path);
    }
}