<?php

use Ihsw\Toxiproxy\Test\AbstractTest,
    Ihsw\Toxiproxy\Toxiproxy,
    Ihsw\Toxiproxy\Proxy,
    Ihsw\Toxiproxy\Toxic;

class ProxyTest extends AbstractTest
{
    /**
     * @expectedException Ihsw\Toxiproxy\Exception\InvalidToxicException
     */
    public function testUpdateInvalidToxic()
    {
        $this->handleProxy(function(Proxy $proxy) {
            $proxy->updateDownstream("fdsfgs", []);
        });
    }

    public function testDisable($callback = null)
    {
        $this->handleProxy(function(Proxy $proxy) use($callback) {
            $response = $proxy->disable();
            $this->assertEquals(
                $response->getStatusCode(),
                Toxiproxy::OK,
                sprintf(
                    "Could not disable proxy '%s': %s",
                    $proxy->getName(),
                    $response->getBody()
                )
            );

            $this->assertProxyUnavailable(
                $proxy,
                sprintf(
                    "Could not verify proxy '%s' being unavailable",
                    $proxy->getName()
                )
            );

            if (!is_null($callback)) {
                $callback($proxy);
            }
        });
    }

    public function testEnable()
    {
        $this->testDisable(function(Proxy $proxy) {
            $response = $proxy->enable();
            $this->assertEquals(
                $response->getStatusCode(),
                Toxiproxy::OK,
                sprintf(
                    "Could not enable proxy '%s': %s",
                    $proxy->getName(),
                    $response->getBody()
                )
            );

            $this->assertProxyAvailable(
                $proxy,
                sprintf(
                    "Could not verify proxy '%s' being available",
                    $proxy->getName()
                )
            );
        });
    }

    public function testGetDownstreamToxics()
    {
        $this->handleProxy(function(Proxy $proxy) {
            $toxics = $proxy->getToxics(Proxy::DOWNSTREAM);
            foreach ($toxics as $toxic) {
                $this->assertTrue(
                    $toxic instanceof Toxic,
                    "Get toxics toxic was not an instance of Toxic"
                );
            }
        });
    }

    public function testGetUpstreamToxics()
    {
        $this->handleProxy(function(Proxy $proxy) {
            $toxics = $proxy->getToxics(Proxy::UPSTREAM);
            foreach ($toxics as $toxic) {
                $this->assertTrue(
                    $toxic instanceof Toxic,
                    "Get toxics toxic was not an instance of Toxic"
                );
            }
        });
    }
}
