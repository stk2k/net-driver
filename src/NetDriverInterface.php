<?php
declare(strict_types=1);

namespace Stk2k\NetDriver;

use Psr\Log\LoggerInterface;

use Stk2k\NetDriver\Http\HttpRequest;
use Stk2k\NetDriver\Http\HttpResponse;
use Stk2k\NetDriver\Exception\NetDriverException;
use Stk2k\NetDriver\Exception\TimeoutException;
use Stk2k\NetDriver\Exception\DeflateException;

interface NetDriverInterface
{
    /**
     * Set verbose flag
     *
     * @param bool $verbose
     */
    public function setVerbose(bool $verbose = true);

    /**
     * Get verbose flag
     *
     * @return bool
     */
    public function getVerbose() : bool;

    /**
     * Set user agent
     *
     * @param $user_agent
     */
    public function setUserAgent(string $user_agent);

    /**
     * Get user agent
     *
     * @return string
     */
    public function getUserAgent() : string;

    /**
     * Create new handle
     *
     * @return NetDriverHandleInterface
     */
    public function newHandle() : NetDriverHandleInterface;

    /**
     * Send HTTP request
     *
     * @param NetDriverHandleInterface $handle
     * @param HttpRequest $request
     *
     * @return HttpResponse
     *
     * @throws NetDriverException
     * @throws TimeoutException
     * @throws DeflateException
     */
    public function sendRequest(NetDriverHandleInterface $handle, HttpRequest $request) : HttpResponse;

    /**
     * Listen event
     *
     * @param string $event
     * @param callable $listener
     */
    public function listen(string $event, callable $listener);

    /**
     * Set logger
     *
     * @param LoggerInterface $logger
     */
    public function setLogger(LoggerInterface $logger);
}