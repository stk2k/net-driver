<?php
declare(strict_types=1);

namespace Stk2k\NetDriver\Http;

use Stk2k\NetDriver\Enum\EnumHttpMethod;
use Stk2k\NetDriver\NetDriverInterface;

class HttpGetRequest extends HttpRequest
{
    /**
     * HttpRequest constructor.
     *
     * @param NetDriverInterface $driver
     * @param string $url
     * @param array $options
     */
    public function __construct(NetDriverInterface $driver, string $url, array $options = [])
    {
        parent::__construct($driver, EnumHttpMethod::GET, $url, $options);
    }
}