<?php
declare(strict_types=1);

namespace Stk2k\NetDriver\Exception;

use Exception;
use Stk2k\NetDriver\Http\HttpRequest;

class TimeoutException extends Exception implements NetDriverExceptionInterface
{
    /** @var HttpRequest */
    private $request;

    /**
     * construct
     *
     * @param HttpRequest $request
     */
    public function __construct(HttpRequest $request)
    {
        $message = 'Operation timed out: URL=' . $request->getUrl() . ' method=' . $request->getMethod();
        parent::__construct($message);

        $this->request = $request;
    }

    /**
     * @return HttpRequest
     */
    public function getRequest() : HttpRequest
    {
        return $this->request;
    }
}