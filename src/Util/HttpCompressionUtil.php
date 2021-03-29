<?php
declare(strict_types=1);

namespace Stk2k\NetDriver\Util;

use Stk2k\NetDriver\Exception\DeflateException;

class HttpCompressionUtil
{
    /**
     * deflate body
     *
     * @param string $body
     * @param string $content_encoding
     *
     * @return string
     *
     * @throws DeflateException
     */
    public static function deflateBody(string $body, string $content_encoding) : string
    {
        switch($content_encoding){
            case 'gzip':
            case 'deflate':
            case 'compress':
                $body = @zlib_decode($body);
                if ( $body === FALSE ){
                    throw new DeflateException();
                }
                return $body;

            default:
                return $body;
        }
    }

}