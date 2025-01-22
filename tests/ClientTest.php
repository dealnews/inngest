<?php

namespace DealNews\Inngest\Tests;

use DealNews\Inngest\Client;
use DealNews\TestHelpers\Guzzle;

/**
 * @group unit
 */
class ClientTest extends \PHPUnit\Framework\TestCase {

    use Guzzle;

    public function testGoodSend() {
        $container = [];
        $mock = $this->makeGuzzleMock(
            [
                200
            ],
            [
                [
                    "ids" => [
                        "01JJ79JQB3TKFK2WYG0TA8BC4X"
                    ],
                    "status" => 200
                ]
            ],
            $container
        );

        $client = new Client('asdf', $mock);
        $this->assertEquals(
            [
                "01JJ79JQB3TKFK2WYG0TA8BC4X"
            ],
            $client->send('event/test', ['foo' => 'bar'], '01JJ79JQB3TKFK2WYG0TA8BC4X')
        );
    }

    public function testBadSend() {
        $container = [];
        $mock = $this->makeGuzzleMock(
            [
                401
            ],
            [
                [
                    "data"       => null,
                    "error"      => "Event key not found",
                    "error_code" => "event_key_not_found"
                ]
            ],
            $container
        );

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionCode(401);

        $client = new Client('asdf', $mock);
        $this->assertTrue($client->send('event/test', ['foo' => 'bar']));
    }
}
