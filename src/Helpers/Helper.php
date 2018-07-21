<?php



if (! function_exists('windows_os')) {

    function windows_os()
    {
        return strtolower(substr(PHP_OS, 0, 3)) === 'win';
    }
}
