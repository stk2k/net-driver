<?php
declare(strict_types=1);

namespace Stk2k\NetDriver\Http;

use Stk2k\NetDriver\Enum\EnumProxyOption;
use Stk2k\NetDriver\Enum\EnumRequestOption;
use Stk2k\NetDriver\NetDriverInterface;

class HttpProxyGetRequest extends HttpGetRequest implements HttpProxyRequestInterface
{
    /**
     * HttpProxyGetRequest constructor.
     *
     * @param NetDriverInterface $driver
     * @param string $url
     * @param array $options
     */
    public function __construct(NetDriverInterface $driver, string $url, array $options = [])
    {
        parent::__construct($driver, $url, $options);
    }

    /**
     * Returns proxy server
     *
     * @return string
     */
    public function getProxyServer() : string
    {
        $proxy_options = $this->getOption(EnumRequestOption::PROXY_OPTIONS, []);
        return isset($proxy_options[EnumProxyOption::PROXY_SERVER]) ? $proxy_options[EnumProxyOption::PROXY_SERVER] : '';
    }

    /**
     * Returns proxy port
     *
     * @return mixed
     * @noinspection PhpMissingReturnTypeInspection
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
    public function getProxyType() : string
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
    public function getProxyAuth() : string
    {
        $proxy_options = $this->getOption(EnumRequestOption::PROXY_OPTIONS, []);
        return isset($proxy_options[EnumProxyOption::PROXY_AUTH]) ? $proxy_options[EnumProxyOption::PROXY_AUTH] : '';
    }

    /**
     * Returns proxy user/password(FORMAT: "user:password")
     *
     * @return string
     */
    public function getProxyUserPassword() : string
    {
        $proxy_options = $this->getOption(EnumRequestOption::PROXY_OPTIONS, []);
        return isset($proxy_options[EnumProxyOption::USER_PASSWD]) ? $proxy_options[EnumProxyOption::USER_PASSWD] : '';
    }

}