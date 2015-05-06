<?php

use Ihsw\Toxiproxy\Test\AbstractHttpTest,
    Ihsw\Toxiproxy\Toxiproxy,
    Ihsw\Toxiproxy\Proxy;

class ProxyHttpTest extends AbstractHttpTest
{
    /**
     * @expectedException Ihsw\Toxiproxy\Exception\InvalidToxicException
     */
    public function testUpdateInvalidToxic()
    {
        $this->handleProxy([], function(Proxy $proxy) {
            $proxy->updateDownstream("fdsfgs", []);
        });
    }

    public function testDisable(array $responses = [], $callback = null)
    {
        $responses = array_merge(
            [self::disableProxyResponse(self::TEST_NAME, self::TEST_UPSTREAM, self::TEST_LISTEN)],
            $responses
        );
        $this->handleProxy($responses, function(Proxy $proxy) use($callback) {
            $response = $proxy->disable();
            $this->assertEquals(
                $response->getStatusCode(),
                Toxiproxy::OK,
                sprintf("Could not disable proxy '%s': %s", $proxy["name"], $response->getBody())
            );

            $this->assertProxyUnavailable(
                $proxy,
                sprintf("Could not verify proxy '%s' being unavailable", $proxy["name"])
            );

            if (!is_null($callback)) {
                $callback($proxy);
            }
        });
    }

    public function testEnable()
    {
        $responses = [self::enableProxyResponse(self::TEST_NAME, self::TEST_UPSTREAM, self::TEST_LISTEN)];
        $this->testDisable($responses, function(Proxy $proxy) {
            $response = $proxy->enable();
            $this->assertEquals(
                $response->getStatusCode(),
                Toxiproxy::OK,
                sprintf("Could not enable proxy '%s': %s", $proxy["name"], $response->getBody())
            );

            $this->assertProxyAvailable(
                $proxy,
                sprintf("Could not verify proxy '%s' being available", $proxy["name"])
            );
        });
    }
}