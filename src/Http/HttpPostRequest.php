<?php
namespace NetDriver\Http;

use NetDriver\Enum\EnumHttpMethod;
use NetDriver\NetDriverInterface;

class HttpPostRequest extends HttpRequest
{
    /** @var array */
    protected $post_fields;

    /**
     * HttpRequest constructor.
     *
     * @param NetDriverInterface $driver
     * @param string $url
     * @param array $post_fields
     * @param array $options
     */
    public function __construct($driver, $url, array $post_fields, array $options = [])
    {
        parent::__construct($driver, EnumHttpMethod::POST, $url, $options);

        $this->post_fields = $post_fields;
    }

    /**
     * @return string
     */
    public function getPostFields()
    {
        return http_build_query($this->post_fields, "", "&");
    }
}