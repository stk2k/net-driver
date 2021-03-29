<?php
declare(strict_types=1);

namespace Stk2k\NetDriver\Drivers\Curl;

use Stk2k\NetDriver\Exception\DeflateException;
use Stk2k\NetDriver\Http\ResponseHeaders;
use Stk2k\NetDriver\Http\HttpResponse;

class CurlResponse extends HttpResponse
{
    /**
     * Constructs grasshopper object
     *
     * @param array $info
     * @param string $content
     * @throws DeflateException
     */
    public function __construct(array $info, string $content)
    {
        // get header from content
        $header = substr($content, 0, $info["header_size"]);
        $header = strtr($header, ["\r\n"=>"\n","\r"=>"\n"]);

        // parser header
        $headers = array_filter(explode("\n",$header));
        $response_headers = new ResponseHeaders($headers);

        // get body from content
        $body = substr($content, $info["header_size"]);

        // status code
        $status_code = isset($info['http_code']) ? $info['http_code'] : -1;

        parent::__construct($status_code, $body, $response_headers);
    }
}