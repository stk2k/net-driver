<?php
declare(strict_types=1);

namespace Stk2k\NetDriver\Exception;

use Exception;

class JsonFormatException extends Exception implements NetDriverExceptionInterface
{
    private $body;
    private $json_error;
    
    /**
     * construct
     *
     * @param string $body
     */
    public function __construct(string $body){
        $this->body = $body;
        $this->json_error = json_last_error();
        parent::__construct('server returned illegal json format(' . $this->json_error . '):' . $body);
    }
    
    /**
     * get json error
     *
     * @return int
     * @noinspection PhpUnused
     */
    public function getJsonError() : int
    {
        return $this->json_error;
    }
}