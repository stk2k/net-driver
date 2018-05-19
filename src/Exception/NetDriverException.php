<?php
namespace NetDriver\Exception;

class NetDriverException extends \Exception implements NetDriverExceptionInterface
{
    /**
     * construct
     *
     * @param string $message
     * @param \Exception|null $prev
     */
    public function __construct($message, $prev = null){
        parent::__construct($message,0,$prev);
    }
}