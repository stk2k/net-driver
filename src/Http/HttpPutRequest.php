<?php
namespace NetDriver\Http;

use NetDriver\Enum\EnumHttpMethod;
use NetDriver\NetDriverInterface;

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
    public function __construct($driver, $url, array $put_fields, array $options = [])
    {
        parent::__construct($driver, EnumHttpMethod::PUT, $url, $options);

        $this->put_fields = $put_fields;
    }

    /**
     * @return string
     */
    public function getPutFields()
    {
        return http_build_query($this->put_fields, "", "&");
    }
}