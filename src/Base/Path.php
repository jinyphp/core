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

class Path
{
    /**
     * 
     */
    public static function check($path)
    {
        return str_replace("/", DIRECTORY_SEPARATOR, $path);
    } 


    /**
     * 패스경로를 추가합니다.
     */
    public static function append($dst, $src)
    {

        $dst = self::check($dst); //str_replace("/", DIRECTORY_SEPARATOR, $dst);
        $src = self::check($src); //str_replace("/", DIRECTORY_SEPARATOR, $src);

        $dst = rtrim($dst, DIRECTORY_SEPARATOR);
        $dst = explode(DIRECTORY_SEPARATOR, $dst);

        // 배열을 반대로 뒤집기
        $dst = \array_reverse($dst);

        $src = explode(DIRECTORY_SEPARATOR, trim($src, DIRECTORY_SEPARATOR));
        for ($i=0, $j=0;$i<count($src);$i++) {
            if($src[$i] == ".") unset($src[$i]);
            else if($src[$i] == "..") {
                unset($src[$i]);
                unset($dst[$j++]);
            }
        }

        // 원상태 복귀후, 배열을 결합합니다.
        $dst = \array_reverse($dst);
        $path = \array_merge($dst,$src);
        
        return \implode(DS,$path);
    }


    /**
     * 
     */
    public static function url($path)
    {
        return str_replace(DIRECTORY_SEPARATOR, "/", $path);
    }

    /**
     * 
     */
}
