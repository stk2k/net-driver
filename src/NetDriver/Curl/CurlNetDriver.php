<?php
namespace NetDriver\NetDriver\Curl;

use Psr\Log\LoggerInterface;

use NetDriver\Exception\CurlException;
use NetDriver\Exception\NetDriverException;
use NetDriver\NetDriverInterface;
use NetDriver\NetDriverHandleInterface;
use NetDriver\Http\HttpRequest;
use NetDriver\Http\HttpResponse;
use NetDriver\NetDriver\AbstractNetDriver;

class CurlNetDriver extends AbstractNetDriver implements NetDriverInterface
{
    /**
     * CurlNetDriver constructor.
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

            curl_setopt($ch, CURLINFO_HEADER_OUT, true);
            curl_setopt($ch, CURLOPT_VERBOSE, true);

            // set request header
            $headers = $request->getHttpHeaders();
            $headers_curl = array();
            foreach($headers as $key => $value){
                $headers_curl[] = "$key: $value";
            }
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers_curl);

            // set extra options
            $extra_options = $request->getExtraOptions();
            foreach($extra_options as $opt => $value){
                curl_setopt($ch, $opt, $value);
            }

            $result = curl_exec($ch);

            if ($result === false){
                throw new CurlException('curl_exec', $ch);
            }

            $info = curl_getinfo ($ch);

            $response = new CurlResponse($info, $result);

            $headers = $response->getHeaders();

            $body = $response->getBody();

            $status_code = $response->getStatusCode();

            $this->fireOnReceivedResponse($status_code, $body, $headers);

            return new HttpResponse($body, $status_code);
        }
        catch (\Exception $e){
            throw new NetDriverException($url, $e);
        }
    }
}