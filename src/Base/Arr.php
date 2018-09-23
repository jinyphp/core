<?php
namespace Jiny\Core\Base;

class Arr
{
    public static function append($src, $arr)
    {
        foreach ($arr as $k => $value) {
            $src[$k] = $value;
        }
    }

}