<?php
namespace NetDriver;

use Psr\Log\LoggerInterface;

use NetDriver\Http\HttpRequest;
use NetDriver\Http\HttpResponse;

interface NetDriverInterface
{
    /**
     * Set user agent
     *
     * @param $user_agent
     */
    public function setUserAgent($user_agent);

    /**
     * Get user agent
     *
     * @return string
     */
    public function getUserAgent();

    /**
     * Create new handle
     *
     * @return NetDriverHandleInterface
     */
    public function newHandle();

    /**
     * Send HTTP request
     *
     * @param NetDriverHandleInterface $handle
     * @param HttpRequest $request
     *
     * @return HttpResponse
     */
    public function sendRequest(NetDriverHandleInterface $handle, HttpRequest $request);

    /**
     * Listen event
     *
     * @param string $event
     * @param callable $listener
     */
    public function listen($event, $listener);

    /**
     * Set logger
     *
     * @param LoggerInterface $logger
     */
    public function setLogger(LoggerInterface $logger);
}