<?php

namespace NotificationChannels\Cmsms\Test;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Facades\Event;
use Mockery;
use NotificationChannels\Cmsms\CmsmsClient;
use NotificationChannels\Cmsms\CmsmsMessage;
use NotificationChannels\Cmsms\Events\SMSSendingFailedEvent;
use NotificationChannels\Cmsms\Events\SMSSentSuccessfullyEvent;
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

    /** @test */
    public function it_includes_multipart_data()
    {
        $message = clone $this->message;
        $message->multipart(2, 6);

        $messageJson = $this->client->buildMessageJson($message, '00301234');

        $messageJsonObject = json_decode($messageJson);

        $this->assertTrue(isset($messageJsonObject->messages->msg[0]->minimumNumberOfMessageParts));
        $this->assertEquals(2, $messageJsonObject->messages->msg[0]->minimumNumberOfMessageParts);
        $this->assertTrue(isset($messageJsonObject->messages->msg[0]->minimumNumberOfMessageParts));
        $this->assertEquals(6, $messageJsonObject->messages->msg[0]->maximumNumberOfMessageParts);
    }

    /** @test */
    public function it_includes_reference_data()
    {
        $message = clone $this->message;
        $message->reference('ABC');

        $messageJson = $this->client->buildMessageJson($message, '00301234');

        $messageJsonObject = json_decode($messageJson);

        $this->assertTrue(isset($messageJsonObject->messages->msg[0]->reference));
        $this->assertEquals('ABC', $messageJsonObject->messages->msg[0]->reference);
    }

    /** @test */
    public function it_dispatches_a_success_event()
    {
        Event::fake();

        $this->guzzle
            ->shouldReceive('request')
            ->once()
            ->andReturn(new Response(200, [], '{"details": "Created 1 message(s)", "errorCode": 0}'));

        $this->client->send($this->message, '00301234');

        Event::assertDispatched(SMSSentSuccessfullyEvent::class);
    }

    /** @test */
    public function it_dispatches_a_failure_event()
    {
        Event::fake();

        $this->guzzle
            ->shouldReceive('request')
            ->once()
            ->andReturn(new Response(200, [], '{"details": "Some error message", "errorCode": 1}'));

        try {
            $this->client->send($this->message, '00301234');
        } catch (CouldNotSendNotification $e) {
            // Do nothing, we know about the exception
        }

        Event::assertDispatched(SMSSendingFailedEvent::class);
    }
}
