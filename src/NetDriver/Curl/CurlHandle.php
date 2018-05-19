<?php
namespace NetDriver\NetDriver\Curl;

use NetDriver\NetDriverHandleInterface;

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