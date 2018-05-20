<?php
use NetDriver\Exception\NetDriverException;
use NetDriver\NetDriver\Php\PhpNetDriver;
use NetDriver\Http\HttpGetRequest;
use NetDriver\Http\HttpResponse;

class HttpListenerTest extends PHPUnit_Framework_TestCase
{
    const TEST_URL = 'http://example.com';
    
    public function testSendRequest()
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