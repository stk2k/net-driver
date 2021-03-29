<?php
declare(strict_types=1);

namespace Stk2k\NetDriver\Exception;

use Exception;

class NetDriverException extends Exception implements NetDriverExceptionInterface
{
    /**
     * construct
     *
     * @param string $message
     * @param Exception|null $prev
     */
    public function __construct(string $message, $prev = null){
        parent::__construct($message,0,$prev);
    }
}