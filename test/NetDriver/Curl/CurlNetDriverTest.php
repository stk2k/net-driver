<?php
use NetDriver\NetDriver\Curl\CurlNetDriver;
use NetDriver\Http\HttpGetRequest;
use NetDriver\Exception\NetDriverException;

class CurlNetDriverTest extends PHPUnit_Framework_TestCase
{
    const TEST_URL = 'http://example.com';
    
    public function testSendRequest()
    {
        $driver = new CurlNetDriver();
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

}