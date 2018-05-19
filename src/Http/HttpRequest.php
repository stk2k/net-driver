<?php
namespace NetDriver\Http;

use NetDriver\Enum\EnumRequestOption;
use NetDriver\NetDriverInterface;

class HttpRequest
{
    /** @var NetDriverInterface */
    private $driver;

    /** @var string */
    private $method;

    /** @var string */
    private $url;

    /** @var array */
    private $data;

    /** @var array */
    private $options;

    /**
     * HttpRequest constructor.
     *
     * @param NetDriverInterface $driver
     * @param string $method
     * @param string $url
     * @param array $data
     * @param array $options
     */
    public function __construct($driver, $method, $url, array $data, array $options = [])
    {
        $this->driver = $driver;
        $this->method = $method;
        $this->url = $url;
        $this->data = $data;
        $this->options = $options;
    }

    /**
     * Get URL
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Get HTTP headers
     *
     * @return array
     */
    public function getHttpHeaders()
    {
        $field = EnumRequestOption::HTTPHEADERS;
        $http_deaders = isset($this->options[$field]) ? $this->options[$field] : [];

        $http_deaders = array_merge($http_deaders, $this->getDefaultHttpHeaders());

        return $http_deaders;
    }

    /**
     * Get extra options
     *
     * @return array
     */
    public function getExtraOptions()
    {
        $field = EnumRequestOption::EXTRAOPTIONS;
        return isset($this->options[$field]) ? $this->options[$field] : [];
    }

    /**
     * get default http headers
     *
     * @return array
     */
    private function getDefaultHttpHeaders()
    {
        return array(
            'Content-Type' => 'text/plain',
            'User-Agent' => $this->driver->getUserAgent(),
            'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
            'Accept-Language' => 'en-us;q=0.7,en;q=0.3',
            'Accept-Encoding' => 'gzip, deflate',
            'Accept-Charset' => 'ISO-8859-1,utf-8;q=0.7,*;q=0.7',
            'Connection' => 'keep-alive',
            'Keep-Alive' => '300',
            'Cache-Control' => 'max-age=0',
            'Pragma' => '',
        );
    }

}