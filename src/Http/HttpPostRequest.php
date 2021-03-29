<?php
declare(strict_types=1);

namespace Stk2k\NetDriver\Http;

use Stk2k\NetDriver\Enum\EnumHttpMethod;
use Stk2k\NetDriver\NetDriverInterface;

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
    public function __construct(NetDriverInterface $driver, string $url, array $post_fields, array $options = [])
    {
        parent::__construct($driver, EnumHttpMethod::POST, $url, $options);

        $this->post_fields = $post_fields;
    }

    /**
     * @return string
     */
    public function getPostFields() : string
    {
        return http_build_query($this->post_fields);
    }
}