<?php

class RouteTest extends TestCase
{
    /**
     * Asimple test or core routes.
     *
     * @return $response
     */
    public function testRoutes()
    {
        $response = $this->call('GET', '/');

        $this->assertEquals(200, $response->status());
    }
}
