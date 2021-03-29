<?php
declare(strict_types=1);

namespace Stk2k\NetDriver\Http;

use Stk2k\NetDriver\NetDriverInterface;

class JsonPostRequest extends HttpPostRequest
{
    /**
     * HttpRequest constructor.
     *
     * @param NetDriverInterface $driver
     * @param string $url
     * @param array $data
     * @param array $options
     */
    public function __construct(NetDriverInterface $driver, string $url, array $data, array $options = [])
    {
        parent::__construct($driver, $url, $data, $options);
    }

    /**
     * @return string
     */
    public function getPostFields() : string
    {
        return json_encode($this->post_fields, JSON_FORCE_OBJECT);
    }
}