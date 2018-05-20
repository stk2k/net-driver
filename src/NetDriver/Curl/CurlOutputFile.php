<?php
namespace NetDriver\NetDriver\Curl;

class CurlOutputFile
{
    /** @var resource */
    private $handle;

    /**
     * CurlOutputFile constructor.
     */
    public function __construct()
    {
        $this->handle = tmpfile();
    }

    /**
     * @return bool|resource
     */
    public function handle()
    {
        return $this->handle;
    }

    /**
     * @return string
     */
    public function readAll()
    {
        $content = '';
        if ($this->handle)
        {
            fseek($this->handle, 0);
            while(!feof($this->handle))
            {
                $content .= fread($this->handle, 8192);
            }
        }
        return $content;
    }
}