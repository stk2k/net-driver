<?php
declare(strict_types=1);

namespace Stk2k\NetDriver\Drivers\Curl;

use Stk2k\NetDriver\NetDriverHandleInterface;

class CurlHandle implements NetDriverHandleInterface
{
    /** @var resource  */
    private $handle;

    /**
     * Construct
     *
     */
    public function __construct()
    {
        $this->handle = curl_init();
    }

    /**
     * Reset
     */
    public function reset()
    {
        curl_reset($this->handle);
        return $this->handle;
    }

    /**
     * Close
     *
     */
    public function close()
    {
        if ($this->handle){
            curl_close($this->handle);
        }
        $this->handle = null;
    }
}