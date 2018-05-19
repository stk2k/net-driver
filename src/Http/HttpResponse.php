<?php
namespace NetDriver\Http;

use NetDriver\Exception\JsonFormatException;

class HttpResponse
{
    /** @var string */
    private $body;

    /** @var int */
    private $status_code;

    /**
     * HttpResponse constructor.
     *
     * @param string $body
     * @param int $status_code
     */
    public function __construct($body, $status_code)
    {
        $this->body = $body;
        $this->status_code = $status_code;
    }

    /**
     * Get response body
     *
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Get status code
     *
     * @return int
     */
    public function getStatusCode()
    {
        return $this->status_code;
    }

    /**
     * Decode as JSON
     *
     * @return array|object
     *
     * @throws JsonFormatException
     */
    public function jsonDecode()
    {
        $json = json_decode($this->body, true);
        if ($json===null){
            throw new JsonFormatException($this->body);
        }
        return $json;
    }
}