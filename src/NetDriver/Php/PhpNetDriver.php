<?php
namespace NetDriver\NetDriver\Php;

use NetDriver\Exception\NetDriverException;
use NetDriver\NetDriverInterface;
use NetDriver\NetDriverHandleInterface;
use NetDriver\Http\HttpRequest;
use NetDriver\Http\HttpResponse;
use NetDriver\Http\HttpPostRequest;
use NetDriver\Http\ResponseHeaders;
use NetDriver\NetDriver\AbstractNetDriver;

class PhpNetDriver extends AbstractNetDriver implements NetDriverInterface
{
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

        try{
            // fire event after received HTTP response
            $request = $this->fireOnSendingRequest($request);

            // context
            $context = [];

            $context['ignore_errors'] = true;

            if ($request instanceof HttpPostRequest)
            {
                // data
                $data = $request->getPostFields();

                // header
                $header = [
                    "Content-Type: application/x-www-form-urlencoded",
                    "Content-Length: ".strlen($data)
                ];

                // context
                $context['header'] = implode("\r\n", $header);
                $context['content'] = $data;
            }

            // context
            $context['method'] = $request->getMethod();
            $context = stream_context_create(['http' => $context]);

            // send request
            $body = file_get_contents($url, false, $context);

            if ($body === false){
                throw new NetDriverException('file_get_contents failed');
            }

            if (!preg_match('/HTTP\/1\.[0|1|x] ([0-9]{3})/', $http_response_header[0], $matches)){
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