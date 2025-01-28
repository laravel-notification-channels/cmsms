<?php

namespace NotificationChannels\Cmsms\Test;

use NotificationChannels\Cmsms\CmsmsMessage;
use NotificationChannels\Cmsms\Exceptions\InvalidMessage;
use PHPUnit\Framework\TestCase;

class CmsmsMessageTest extends TestCase
{
    /** @test */
    public function it_can_be_instantiated()
    {
        $message = CmsmsMessage::create();

        $this->assertInstanceOf(CmsmsMessage::class, $message);
    }

    /** @test */
    public function it_can_accept_body_content_when_created()
    {
        $message = CmsmsMessage::create('Foo');

        $this->assertEquals('Foo', $message->getBody());
    }

    /** @test */
    public function it_supports_create_method()
    {
        $message = CmsmsMessage::create('Foo');

        $this->assertInstanceOf(CmsmsMessage::class, $message);
        $this->assertEquals('Foo', $message->getBody());
    }

    /** @test */
    public function it_can_set_body()
    {
        $message = CmsmsMessage::create('Bar');

        $this->assertEquals('Bar', $message->getBody());
    }

    /** @test */
    public function it_can_set_originator()
    {
        $message = CmsmsMessage::create()->originator('APPNAME');

        $this->assertEquals('APPNAME', $message->getOriginator());
    }

    /** @test */
    public function it_cannot_set_an_empty_originator()
    {
        $this->expectException(InvalidMessage::class);

        CmsmsMessage::create()->originator('');
    }

    /** @test */
    public function it_cannot_set_an_originator_thats_too_long()
    {
        $this->expectException(InvalidMessage::class);

        CmsmsMessage::create()->originator('0123456789ab');
    }

    /** @test */
    public function it_can_set_reference()
    {
        $message = CmsmsMessage::create()->reference('REFERENCE123');

        $this->assertEquals('REFERENCE123', $message->getReference());
    }

    /** @test */
    public function it_cannot_set_an_empty_reference()
    {
        $this->expectException(InvalidMessage::class);

        CmsmsMessage::create()->reference('');
    }

    /** @test */
    public function it_cannot_set_a_reference_thats_too_long()
    {
        $this->expectException(InvalidMessage::class);

        CmsmsMessage::create()->reference('UmSM7h8I1nySJm0A8IqcU3LDswO7ojfJn');
    }

    /** @test */
    public function it_cannot_set_a_reference_that_contains_non_alpha_numeric_values()
    {
        $this->expectException(InvalidMessage::class);

        CmsmsMessage::create()->reference('@#$*A*Sjks87');
    }

    /** @test */
    public function it_can_set_tariff()
    {
        $message = CmsmsMessage::create()->tariff(12);

        $this->assertEquals(12, $message->getTariff());
    }

    /** @test */
    public function it_can_set_an_empty_tariff()
    {
        $message = CmsmsMessage::create()->tariff(0);

        $this->assertEquals(0, $message->getTariff());
    }

    public function it_can_set_multipart()
    {
        $message = CmsmsMessage::create()->multipart(1, 4);

        $this->assertEquals(1, $message->getMinimumNumberOfMessageParts());
        $this->assertEquals(4, $message->getMaximumNumberOfMessageParts());
    }

    public function it_cannot_set_more_than_8_parts_to_multipart()
    {
        $this->expectException(InvalidMessage::class);

        CmsmsMessage::create()->multipart(1, 9);
    }

    /** @test */
    public function it_cannot_have_a_higher_minimum_than_maximum_for_multipart()
    {
        $this->expectException(InvalidMessage::class);

        CmsmsMessage::create()->multipart(4, 3);
    }
}
