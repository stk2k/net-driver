<?php
namespace NetDriver\Util;

use NetDriver\Exception\DeflateException;

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
    public static function deflateBody($body, $content_encoding)
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
                break;
            default:
                return $body;
                break;
        }
    }

}