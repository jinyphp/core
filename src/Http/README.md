# HTTP Request
url을 통하여 입력된 HTTP 요청을 처리할 수 있습니다.


## 메서드
다양한 메서드를 제공합니다.

### path()
입력된 url에서 도메인을 제외한 경로를 출력합니다.

```php
$boot->path();
```


### 매소드 확인
기본적 HTML from은 GET/POST 2가지 매소드를 지원합니다. 하지만 HTTP는 이 외에도 다양한 매소드 들이 존재합니다.

최신에는 RESTFull 형식의 CRUD 매소드를 처리합니다.

```php
echo "매소드 = ".$boot->method()."<br>";
```

* Create : Post
* Read : get
* Update : put
* Delete : delete
