<?php
namespace NetDriver\Http;

interface HttpProxyRequestInterface
{
    /**
     * Returns proxy server
     *
     * @return string
     */
    public function getProxyServer();

    /**
     * Returns proxy port
     *
     * @return int
     */
    public function getProxyPort();

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
    public function getProxyType();

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
    public function getProxyAuth();

    /**
     * Returns proxy user/password(FORMAT: "user:password")
     *
     * @return string
     */
    public function getProxyUserPassword();
}