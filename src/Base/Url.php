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