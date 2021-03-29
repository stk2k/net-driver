<?php
declare(strict_types=1);

namespace Stk2k\NetDriver\Test\Http;

use PHPUnit\Framework\TestCase;
use Stk2k\NetDriver\Drivers\Php\PhpNetDriver;
use Stk2k\NetDriver\Http\JsonPostRequest;

final class JsonPostRequestTest extends TestCase
{
    const TEST_URL = 'http://example.com';

    public function testGetPostFields()
    {
        $driver = new PhpNetDriver();
        $data = [
            'name' => 'apple',
            'price' => 123,
        ];
        $request = new JsonPostRequest($driver, self::TEST_URL, $data);

        $this->assertEquals('{"name":"apple","price":123}', $request->getPostFields());
    }


}