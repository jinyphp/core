<?php
/*
 * This file is part of the jinyPHP package.
 *
 * (c) hojinlee <infohojin@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Jiny\Core\Base;

class File
{
    /**
     * 운영체제를 확인합니다.
     */
    public static function os()
    {
        if(DIRECTORY_SEPARATOR == "/") return "linux"; else return "windows";
    }


    /**
     * 운영체제에 맞게 path를 재조정합니다.
     */
    public static function osPath($path)
    {
        return str_replace("/", DS, $path);
    }


    /**
     * 
     */
    public static function path($path)
    {
        $path = str_replace("/", DS, $path);
        $path = str_replace("\\", DS, $path);
        return $path;
    }


    /**
     * 파일을 복사합니다.
     * 대상 디렉토리는 자동으로 생성을 합니다.
     */
    public static function copy($src, $dst)
    {
        if (file_exists($src)) {
            $path = pathinfo($dst);
            self::mkdir($path['dirname']);
            
            // 윈도우의 경의 파일복사, 리눅스의 경우 심볼링크 처리
            copy($src,$dst);
            //if(self::os() == "windows") copy($src,$dst); else symlink($src, $dst);
            
            return true;
        } else {
            return false;
        }
    }


    /**
     * 디렉토리를 생성합니다.
     * path가 있는 경우 순차적으로 생성을 합니다.
     */
    public static function mkdir($dir)
    {
        $dir = str_replace("/", DIRECTORY_SEPARATOR, $dir);
        // echo $dir."<br>";
        $arr = explode(DIRECTORY_SEPARATOR, $dir );
        $path = "";

        //print_r($arr);

        foreach ($arr as $name) {
            
            //echo $name;
            if(!$name || $name == "." || $name == "..") {
                //echo "continue $name <br>";
                continue;
            } else {
                $path .= $name;
                if (!is_dir($path)) {
                    if(\mkdir($path)) {
                        //echo "생성 = ".$path."<br>";
                    } else {
                        //echo $path." 디렉토리를 생성할 수 없습니다.<br>";
                    }
                }
                //echo "path=".$path."<br>";
                $path .= DIRECTORY_SEPARATOR;
            }
            
        }
    }


    /**
     * 디렉토리의 목록을 읽어옵니다.
     * 배열값으로 받습니다.
     */
    public static function dir($path)
    {
        // 디렉토리 목록을 배열로 가지고 옵니다.       
        $dir = [];
        foreach (scandir($path) as $value) {
            // . .. 은 제외합니다.
            if($value == ".") continue;
            if($value == "..") continue;
            $dir[] = $value;
        }
        return $dir;
    }


    /**
     * 디렉토리 안에 지정한 파일명이 있는지를 검사합니다.
     */
    public function isNameDir($name, $dir)
    {

    }

    /**
     * 
     */
}
