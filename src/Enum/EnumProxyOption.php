<?php
declare(strict_types=1);

namespace Stk2k\NetDriver\Enum;

class EnumProxyOption
{
    const PROXY_SERVER        = 'proxy-server';         // string
    const PROXY_PORT          = 'proxy-port';           // int
    const PROXY_TYPE          = 'proxy-type';           // int(DEFAULT: 'http')
    const PROXY_AUTH          = 'proxy-auth';           // string(DEFAULT: null)
    const USER_PASSWD         = 'user-passwd';          // string(FORMAT: "user:passwd")
}