<?php
declare(strict_types=1);

namespace Stk2k\NetDriver\Http;

use Stk2k\NetDriver\Enum\EnumHttpMethod;
use Stk2k\NetDriver\NetDriverInterface;

class HttpPutRequest extends HttpRequest
{
    /** @var array */
    protected $put_fields;

    /**
     * HttpRequest constructor.
     *
     * @param NetDriverInterface $driver
     * @param string $url
     * @param array $put_fields
     * @param array $options
     */
    public function __construct(NetDriverInterface $driver, string $url, array $put_fields, array $options = [])
    {
        parent::__construct($driver, EnumHttpMethod::PUT, $url, $options);

        $this->put_fields = $put_fields;
    }

    /**
     * @return string
     */
    public function getPutFields() : string
    {
        return http_build_query($this->put_fields);
    }
}