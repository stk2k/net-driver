<?php /** @noinspection PhpFullyQualifiedNameUsageInspection */
declare(strict_types=1);

namespace Stk2k\NetDriver\Drivers\Php;

use Stk2k\NetDriver\Exception\NetDriverException;
use Stk2k\NetDriver\Http\HttpProxyRequestInterface;
use Stk2k\NetDriver\NetDriverInterface;
use Stk2k\NetDriver\NetDriverHandleInterface;
use Stk2k\NetDriver\Http\HttpRequest;
use Stk2k\NetDriver\Http\HttpResponse;
use Stk2k\NetDriver\Http\HttpPostRequest;
use Stk2k\NetDriver\Http\ResponseHeaders;
use Stk2k\NetDriver\Drivers\AbstractNetDriver;

class PhpNetDriver extends AbstractNetDriver implements NetDriverInterface
{
    /**
     * Create new handle
     *
     * @return NetDriverHandleInterface
     */
    public function newHandle() : NetDriverHandleInterface
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
    public function sendRequest(NetDriverHandleInterface $handle, HttpRequest $request) : HttpResponse
    {
        $url = $request->getUrl();

        try{
            // fire event after received HTTP response
            $request = $this->fireOnSendingRequest($request);

            // context
            $context = [];

            $context['ignore_errors'] = true;
            $context['header'] = [];

            if ($request instanceof HttpPostRequest)
            {
                // data
                $data = $request->getPostFields();

                // header
                $context['header'][] = [
                    "Content-Type: application/x-www-form-urlencoded",
                    "Content-Length: ".strlen($data)
                ];

                // context
                $context['content'] = $request->getPostFields();
            }

            // proxy
            if ($request instanceof HttpProxyRequestInterface){
                $proxy_server = $request->getProxyServer();
                $proxy_port = $request->getProxyPort();
                $context['proxy'] = "tcp://$proxy_server:$proxy_port";
                $context['request_fulluri'] = true;

                $proxy_auth = $request->getProxyAuth();
                if (!empty($proxy_auth)){
                    $auth = base64_encode($request->getProxyUserPassword());
                    $context['header'][] = "Proxy-Authorization: Basic $auth";
                }
            }

            // expand header
            $context['header'] = implode("\r\n", $context['header']);

            // context
            $context['method'] = $request->getMethod();
            $context = stream_context_create(['http' => $context]);

            // send request
            $body = file_get_contents($url, false, $context);

            if ($body === false){
                throw new NetDriverException('file_get_contents failed');
            }

            if (!preg_match('/HTTP\/1\.[0|1x] ([0-9]{3})/', $http_response_header[0], $matches)){
                throw new NetDriverException('invalid http response header: ' . $http_response_header[0]);
            }
            $status_code = intval($matches[1]);

            $headers = new ResponseHeaders($http_response_header);

            $response = new HttpResponse($status_code, $body, $headers);

            // fire event after received HTTP response
            $this->fireOnReceivedResponse($response);

            return $response;
        }
        catch (\Exception $e){
            throw new NetDriverException($url, $e);
        }
    }
}