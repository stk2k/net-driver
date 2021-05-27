<?php
declare(strict_types=1);

namespace Stk2k\NetDriver\Util;

use DOMDocument;

class CharsetUtil
{
    /**
     * Convert encoding
     * @param string $str
     * @param string $html_charset
     * @param string $to_encoding
     * @return string
     */
    public static function convertEncoding(string $str, ?string $html_charset, string $to_encoding = 'UTF-8') : string
    {
        if (empty($html_charset)){
            return $str;
        }

        $php_encoding = self::getPhpEncoding($html_charset);
        $from_encoding = $php_encoding ? $php_encoding : 'auto';

        $str = ( $from_encoding == $to_encoding ) ? $str : mb_convert_encoding( $str, $to_encoding, $from_encoding );

        return $str;
    }

    /**
     * detect charset
     *
     * @param string $body
     * @param string $content_type
     * @param string $default_charset
     *
     * @return string
     */
    public static function detectCharset(string $body, string $content_type, string $default_charset) : ?string
    {
        // get character encoding from Content-Type header
        preg_match( '@([\w/+]+)(;\s+charset=(\S+))?@i', $content_type, $matches );
        $charset = isset($matches[3]) ? $matches[3] : $default_charset;

        $php_encoding = $charset ? self::getPhpEncoding($charset) : null;
        if ( !$php_encoding ){
            $html_encoded = mb_convert_encoding( strtolower($body), 'HTML-ENTITIES', 'UTF-8' );
            $doc = new DOMDocument();
            @$doc->loadHTML( $html_encoded );
            $elements = $doc->getElementsByTagName( "meta" );

            for($i = 0; $i < $elements->length; $i++) {
                $e = $elements->item($i);

                // find like: <meta charset="utf-8"/>
                $node = $e->attributes->getNamedItem("charset");
                if($node){
                    $charset = $node->nodeValue;
                    $php_encoding = self::getPhpEncoding($charset);
                    break;
                }

                // find like: <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
                $node = $e->attributes->getNamedItem("http-equiv");
                if ( $node ){
                    if ( strcasecmp($node->nodeValue, 'content-type') == 0 ){
                        $node = $e->attributes->getNamedItem("content");
                        if( $node ){
                            if ( preg_match('/[; ]charset ?= ?([A-Za-z0-9\-_]+)/', $node->nodeValue, $m) ){
                                $charset = $m[1];
                                $php_encoding = self::getPhpEncoding($charset);
                                break;
                            }
                        }
                    }
                }
            }
        }

        return $php_encoding;
    }

    /**
     * Get PHP character encoding
     *
     * @param $html_charset
     *
     * @return string
     */
    private static function getPhpEncoding(string $html_charset) : string
    {
        $php_encoding = null;

        switch( strtolower($html_charset) ){
            case 'sjis':
            case 'sjis-win':
            case 'shift_jis':
            case 'shift-jis':
            case 'ms_kanji':
            case 'csshiftjis':
            case 'x-sjis':
                $php_encoding = 'sjis-win';
                break;
            case 'euc-jp':
            case 'cseucpkdfmtjapanese':
                $php_encoding = 'EUC-JP';
                break;
            case 'jis':
                $php_encoding = 'jis';
                break;
            case 'iso-2022-jp':
            case 'csiso2022jp':
                $php_encoding = 'ISO-2022-JP';
                break;
            case 'iso-2022-jp-2':
            case 'csiso2022jp2':
                $php_encoding = 'ISO-2022-JP-2';
                break;
            case 'utf-8':
            case 'csutf8':
                $php_encoding = 'UTF-8';
                break;
            case 'utf-16':
            case 'csutf16':
                $php_encoding = 'UTF-16';
                break;
            default:
                if ( strpos($html_charset,'sjis') ){
                    $php_encoding = 'SJIS';
                }
                break;
        }

        return $php_encoding;
    }


}
