<?php
declare(strict_types=1);

namespace Stk2k\NetDriver\Drivers\Curl;

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
     * @noinspection PhpMissingReturnTypeInspection
     */
    public function handle()
    {
        return $this->handle;
    }

    /**
     * @return string
     */
    public function readAll() : string
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