<?php
namespace NetDriver\Http;

use NetDriver\Enum\EnumHttpMethod;
use NetDriver\NetDriverInterface;

class HttpPostRequest extends HttpRequest
{
    /**
     * HttpRequest constructor.
     *
     * @param NetDriverInterface $driver
     * @param string $url
     * @param array $data
     * @param array $options
     */
    public function __construct($driver, $url, array $data, array $options = [])
    {
        parent::__construct($driver, EnumHttpMethod::GET, $url, $data, $options);
    }
}