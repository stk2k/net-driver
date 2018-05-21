<?php
use NetDriver\Enum\EnumEvent;
use NetDriver\Http\HttpGetRequest;
use NetDriver\Http\HttpRequest;
use NetDriver\Http\HttpResponse;
use NetDriver\NetDriver\AbstractNetDriver;
use NetDriver\NetDriverHandleInterface;

class ConcreteAbstractNetDriver extends AbstractNetDriver
{
    public function newHandle()
    {
    }
    public function sendRequest(NetDriverHandleInterface $handle, HttpRequest $request)
    {
    }
}

class AbstractNetDriverTest extends PHPUnit_Framework_TestCase
{
    /**
     * @throws \NetDriver\Exception\DeflateException
     */
    public function testListen()
    {
        $driver = new ConcreteAbstractNetDriver();

        ob_start();
        $driver->fireOnSendingRequest(new HttpGetRequest($driver, 'http://example.com'));
        $this->assertSame('', ob_get_clean());

        $driver->listen(EnumEvent::REQUEST, function(HttpRequest $request){
            $this->assertSame('http://example.com', $request->getUrl());
            echo '.';
        });
        ob_start();
        $driver->fireOnSendingRequest(new HttpGetRequest($driver, 'http://example.com'));
        $this->assertSame('.', ob_get_clean());

        ob_start();
        $driver->fireOnReceivedVerbose('', '', '');
        $this->assertSame('', ob_get_clean());

        ob_start();
        $driver->fireOnReceivedResponse(new HttpResponse(0, ''));
        $this->assertSame('', ob_get_clean());
    }
    public function testFireOnReceivedVerbose()
    {
        $driver = new ConcreteAbstractNetDriver();

        ob_start();
        $driver->fireOnReceivedVerbose('foo', 'bar', 'baz');
        $this->assertSame('', ob_get_clean());

        $driver->listen(EnumEvent::REQUEST, function(){
            echo '.';
        });

        ob_start();
        $driver->fireOnReceivedVerbose('foo', 'bar', 'baz');
        $this->assertSame('', ob_get_clean());

        $driver = new ConcreteAbstractNetDriver();

        ob_start();
        $driver->fireOnReceivedVerbose('foo', 'bar', 'baz');
        $this->assertSame('', ob_get_clean());

        $driver->listen(EnumEvent::RESPONSE, function(){
            echo '.';
        });

        ob_start();
        $driver->fireOnReceivedVerbose('foo', 'bar', 'baz');
        $this->assertSame('', ob_get_clean());

        $driver = new ConcreteAbstractNetDriver();

        ob_start();
        $driver->fireOnReceivedVerbose('foo', 'bar', 'baz');
        $this->assertSame('', ob_get_clean());

        $driver->listen(EnumEvent::VERBOSE, function($strerr, $header, $output){
            echo "$strerr/$header/$output";
        });

        ob_start();
        $driver->fireOnReceivedVerbose('foo', 'bar', 'baz');
        $this->assertSame('foo/bar/baz', ob_get_clean());
    }
}