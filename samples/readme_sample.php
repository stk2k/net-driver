<?php
require_once dirname(dirname(__FILE__)) . '/vendor/autoload.php';

use Stk2k\NetDriver\Http\HttpRequest;
use Stk2k\NetDriver\Http\HttpGetRequest;
use Stk2k\NetDriver\Http\HttpPostRequest;
use Stk2k\NetDriver\Drivers\Curl\CurlNetDriver;
use Stk2k\NetDriver\Drivers\Php\PhpNetDriver;
use Stk2k\NetDriver\Exception\NetDriverExceptionInterface;

echo PHP_EOL . '===========[ Example 1: sending HTTP request by cURL ]===========' . PHP_EOL;

$driver = new CurlNetDriver();
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

echo PHP_EOL . '===========[ Example 2: sending HTTP request by file_get_contents ]===========' . PHP_EOL;

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

echo PHP_EOL . '===========[ Example 3: post request ]===========' . PHP_EOL;

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

echo PHP_EOL . '===========[ Example 4: request callback ]===========' . PHP_EOL;

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

echo PHP_EOL . '===========[ Example 5:: verbose callback  ]===========' . PHP_EOL;

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
