<?php

namespace Tests\Feature;

use App\Events\Inbox;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class InboxEventTest extends TestCase
{
    
    public function test_inbox_event_terdispatch_dengan_data_yang_benar()
    {
        Event::fake();

        $message = 'Halo';
        $sender = 'admin';
        $receiverType = 'customer';
        $receiverId = 10;
        $senderId = 1;

        event(new Inbox(
            $message,
            $sender,
            $receiverType,
            $receiverId,
            $senderId
        ));

        Event::assertDispatched(Inbox::class, function ($event) use (
            $message,
            $sender,
            $receiverType,
            $receiverId,
            $senderId
        ) {
            return
                $event->message === $message &&
                $event->sender === $sender &&
                $event->receiverType === $receiverType &&
                $event->receiverId === $receiverId &&
                $event->senderId === $senderId;
        });
    }

    
    public function test_inbox_event_menggunakan_private_channel_yang_benar()
    {
        $event = new Inbox(
            'Pesan Test',
            'admin',
            'customer',
            5,
            99
        );

        $channel = $event->broadcastOn();

        $this->assertEquals(
            'private-inbox.customer.5',
            $channel->name
        );
    }

    
    public function test_inbox_event_menggunakan_nama_event_yang_benar()
    {
        $event = new Inbox(
            'Pesan',
            'admin',
            'customer',
            1,
            1
        );

        $this->assertEquals('Inbox', $event->broadcastAs());
    }

    
    public function test_inbox_event_mengirim_payload_yang_benar()
    {
        $event = new Inbox(
            'Pesan isi',
            'admin',
            'customer',
            8,
            3
        );

        $payload = $event->broadcastWith();

        $this->assertEquals([
            'message' => 'Pesan isi',
            'sender' => 'admin',
            'receiverType' => 'customer',
            'receiverId' => 8,
            'senderId' => 3,
        ], $payload);
    }
}
