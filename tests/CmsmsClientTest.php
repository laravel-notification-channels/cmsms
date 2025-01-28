<?php

namespace NotificationChannels\Cmsms\Test;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Mockery;
use NotificationChannels\Cmsms\CmsmsClient;
use NotificationChannels\Cmsms\CmsmsMessage;
use NotificationChannels\Cmsms\Exceptions\CouldNotSendNotification;
use Orchestra\Testbench\TestCase;

class CmsmsClientTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->app['config']['services.cmsms.originator'] = 'My App';
        $this->guzzle = Mockery::mock(new Client());
        $this->client = Mockery::mock(new CmsmsClient($this->guzzle, '00000FFF-0000-F0F0-F0F0-FFFFFFFFFFFF'));
        $this->message = CmsmsMessage::create('Message content')->originator('APPNAME');
    }

    public function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /** @test */
    public function it_can_be_instantiated()
    {
        $this->assertInstanceOf(CmsmsClient::class, $this->client);
        $this->assertInstanceOf(CmsmsMessage::class, $this->message);
    }

    /**
     * @test
     * @doesNotPerformAssertions
     */
    public function it_can_send_message()
    {
        $this->guzzle
            ->shouldReceive('request')
            ->once()
            ->andReturn(new Response(200, [], '{"details": "Created 1 message(s)", "errorCode": 0}'));

        $this->client->send($this->message, '00301234');
    }

    /**
     * @test
     * @doesNotPerformAssertions
     */
    public function it_sets_a_default_originator_if_none_is_set()
    {
        $message = Mockery::mock(CmsmsMessage::create('Message body'));
        $message->shouldReceive('originator')
                ->once()
                ->with($this->app['config']['services.cmsms.originator']);

        $this->guzzle
            ->shouldReceive('request')
            ->once()
            ->andReturn(new Response(200, [], '{"details": "Created 1 message(s)", "errorCode": 0}'));

        $this->client->send($message, '00301234');
    }

    /** @test */
    public function it_throws_exception_on_error_response()
    {
        $this->expectException(CouldNotSendNotification::class);

        $this->guzzle
            ->shouldReceive('request')
            ->once()
            ->andReturn(new Response(200, [], '{"details": "Some error message", "errorCode": 1}'));

        $this->client->send($this->message, '00301234');
    }
}
