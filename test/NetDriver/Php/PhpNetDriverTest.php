<?php

use NetDriver\Enum\EnumProxyOption;
use NetDriver\Enum\EnumRequestOption;
use NetDriver\Exception\NetDriverExceptionInterface;
use NetDriver\Exception\TimeoutException;
use NetDriver\Http\HttpProxyGetRequest;
use NetDriver\NetDriver\Php\PhpNetDriver;
use NetDriver\Http\HttpGetRequest;
use NetDriver\Exception\NetDriverException;

class PhpNetDriverTest extends PHPUnit_Framework_TestCase
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
/*
    public function testProxy()
    {
        $driver = new PhpNetDriver();
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
        catch(NetDriverExceptionInterface $e)
        {
            $this->fail();
        }
    }
*/
}