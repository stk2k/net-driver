<?php
namespace NetDriver\Enum;

class EnumRequestOption
{
    const HTTPHEADERS        = 'http-headers';       // array
    const EXTRAOPTIONS       = 'extra-options';      // array
    const VERBOSE            = 'verbose';            // bool
    const TOTAL_TIMEOUT_MS   = 'total_timeout_ms';   // int
    const CONNECT_TIMEOUT_MS = 'connect_timeout_ms'; // int
}