<?
namespace Jiny\Core;

class _String
{
    private $str;
    public function __construct($str)
    {
        $this->str = $str;
    }

    public split($token=";")
    {
        return explode($token, $this->str);
    }
}