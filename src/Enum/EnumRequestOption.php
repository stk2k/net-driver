<?php
declare(strict_types=1);

namespace Stk2k\NetDriver\Enum;

class EnumRequestOption
{
    const HTTP_HEADERS        = 'http-headers';         // array
    const EXTRA_OPTIONS       = 'extra-options';        // array
    const VERBOSE             = 'verbose';              // bool
    const TOTAL_TIMEOUT_MS    = 'total_timeout_ms';     // int
    const CONNECT_TIMEOUT_MS  = 'connect_timeout_ms';   // int

    const PROXY_OPTIONS       = 'proxy-options';        // array
}