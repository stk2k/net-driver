<?php
namespace NetDriver;

interface HttpListenerInterface
{
    /**
     * Received HTTP response
     *
     * @param int $status_code
     * @param string $body
     * @param array $headers
     */
    public function onReceivedResponse($status_code, $body, $headers);
}