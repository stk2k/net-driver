<?php
namespace NetDriver\Http;

use NetDriver\Exception\DeflateException;
use NetDriver\Exception\JsonFormatException;
use NetDriver\Util\HttpCompressionUtil;
use NetDriver\Util\CharsetUtil;

class HttpResponse
{
    /** @var int */
    private $status_code;

    /** @var string */
    private $body;

    /** @var ResponseHeaders */
    private $headers;

    /** @var string */
    private $charset;

    /**
     * HttpResponse constructor.
     *
     * @param int $status_code
     * @param string $body
     * @param ResponseHeaders $headers
     *
     * @throws DeflateException
     */
    public function __construct($status_code, $body, ResponseHeaders $headers)
    {
        // deflate compressed data
        $body = HttpCompressionUtil::deflateBody($body, $headers->getContentEncoding());

        // detect character encoding
        $this->charset = CharsetUtil::detectCharset($body, $headers->getContentType(), $headers->getCharset());

        $this->status_code = $status_code;
        $this->body = CharsetUtil::convertEncoding($body, $this->charset);
        $this->headers = $headers;
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
     * Get response body
     *
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Get response headers
     *
     * @return ResponseHeaders
     */
    public function getHeaders()
    {
        return $this->headers;
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