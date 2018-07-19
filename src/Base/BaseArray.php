<?php
namespace Jiny\Core\Base;

class BaseArray
{
    public static function append($src, $arr)
    {
        echo __METHOD__."<br>";

        foreach ($arr as $k => $value) {
            $src[$k] = $value;
        }
    }

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


}