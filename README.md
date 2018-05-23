NetDriver, HTTP client with plug-in system
=======================

## Description

NetDriver is a PHP library which provides sending HTTP requests.

## Feature

- Pluggable. You can easily replace other HTTP access library if you want.
- Simple interfaces.
- Callback events. You can customize your request before it is exectuted. Also you can get detail logs in sending request.
- PSR-3 Logger acceptable.
- cURL/PHP(file_get_contents) net drivers are bundled.

## Usage

1. Create net driver object(CurlNetDriver or PhpNetDriver are available).
1. Create handle from net driver object.
1. Create request object(HttpGetRequst/HttpPostRequest/JsonPostRequest are available).
1. Call sendRequest method of net driver object and receive response object.

## Demo

### Example 1: sending HTTP request by cURL

```php
use NetDriver\NetDriver\Curl\CurlNetDriver;

$driver = new CurlNetDriver();
$request = new HttpGetRequest($driver, 'http://sazysoft.com/test/');
$handle = $driver->newHandle();

try{
    $response = $driver->sendRequest($handle, $request);
    echo $response->getBody();
}
catch(NetDriverException $e)
{
    // error handling here
}
```

### Example 2: sending HTTP request by file_get_contents

```php
use NetDriver\NetDriver\Php\PhpNetDriver;

$driver = new PhpNetDriver();
$request = new HttpGetRequest($driver, 'http://sazysoft.com/test/');
$handle = $driver->newHandle();

try{
    $response = $driver->sendRequest($handle, $request);
    echo $response->getBody();
}
catch(NetDriverExceptionInterface $e)
{
    // error handling here
}
```

### Example 3: post request

```php
$driver = new CurlNetDriver();
$request = new HttpPostRequest($driver, 'http://sazysoft.com/test/', ['foo' => 'bar', 'baz' => 'qux']);
$handle = $driver->newHandle();

try{
    $response = $driver->sendRequest($handle, $request);
    echo $response->getBody();
}
catch(NetDriverExceptionInterface $e)
{
    // error handling here
}
```

### Example 4: request callback

```php
$driver = new CurlNetDriver();
$request = new HttpGetRequest($driver, 'http://sazysoft.com/test/');
$handle = $driver->newHandle();

try{
    $driver->listen('request', function(HttpRequest $request){
        $request->addHttpHeader('Content-Type', 'text/json');       // set content type to text/json
        return $request;    // replace old request to new one
    });
    $response = $driver->sendRequest($handle, $request);
    echo $response->getBody();
}
catch(NetDriverExceptionInterface $e)
{
    // error handling here
}
```

### Example 5: verbose callback

```php
$driver = new CurlNetDriver();
$request = new HttpGetRequest($driver, 'http://sazysoft.com/test/');
$handle = $driver->newHandle();

try{
    $driver->setVerbose(true);          // curl netdriver can write debug info to output
    $driver->listen('verbose', function($strerr, $header, $output){
        echo '----[ strerr ]----' . PHP_EOL;
        echo $strerr . PHP_EOL;
        echo '----[ header ]----' . PHP_EOL;
        echo $header . PHP_EOL;
        echo '----[ output ]----' . PHP_EOL;
        echo $output . PHP_EOL;
    });
    $response = $driver->sendRequest($handle, $request);
    echo '----[ response ]----' . PHP_EOL;
    echo $response->getBody();
}
catch(NetDriverExceptionInterface $e)
{
    // error handling here
}
```

## Requirement

PHP 5.5 or later


## Installing NetDriver

The recommended way to install NetDriver is through
[Composer](http://getcomposer.org).

```bash
composer require stk2k/net-driver
```

After installing, you need to require Composer's autoloader:

```php
require 'vendor/autoload.php';
```

## License
[MIT](https://github.com/stk2k/net-driver/blob/master/LICENSE)

## Author

[stk2k](https://github.com/stk2k)

## Disclaimer

This software is no warranty.

We are not responsible for any results caused by the use of this software.

Please use the responsibility of the your self.
