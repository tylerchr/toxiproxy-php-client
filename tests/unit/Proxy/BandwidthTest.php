<?php

use GuzzleHttp\Client as HttpClient;
use Ihsw\Toxiproxy\Test\AbstractHttpTest,
    Ihsw\Toxiproxy\Toxiproxy,
    Ihsw\Toxiproxy\Proxy;

class BandwidthTest extends AbstractHttpTest
{
    public function testUpdateDownstream()
    {
        $responses = [self::httpTestResponseFactory(Toxiproxy::OK, "set-bandwidth-toxic.json")];
        $this->handleProxy($responses, function(Proxy $proxy) {
            $response = $proxy->updateDownstream("bandwidth", ["rate" => 1000]);
            $this->assertEquals(
                $response->getStatusCode(),
                Toxiproxy::OK,
                sprintf("Could not update downstream bandwidth toxic for proxy '%s'", $proxy->getName())
            );
        });
    }

    public function testUpdateUpstream()
    {
        $responses = [self::httpTestResponseFactory(Toxiproxy::OK, "set-bandwidth-toxic.json")];
        $this->handleProxy($responses, function(Proxy $proxy) {
            $response = $proxy->updateUpstream("bandwidth", ["rate" => 1000]);
            $this->assertEquals(
                $response->getStatusCode(),
                Toxiproxy::OK,
                sprintf("Could not update upstream bandwidth toxic for proxy '%s'", $proxy->getName())
            );
        });
    }
}