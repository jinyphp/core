<?php
namespace Jiny\Core\Http;

class Request
{
    private $App;

    /**
     * 초기화
     * 인스턴스 주입
     */
    public function __construct($app)
    {
        $this->App = $app;
    }

    /**
     * REQUEST URL에서 도메인 이후 부분의 반환합니다.
     * 예를들어 URL 요청을 `http://domain.com/foo/bar`와 같이 입력을 했다면 request의 `path` 메소드는 도메인 이후 부분의 `foo/bar`를 반환 합니다.
     */
    public function path()
    {
        return $_SERVER['REQUEST_URI'];
    }

    public function is($url)
    {

    }

    /**
     * 쿼리스트링을 제외한 모든 REQUEST_URI를 반환합니다.
     */
    public function url()
    {
        return explode('?', $_SERVER['REQUEST_URI'])[0];
    }

    public function fullUrl()
    {

    }

    /**
     * HTTP 접속 매소드를 확인합니다.
     */
    public function method()
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    /**
     * 
     */
}