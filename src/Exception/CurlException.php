<?php
declare(strict_types=1);

namespace Stk2k\NetDriver\Exception;

use Exception;

class CurlException extends Exception implements NetDriverExceptionInterface
{
    /** @var string */
    private $function;
    
    /** @var string */
    private $errno;
    
    /** @var string */
    private $errmsg;
    
    /**
     * construct
     *
     * @param string $function
     * @param resource $curl_handle
     */
    public function __construct(string $function, $curl_handle){
    
        $this->errno = curl_errno($curl_handle);
        $this->errmsg = curl_error($curl_handle);
        
        $msg = 'cURL error:' . $this->errmsg . '(' . $this->errno . ')';
        parent::__construct($msg);
        
        $this->function = $function;
    }
    
    /**
     * get error curl function
     *
     * @return string
     * @noinspection PhpUnused
     */
    public function getFunction() : string
    {
        return $this->function;
    }
    
    /**
     * get error number
     *
     * @return string
     * @noinspection PhpUnused
     */
    public function getErrorNumber() : string
    {
        return $this->errno;
    }
    
    /**
     * get error message
     *
     * @return string
     * @noinspection PhpUnused
     */
    public function getErrorMessage() : string
    {
        return $this->errmsg;
    }
}