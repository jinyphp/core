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

class BaseArray
{
    /**
     * 
     */
    public static function append($src, $arr)
    {
        echo __METHOD__."<br>";

        foreach ($arr as $k => $value) {
            $src[$k] = $value;
        }
    }


    /**
     * 
     */
    public static function path($arr)
    {
        if(count($arr)){
            $string = DIRECTORY_SEPARATOR;
            foreach($arr as $name){
                $string .= $name.DIRECTORY_SEPARATOR;
            }

            return rtrim($string, DIRECTORY_SEPARATOR);
        }
    }

    /**
     * 
     */
}