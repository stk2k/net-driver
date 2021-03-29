<?php
declare(strict_types=1);

namespace Stk2k\NetDriver\Http;

use Stk2k\NetDriver\Enum\EnumRequestOption;
use Stk2k\NetDriver\NetDriverInterface;

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
    public function __construct(NetDriverInterface $driver, string $method, string $url, array $options = [])
    {
        $this->driver = $driver;
        $this->method = $method;
        $this->url = $url;
        $this->options = $options;
    }

    /**
     * @return string
     */
    public function getMethod() : string
    {
        return $this->method;
    }

    /**
     * Get URL
     *
     * @return string
     */
    public function getUrl() : string
    {
        return $this->url;
    }

    /**
     * Get option
     *
     * @param $field
     * @param array|string|int|null $default
     *
     * @return array|string|int|null
     */
    public function getOption($field, $default = null)
    {
        return isset($this->options[$field]) ? $this->options[$field] : $default;
    }

    /**
     * Add HTTP Header
     *
     * @param string $http_header
     * @param string $value
     */
    public function addHttpHeader(string $http_header, string $value)
    {
        $field = EnumRequestOption::HTTP_HEADERS;
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
    public function getHttpHeaders() : array
    {
        $field = EnumRequestOption::HTTP_HEADERS;
        $http_deaders = isset($this->options[$field]) ? $this->options[$field] : [];

        $http_deaders = array_merge($this->getDefaultHttpHeaders(), $http_deaders);

        return $http_deaders;
    }

    /**
     * Get extra options
     *
     * @return array
     */
    public function getExtraOptions() : array
    {
        $field = EnumRequestOption::EXTRA_OPTIONS;
        return $this->options[$field] ?? [];
    }

    /**
     * Get verbose options
     *
     * @return bool
     */
    public function getVerbose() : bool
    {
        $field = EnumRequestOption::VERBOSE;
        return $this->options[$field] ?? false;
    }

    /**
     * Get total timeout(milli second)
     *
     * @return int
     */
    public function getTotalTimeoutMs() : int
    {
        $field = EnumRequestOption::TOTAL_TIMEOUT_MS;
        return $this->options[$field] ?? 0;
    }

    /**
     * Get connect timeout(milli second)
     *
     * @return int
     */
    public function getConnectTimeoutMs() : int
    {
        $field = EnumRequestOption::CONNECT_TIMEOUT_MS;
        return $this->options[$field] ?? 0;
    }

    /**
     * get default http headers
     *
     * @return array
     */
    private function getDefaultHttpHeaders() : array
    {
        return [
            'User-Agent' => $this->driver->getUserAgent(),
        ];
    }

}