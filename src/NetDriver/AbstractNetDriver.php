<?php
namespace NetDriver\NetDriver;

use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

use NetDriver\Enum\EnumEvent;
use NetDriver\NetDriverInterface;
use NetDriver\Http\HttpRequest;
use NetDriver\Http\HttpResponse;

abstract class AbstractNetDriver implements NetDriverInterface
{
    const DEFAULT_USER_AGENT = 'stk2k/net-driver';

    /** @var LoggerInterface */
    private $logger;

    /** @var callable[] */
    private $listeners;

    /** @var string */
    private $user_agent;

    /**
     * AbstractNetDriver constructor.
     *
     * @param string $user_agent
     */
    public function __construct($user_agent = self::DEFAULT_USER_AGENT)
    {
        $this->user_agent = $user_agent;
        $this->listeners = [];
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

    /**
     * System is unusable.
     *
     * @param string $message
     * @param array  $context
     *
     * @return void
     */
    public function emergency($message, array $context = array())
    {
        $this->log(LogLevel::EMERGENCY, $message, $context);
    }

    /**
     * Action must be taken immediately.
     *
     * Example: Entire website down, database unavailable, etc. This should
     * trigger the SMS alerts and wake you up.
     *
     * @param string $message
     * @param array  $context
     *
     * @return void
     */
    public function alert($message, array $context = array())
    {
        $this->log(LogLevel::ALERT, $message, $context);
    }

    /**
     * Critical conditions.
     *
     * Example: Application component unavailable, unexpected exception.
     *
     * @param string $message
     * @param array  $context
     *
     * @return void
     */
    public function critical($message, array $context = array())
    {
        $this->log(LogLevel::CRITICAL, $message, $context);
    }

    /**
     * Runtime errors that do not require immediate action but should typically
     * be logged and monitored.
     *
     * @param string $message
     * @param array  $context
     *
     * @return void
     */
    public function error($message, array $context = array())
    {
        $this->log(LogLevel::ERROR, $message, $context);
    }

    /**
     * Exceptional occurrences that are not errors.
     *
     * Example: Use of deprecated APIs, poor use of an API, undesirable things
     * that are not necessarily wrong.
     *
     * @param string $message
     * @param array  $context
     *
     * @return void
     */
    public function warning($message, array $context = array())
    {
        $this->log(LogLevel::WARNING, $message, $context);
    }

    /**
     * Normal but significant events.
     *
     * @param string $message
     * @param array  $context
     *
     * @return void
     */
    public function notice($message, array $context = array())
    {
        $this->log(LogLevel::NOTICE, $message, $context);
    }

    /**
     * Interesting events.
     *
     * Example: User logs in, SQL logs.
     *
     * @param string $message
     * @param array  $context
     *
     * @return void
     */
    public function info($message, array $context = array())
    {
        $this->log(LogLevel::INFO, $message, $context);
    }

    /**
     * Detailed debug information.
     *
     * @param string $message
     * @param array  $context
     *
     * @return void
     */
    public function debug($message, array $context = array())
    {
        $this->log(LogLevel::DEBUG, $message, $context);
    }

    /**
     * Logs with an arbitrary level.
     *
     * @param mixed  $level
     * @param string $message
     * @param array  $context
     *
     * @return void
     */
    public function log($level, $message, array $context = array())
    {
        if ($this->logger)
        {
            $this->logger->log($level, $message, $context);
        }
    }
}