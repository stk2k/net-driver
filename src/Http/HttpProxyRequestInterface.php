<?php
declare(strict_types=1);

namespace Stk2k\NetDriver\Http;

interface HttpProxyRequestInterface
{
    /**
     * Returns proxy server
     *
     * @return string
     */
    public function getProxyServer() : string;

    /**
     * Returns proxy port
     *
     * @return int
     */
    public function getProxyPort() : int;

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
    public function getProxyType() : string;

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
    public function getProxyAuth() : string;

    /**
     * Returns proxy user/password(FORMAT: "user:password")
     *
     * @return string
     */
    public function getProxyUserPassword() : string;
}