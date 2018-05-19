<?php
namespace NetDriver;

interface NetDriverHandleInterface
{
    /**
     * Reset
     */
    public function reset();

    /**
     * Close
     *
     */
    public function close();
}