<?php
declare(strict_types=1);

namespace Stk2k\NetDriver\Http;

use Stk2k\NetDriver\Exception\DeflateException;
use Stk2k\NetDriver\Exception\JsonFormatException;
use Stk2k\NetDriver\Util\HttpCompressionUtil;
use Stk2k\NetDriver\Util\CharsetUtil;

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
     * @param ResponseHeaders|null $headers
     *
     * @throws DeflateException
     */
    public function __construct(int $status_code, string $body, ResponseHeaders $headers = null)
    {
        // deflate compressed data
        $body = $headers ? HttpCompressionUtil::deflateBody($body, $headers->getContentEncoding()) : '';

        // detect character encoding
        $this->charset = $headers ? CharsetUtil::detectCharset($body, $headers->getContentType(), $headers->getCharset()) : '';

        $this->status_code = $status_code;
        $this->body = CharsetUtil::convertEncoding($body, $this->charset);
        $this->headers = $headers;
    }

    /**
     * Get status code
     *
     * @return int
     */
    public function getStatusCode() : int
    {
        return $this->status_code;
    }

    /**
     * Get response body
     *
     * @return string
     */
    public function getBody() : string
    {
        return $this->body;
    }

    /**
     * Get response headers
     *
     * @return ResponseHeaders
     */
    public function getHeaders() : ResponseHeaders
    {
        return $this->headers;
    }

    /**
     * Decode as JSON
     *
     * @return array|object
     *
     * @throws JsonFormatException
     * @noinspection PhpMissingReturnTypeInspection
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