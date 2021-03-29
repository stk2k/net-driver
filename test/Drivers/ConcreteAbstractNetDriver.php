<?php
declare(strict_types=1);

namespace Stk2k\NetDriver\Test\Drivers;

use Stk2k\NetDriver\Http\HttpRequest;
use Stk2k\NetDriver\Http\HttpResponse;
use Stk2k\NetDriver\Drivers\AbstractNetDriver;
use Stk2k\NetDriver\Drivers\Php\PhpHandle;
use Stk2k\NetDriver\NetDriverHandleInterface;

class ConcreteAbstractNetDriver extends AbstractNetDriver
{
    public function newHandle() : NetDriverHandleInterface
    {
        return new PhpHandle();
    }

    public function sendRequest(NetDriverHandleInterface $handle, HttpRequest $request) : HttpResponse
    {
        return new HttpResponse(0, "");
    }

}
