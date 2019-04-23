<?php
namespace NetDriver\Http;

use NetDriver\Enum\EnumHttpMethod;
use NetDriver\Enum\EnumProxyOption;
use NetDriver\Enum\EnumRequestOption;
use NetDriver\NetDriverInterface;

class HttpProxyGetRequest extends HttpGetRequest implements HttpProxyRequestInterface
{
    /**
     * HttpProxyGetRequest constructor.
     *
     * @param NetDriverInterface $driver
     * @param string $url
     * @param array $options
     */
    public function __construct($driver, $url, array $options = [])
    {
        parent::__construct($driver, $url, $options);
    }

    /**
     * Returns proxy server
     *
     * @return string
     */
    public function getProxyServer()
    {
        $proxy_options = $this->getOption(EnumRequestOption::PROXY_OPTIONS, []);
        return isset($proxy_options[EnumProxyOption::PROXY_SERVER]) ? $proxy_options[EnumProxyOption::PROXY_SERVER] : '';
    }

    /**
     * Returns proxy port
     *
     * @return mixed
     */
    public function getProxyPort()
    {
        $proxy_options = $this->getOption(EnumRequestOption::PROXY_OPTIONS, []);
        return isset($proxy_options[EnumProxyOption::PROXY_PORT]) ? $proxy_options[EnumProxyOption::PROXY_PORT] : '';
    }

    /**
     * Returns proxy type
     *
     * available values:
     *
     * 'http' for HTTP proxy
     * 'https' for HTTPS proxy
     *
     * @return string
     */
    public function getProxyType()
    {
        $proxy_options = $this->getOption(EnumRequestOption::PROXY_OPTIONS, []);
        return isset($proxy_options[EnumProxyOption::PROXY_TYPE]) ? $proxy_options[EnumProxyOption::PROXY_TYPE] : 'http';
    }

    /**
     * Returns proxy auth
     *
     * available values:
     *
     * 'basic' for BASIC auth
     * null or empty('') for no auth
     *
     * @return string
     */
    public function getProxyAuth()
    {
        $proxy_options = $this->getOption(EnumRequestOption::PROXY_OPTIONS, []);
        return isset($proxy_options[EnumProxyOption::PROXY_AUTH]) ? $proxy_options[EnumProxyOption::PROXY_AUTH] : null;
    }

    /**
     * Returns proxy user/password(FORMAT: "user:password")
     *
     * @return mixed
     */
    public function getProxyUserPassword()
    {
        $proxy_options = $this->getOption(EnumRequestOption::PROXY_OPTIONS, []);
        return isset($proxy_options[EnumProxyOption::USER_PASSWD]) ? $proxy_options[EnumProxyOption::USER_PASSWD] : '';
    }

}