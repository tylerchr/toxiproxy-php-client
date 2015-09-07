<?php namespace Ihsw\Toxiproxy;

use GuzzleHttp\Exception\ClientException as HttpClientException;
use Ihsw\Toxiproxy\Proxy;

class Toxic
{
    private $proxy;
    private $name;
    private $direction;
    private $data;

    public function __construct(Proxy $proxy, $name, $direction,
        array $data = [])
    {
        $this->proxy = $proxy;
        $this->name = $name;
        $this->direction = $direction;
        $this->data = $data;
    }

    /**
     * misc
     */
    public function getHttpClient()
    {
        return $this->proxy->getHttpClient();
    }

    public function getData()
    {
        return $this->data;
    }

    /**
     * api access
     */
    public function setData(array $data)
    {
        $this->data = $data;
        $url = sprintf(
            "/proxies/%s/%s/toxics/%s",
            $this->proxy->getName(),
            $this->direction,
            $this->name
        );
        try {
            return $this->getHttpClient()->post(
                $url,
                ["body" => json_encode($data)]
            );
        } catch (HttpClientException $e) {
            $this->proxy->getToxiproxy()->handleHttpClientException($e);
        }
    }
}
