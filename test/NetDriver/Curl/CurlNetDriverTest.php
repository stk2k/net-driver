<?php
use NetDriver\NetDriver\Curl\CurlNetDriver;
use NetDriver\Http\HttpGetRequest;
use NetDriver\Exception\NetDriverExceptionInterface;
use NetDriver\Exception\TimeoutException;
use NetDriver\Enum\EnumRequestOption;

class CurlNetDriverTest extends PHPUnit_Framework_TestCase
{
    const TEST_URL1 = 'http://sazysoft.com/test/';
    const TEST_URL2 = 'http://sazysoft.com/test/timeout.php';
    
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
}