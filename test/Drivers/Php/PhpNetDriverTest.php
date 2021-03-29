<?php
declare(strict_types=1);

namespace Stk2k\NetDriver\Test\Drivers\Php;

use PHPUnit\Framework\TestCase;

use Stk2k\NetDriver\Http\HttpResponse;
use Stk2k\NetDriver\Drivers\Php\PhpNetDriver;
use Stk2k\NetDriver\Http\HttpGetRequest;
use Stk2k\NetDriver\Exception\NetDriverException;

class PhpNetDriverTest extends TestCase
{
    const TEST_URL = 'http://example.com';

    const TEST_PROXY_SERVER  = 'sazysoft.com';
    const TEST_PROXY_PORT    = 8800;

    public function testSendRequest()
    {
        $driver = new PhpNetDriver();
        $request = new HttpGetRequest($driver, self::TEST_URL);
        $handle = $driver->newHandle();

        try{
            $response = $driver->sendRequest($handle, $request);

            $this->assertSame(200, $response->getStatusCode());
        }
        catch(NetDriverException $e)
        {
            $this->fail();
        }
    }

    public function testListen()
    {
        $driver = new PhpNetDriver();
        $request = new HttpGetRequest($driver, self::TEST_URL);
        $handle = $driver->newHandle();

        try{
            ob_start();
            $driver->listen('response', function(HttpResponse $response){
                echo $response->getHeaders()->getContentType();
            });
            $response = $driver->sendRequest($handle, $request);
            $echo = ob_get_clean();

            $this->assertSame(200, $response->getStatusCode());
            $this->assertSame('text/html', $echo);
        }
        catch(NetDriverException $e)
        {
            $this->fail();
        }
    }
}