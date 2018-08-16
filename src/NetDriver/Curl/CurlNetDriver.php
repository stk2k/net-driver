<?php
namespace NetDriver\NetDriver\Curl;

use NetDriver\Exception\CurlException;
use NetDriver\Exception\DeflateException;
use NetDriver\Exception\NetDriverException;
use NetDriver\Exception\TimeoutException;
use NetDriver\NetDriverInterface;
use NetDriver\NetDriverHandleInterface;
use NetDriver\NetDriver\AbstractNetDriver;
use NetDriver\Http\HttpRequest;
use NetDriver\Http\HttpResponse;
use NetDriver\Http\HttpPostRequest;
use NetDriver\Http\HttpPutRequest;

class CurlNetDriver extends AbstractNetDriver implements NetDriverInterface
{
    /**
     * Create new handle
     *
     * @return NetDriverHandleInterface
     */
    public function newHandle()
    {
        return new CurlHandle();
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
     * @throws TimeoutException
     * @throws DeflateException
     */
    public function sendRequest(NetDriverHandleInterface $handle, HttpRequest $request)
    {
        $url = $request->getUrl();

        try{
            $ch = $handle->reset();

            // set default options
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HEADER, true);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

            $this->debug('Set URL: ' . $url);

            // set total timeout
            curl_setopt($ch, CURLOPT_TIMEOUT_MS, $request->getTotalTimeoutMs());

            // set connect timeout
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT_MS, $request->getConnectTimeoutMs());

            // fire event after received HTTP response
            $request = $this->fireOnSendingRequest($request);

            // set request header
            $headers = $request->getHttpHeaders();
            $headers_curl = array();
            foreach($headers as $key => $value){
                $headers_curl[] = "$key: $value";
            }
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers_curl);
            $this->debug('HTTP request headers: ' . print_r($headers_curl, true));

            // set extra options
            $extra_options = $request->getExtraOptions();
            foreach($extra_options as $opt => $value){
                curl_setopt($ch, $opt, $value);
            }

            // set custome request
            if ($request instanceof HttpPostRequest){
                //curl_setopt($ch, CURLOPT_CUSTOMREQUEST, EnumHttpMethod::POST);
                $this->debug('Method: POST');
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $request->getPostFields());
                $this->debug('POST fields: ' . print_r($request->getPostFields(), true));
            }
            else if ($request instanceof HttpPutRequest){
                $this->debug('Method: PUT');
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $request->getMethod());
                curl_setopt($ch, CURLOPT_POSTFIELDS, $request->getPutFields());
            }
            else{
                $method = $request->getMethod();
                $this->debug('Method: ' . $method);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
            }

            // verbose
            $verbose = $this->getVerbose() | $request->getVerbose();
            curl_setopt($ch, CURLOPT_VERBOSE, $verbose ? 1 : 0);

            // verbose output
            $strerr_file = new CurlOutputFile();
            curl_setopt($ch, CURLOPT_STDERR, $strerr_file->handle());

            $header_file = new CurlOutputFile();
            curl_setopt($ch, CURLOPT_WRITEHEADER, $header_file->handle());

            $output_file = new CurlOutputFile();
            curl_setopt($ch, CURLOPT_FILE, $output_file->handle());

            // send request
            $result = curl_exec($ch);

            $this->debug('curl_exec result: ' . $result);

            // get verbose output
            $strerr = $strerr_file->readAll();
            $header = $header_file->readAll();
            $output = $output_file->readAll();

            $this->debug('strerr: ' . $strerr);
            $this->debug('header: ' . $header);
            $this->debug('output: ' . $output);

            // fire event after received verbose
            $this->fireOnReceivedVerbose($strerr, $header, $output);

            if ($result === false)
            {
                switch(curl_errno($ch))
                {
                    case CURLE_OPERATION_TIMEDOUT:
                        throw new TimeoutException($request);
                        break;
                }
                throw new CurlException('curl_exec', $ch);
            }

            // get response
            $info = curl_getinfo ($ch);

            $response = new CurlResponse($info, $output);

            $this->debug('status code: ' . $response->getStatusCode());
            $this->debug('response headers: ' . $response->getHeaders());

            // fire event after received HTTP response
            $this->fireOnReceivedResponse($response);

            return $response;
        }
        catch (CurlException $e){
            throw new NetDriverException($url, $e);
        }
    }
}