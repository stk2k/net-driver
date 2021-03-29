<?php
declare(strict_types=1);

namespace Stk2k\NetDriver\Test\Drivers;

use PHPUnit\Framework\TestCase;

use Stk2k\NetDriver\Enum\EnumEvent;
use Stk2k\NetDriver\Http\HttpGetRequest;
use Stk2k\NetDriver\Http\HttpRequest;
use Stk2k\NetDriver\Http\HttpResponse;
use Stk2k\NetDriver\Exception\DeflateException;

class AbstractNetDriverTest extends TestCase
{
    /**
     * @throws DeflateException
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