<?php
namespace NetDriver\NetDriver\Php;

use Psr\Log\LoggerInterface;

use NetDriver\Exception\NetDriverException;
use NetDriver\NetDriverInterface;
use NetDriver\NetDriverHandleInterface;
use NetDriver\Http\HttpRequest;
use NetDriver\Http\HttpResponse;
use NetDriver\NetDriver\AbstractNetDriver;

class PhpNetDriver extends AbstractNetDriver implements NetDriverInterface
{
    /**
     * FileGetContentsDriver constructor.
     *
     * @param LoggerInterface|null $logger
     */
    public function __construct(LoggerInterface $logger = null)
    {
        parent::__construct();

        $this->setLogger($logger);
    }

    /**
     * Create new handle
     *
     * @return NetDriverHandleInterface
     */
    public function newHandle()
    {
        return new PhpHandle();
    }

    /**
     * Send HTTP request
     *
     * @param NetDriverHandleInterface $handle
     * @param HttpRequest $request
     *
     * @return HttpResponse
     *
     * @throws NetDriverException
     */
    public function sendRequest(NetDriverHandleInterface $handle, HttpRequest $request)
    {
        $url = $request->getUrl();

        $context = stream_context_create([
            'http' => ['ignore_errors' => true]
        ]);
        $body = file_get_contents($url, false, $context);

        if ($body === false){
            throw new NetDriverException('file_get_contents failed');
        }

        if (!preg_match('/HTTP\/1\.[0|1|x] ([0-9]{3})/', $http_response_header[0], $matches)){
            throw new NetDriverException('invalid http response header: ' . $http_response_header[0]);
        }
        $status_code = intval($matches[1]);

        return new HttpResponse($body, $status_code);
    }
}