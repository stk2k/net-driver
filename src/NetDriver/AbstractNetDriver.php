<?php
namespace NetDriver\NetDriver;

use Psr\Log\LoggerInterface;

use NetDriver\Util\LoggerDelegate;
use NetDriver\Enum\EnumEvent;
use NetDriver\NetDriverInterface;
use NetDriver\Http\HttpRequest;
use NetDriver\Http\HttpResponse;

abstract class AbstractNetDriver implements NetDriverInterface
{
    use LoggerDelegate;

    const DEFAULT_USER_AGENT = 'stk2k/net-driver';

    /** @var LoggerInterface */
    private $logger;

    /** @var callable[] */
    private $listeners;

    /** @var string */
    private $user_agent;

    /** @var bool */
    private $verbose;

    /**
     * AbstractNetDriver constructor.
     *
     * @param string $user_agent
     */
    public function __construct($user_agent = self::DEFAULT_USER_AGENT)
    {
        $this->user_agent = $user_agent;
        $this->listeners = [];
        $this->verbose = false;
    }

    /**
     * Set verbose flag
     *
     * @param $verbose
     */
    public function setVerbose($verbose = true)
    {
        $this->verbose = $verbose;
    }

    /**
     * Get verbose flag
     *
     * @return bool
     */
    public function getVerbose()
    {
        return $this->verbose;
    }

    /**
     * Set logger
     *
     * @param LoggerInterface $logger
     */
    public function setLogger(LoggerInterface $logger = null)
    {
        $this->logger = $logger;
    }

    /**
     * Get logger
     *
     * @return LoggerInterface
     */
    public function getLlogger()
    {
        return $this->logger;
    }

    /**
     * Set user agent
     *
     * @param $user_agent
     */
    public function setUserAgent($user_agent)
    {
        $this->user_agent = $user_agent;
    }

    /**
     * Get user agent
     *
     * @return string
     */
    public function getUserAgent()
    {
        return $this->user_agent;
    }

    /**
     * Listen event
     *
     * @param string $event
     * @param callable $listener
     */
    public function listen($event, $listener)
    {
        $this->listeners[$event][] = $listener;
    }

    /**
     * Fire event before sending HTTP request
     *
     * @param HttpRequest $request
     *
     * @return HttpRequest
     */
    public function fireOnSendingRequest(HttpRequest $request)
    {
        $event = EnumEvent::REQUEST;
        if (isset($this->listeners[$event]) && is_array($this->listeners[$event]))
        {
            foreach($this->listeners[$event] as $l)
            {
                $ret = $l($request);
                if ($ret instanceof HttpRequest){
                    $request = $ret;
                }
            }
        }
        return $request;
    }

    /**
     * Fire event after received verbose
     *
     * @param string $strerr
     * @param string $header
     * @param string $output
     */
    public function fireOnReceivedVerbose($strerr, $header, $output)
    {
        $event = EnumEvent::VERBOSE;
        if (isset($this->listeners[$event]) && is_array($this->listeners[$event]))
        {
            foreach($this->listeners[$event] as $l)
            {
                $l($strerr, $header, $output);
            }
        }
    }

    /**
     * Fire event after received HTTP response
     *
     * @param HttpResponse $response
     */
    public function fireOnReceivedResponse(HttpResponse $response)
    {
        $event = EnumEvent::RESPONSE;
        if (isset($this->listeners[$event]) && is_array($this->listeners[$event]))
        {
            foreach($this->listeners[$event] as $l)
            {
                $l($response);
            }
        }
    }
}