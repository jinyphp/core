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

class Arr
{
    /**
     * 
     */
    public static function append($src, $arr)
    {
        foreach ($arr as $k => $value) {
            $src[$k] = $value;
        }
    }

    /**
     * 
     */
}