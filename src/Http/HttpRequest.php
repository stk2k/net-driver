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
    private $options;

    /**
     * HttpRequest constructor.
     *
     * @param NetDriverInterface $driver
     * @param string $method
     * @param string $url
     * @param array $options
     */
    public function __construct($driver, $method, $url, array $options = [])
    {
        $this->driver = $driver;
        $this->method = $method;
        $this->url = $url;
        $this->options = $options;
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
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
     * Add HTTP Header
     *
     * @param string $http_header
     * @param string $value
     */
    public function addHttpHeader($http_header, $value)
    {
        $field = EnumRequestOption::HTTPHEADERS;
        $http_headers = isset($this->options[$field]) ? $this->options[$field] : [];

        foreach($http_headers as $key => $header){
            if (strpos($header, $http_header) === 0){
                $http_headers[$key] = "$http_header: $value\r\n";
                return;
            }
        }

        $http_headers[$http_header] = $value;
        $this->options[$field] = $http_headers;
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

        $http_deaders = array_merge($this->getDefaultHttpHeaders(), $http_deaders);

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
     * Get verbose options
     *
     * @return bool
     */
    public function getVerbose()
    {
        $field = EnumRequestOption::VERBOSE;
        return isset($this->options[$field]) ? $this->options[$field] : false;
    }

    /**
     * Get total timeout(milli second)
     *
     * @return bool
     */
    public function getTotalTimeoutMs()
    {
        $field = EnumRequestOption::TOTAL_TIMEOUT_MS;
        return isset($this->options[$field]) ? $this->options[$field] : 0;
    }

    /**
     * Get connect timeout(milli second)
     *
     * @return bool
     */
    public function getConnectTimeoutMs()
    {
        $field = EnumRequestOption::CONNECT_TIMEOUT_MS;
        return isset($this->options[$field]) ? $this->options[$field] : 0;
    }

    /**
     * get default http headers
     *
     * @return array
     */
    private function getDefaultHttpHeaders()
    {
        return array(
            'User-Agent' => $this->driver->getUserAgent(),
        );
    }

}