<?php
declare(strict_types=1);

namespace Stk2k\NetDriver\Test\Drivers\Curl;

use PHPUnit\Framework\TestCase;

use Stk2k\NetDriver\Drivers\Curl\CurlNetDriver;
use Stk2k\NetDriver\Http\HttpGetRequest;
use Stk2k\NetDriver\Exception\NetDriverExceptionInterface;
use Stk2k\NetDriver\Exception\TimeoutException;
use Stk2k\NetDriver\Enum\EnumRequestOption;

class CurlNetDriverTest extends TestCase
{
    const TEST_URL1 = 'http://sazysoft.com/test/';
    const TEST_URL2 = 'http://sazysoft.com/test/timeout.php';

    const TEST_PROXY_SERVER  = 'sazysoft.com';
    const TEST_PROXY_PORT    = 8800;
    
    public function testSendRequest()
    {
        $driver = new CurlNetDriver();
        $request = new HttpGetRequest($driver, self::TEST_URL1);
        $handle = $driver->newHandle();

        try{
            $response = $driver->sendRequest($handle, $request);

            $this->assertSame(200, $response->getStatusCode());
        }
        catch(NetDriverExceptionInterface $e)
        {
            $this->fail();
        }
    }

    public function testTimeout()
    {
        $driver = new CurlNetDriver();
        $request = new HttpGetRequest($driver, self::TEST_URL2, [
            EnumRequestOption::TOTAL_TIMEOUT_MS => 1000,
        ]);
        $handle = $driver->newHandle();

        try{
            $driver->sendRequest($handle, $request);

            $this->assertTrue(true, 'success!');
        }
        catch(TimeoutException $e)
        {
            $this->assertSame(self::TEST_URL2, $e->getRequest()->getUrl());
            $this->assertSame('GET', $e->getRequest()->getMethod());
        }
        catch(NetDriverExceptionInterface $e)
        {
            $this->fail();
        }
    }
/*
    public function testProxy()
    {
        $driver = new CurlNetDriver();
        $request = new HttpProxyGetRequest($driver, "https://yahoo.co.jp", [
            EnumRequestOption::PROXY_OPTIONS => [
                EnumProxyOption::PROXY_SERVER => self::TEST_PROXY_SERVER,
                EnumProxyOption::PROXY_PORT => self::TEST_PROXY_PORT,
            ],
        ]);
        $handle = $driver->newHandle();

        try{
            $driver->sendRequest($handle, $request);
        }
        catch(TimeoutException $e)
        {
            $this->assertSame(self::TEST_URL2, $e->getRequest()->getUrl());
            $this->assertSame('GET', $e->getRequest()->getMethod());
        }
        catch(NetDriverExceptionInterface $e)
        {
            $this->fail();
        }
    }
*/
}