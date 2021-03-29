<?php
declare(strict_types=1);

namespace Stk2k\NetDriver;

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