<?php
declare(strict_types=1);

namespace Stk2k\NetDriver\Drivers\Curl;

use Stk2k\NetDriver\Exception\CurlException;
use Stk2k\NetDriver\Exception\DeflateException;
use Stk2k\NetDriver\Exception\NetDriverException;
use Stk2k\NetDriver\Exception\TimeoutException;
use Stk2k\NetDriver\Http\HttpProxyRequestInterface;
use Stk2k\NetDriver\NetDriverInterface;
use Stk2k\NetDriver\NetDriverHandleInterface;
use Stk2k\NetDriver\Drivers\AbstractNetDriver;
use Stk2k\NetDriver\Http\HttpRequest;
use Stk2k\NetDriver\Http\HttpResponse;
use Stk2k\NetDriver\Http\HttpPostRequest;
use Stk2k\NetDriver\Http\HttpPutRequest;

class CurlNetDriver extends AbstractNetDriver implements NetDriverInterface
{
    const DEFAULT_MAX_REDIRECTION   = 10;

    /**
     * Create new handle
     *
     * @return NetDriverHandleInterface
     */
    public function newHandle() : NetDriverHandleInterface
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
    public function sendRequest(NetDriverHandleInterface $handle, HttpRequest $request) : HttpResponse
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

            // follow redirection URL
            curl_setopt($ch,CURLOPT_FOLLOWLOCATION,true);

            // set max redirection
            curl_setopt($ch,CURLOPT_MAXREDIRS, self::DEFAULT_MAX_REDIRECTION);

            // add referer in redirection
            curl_setopt($ch,CURLOPT_AUTOREFERER,true);

            // set total timeout
            curl_setopt($ch, CURLOPT_TIMEOUT_MS, $request->getTotalTimeoutMs());

            // set connect timeout
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT_MS, $request->getConnectTimeoutMs());

            // fire event after received HTTP response
            $request = $this->fireOnSendingRequest($request);

            // set request header
            $headers = $request->getHttpHeaders();
            $headers_curl = [];
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

            // set proxy options
            if ($request instanceof HttpProxyRequestInterface){
                curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, TRUE);
                curl_setopt($ch, CURLOPT_PROXYPORT, $request->getProxyPort());
                $proxy_type = $request->getProxyType();
                switch($proxy_type){
                    case 'http':
                        curl_setopt($ch, CURLOPT_PROXY, 'http://' . $request->getProxyServer());
                        break;
                    case 'https':
                        curl_setopt($ch, CURLOPT_PROXY, 'https://' . $request->getProxyServer());
                        break;
                }
                $proxy_auth = $request->getProxyAuth();
                if (!empty($proxy_auth)){
                    curl_setopt($ch, CURLOPT_PROXYAUTH, $proxy_auth);
                    curl_setopt($ch, CURLOPT_PROXYUSERPWD, $request->getProxyUserPassword());
                }
                else{
                    curl_setopt($ch, CURLOPT_PROXYUSERPWD, "anonymous:");
                }
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