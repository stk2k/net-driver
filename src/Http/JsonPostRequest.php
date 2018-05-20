<?php
namespace NetDriver\Http;

use NetDriver\NetDriverInterface;

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
    public function __construct($driver, $url, array $data, array $options = [])
    {
        parent::__construct($driver, $url, $data, $options);
    }

    /**
     * @return array
     */
    public function getData()
    {
        return json_encode($this->post_fields, JSON_FORCE_OBJECT);
    }
}