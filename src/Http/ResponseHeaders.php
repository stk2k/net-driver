<?php
namespace NetDriver\Http;

use NetDriver\Enum\EnumHttpHeader;

class ResponseHeaders
{
    /** @var array */
    private $headers;

    /** @var int */
    private $status_code;

    /** @var string */
    private $reason_phrase;

    /** @var string */
    private $protocol;

    /** @var string */
    private $protocol_version;

    /** @var array */
    private $parsed;

    /**
     * ResponseHeaders constructor.
     *
     * @param array $headers
     */
    public function __construct(array $headers)
    {
        $this->headers = $headers;
        $this->parsed = [];

        $this->parse();
    }

    /**
     * @return int
     */
    public function getStatusCode()
    {
        return $this->status_code;
    }

    /**
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * @param string $key
     *
     * @return mixed
     */
    public function getHeader($key)
    {
        return isset($this->parsed[$key]) ? $this->parsed[$key] : '';
    }

    /**
     * @return mixed
     */
    public function getContentEncoding()
    {
        return isset($this->parsed[EnumHttpHeader::CONTENT_ENCODING]) ? $this->parsed[EnumHttpHeader::CONTENT_ENCODING] : '';
    }

    /**
     * @return mixed
     */
    public function getCharset()
    {
        return isset($this->parsed[EnumHttpHeader::CHARSET]) ? $this->parsed[EnumHttpHeader::CHARSET] : '';
    }

    /**
     * @return mixed
     */
    public function getContentType()
    {
        return isset($this->parsed[EnumHttpHeader::CONTENT_TYPE]) ? $this->parsed[EnumHttpHeader::CONTENT_TYPE] : '';
    }

    /**
     * Parse headers
     */
    public function parse()
    {
        $this->parsed = [];

        $status_line = isset($this->headers[0]) ? trim($this->headers[0]) : null;

        if ( $status_line ){
            $parts = explode(' ', $status_line, 3);
            $protocol_and_version = isset($parts[0]) ? $parts[0] : '';
            $this->status_code = isset($parts[1]) ? intval($parts[1]) : 0;
            $this->reason_phrase = isset($parts[2]) ? $parts[2] : '';

            $p = strpos($protocol_and_version, '/');
            $this->protocol = ($p !== false) ? substr($protocol_and_version,0,$p) : $protocol_and_version;
            $this->protocol_version = ($p !== false) ? substr($protocol_and_version,$p+1) : '';
        }

        foreach( $this->headers as $h ){
            if ( preg_match( '@Content-Encoding:\s+([\w/+]+)@i', $h, $matches ) ){
                $this->parsed[EnumHttpHeader::CONTENT_ENCODING] = isset($matches[1]) ? strtolower($matches[1]) : null;
            }
            if ( preg_match( '@Content-Type:\s*([\w/+-\/]+);\s*charset=\s*([\w/+\-]+)@i', $h, $matches ) ){
                $this->parsed[EnumHttpHeader::CONTENT_TYPE] = isset($matches[1]) ? strtolower($matches[1]) : null;
                $this->parsed[EnumHttpHeader::CHARSET] = isset($matches[2]) ? strtolower($matches[2]) : null;
            }
            if ( preg_match( '@Content-Type:\s*([\w/+-\/]+)@i', $h, $matches ) ){
                $this->parsed[EnumHttpHeader::CONTENT_TYPE] = isset($matches[1]) ? strtolower($matches[1]) : null;
            }
            if ( preg_match( '@Host:\s+([\w/:+]+)@i', $h, $matches ) ){
                $this->parsed[EnumHttpHeader::HOST] = isset($matches[1]) ? strtolower($matches[1]) : null;
            }
        }
    }
}