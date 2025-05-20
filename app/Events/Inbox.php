<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class Inbox implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;
    public $sender;
    public $receiverType;
    public $receiverId;
    public $senderId;

    public function __construct($message, $sender, $receiverType, $receiverId, $senderId)
    {
        $this->message = $message;
        $this->sender = $sender;
        $this->receiverType = $receiverType;
        $this->receiverId = $receiverId;
        $this->senderId = $senderId;
    }

    public function broadcastOn()
    {
        return new PrivateChannel("inbox.{$this->receiverType}.{$this->receiverId}");
    }

    public function broadcastAs()
    {
        return 'Inbox';
    }

    public function broadcastWith()
{
    return [
        'message' => $this->message,
        'sender' => $this->sender,
        'receiverType' => $this->receiverType,
        'receiverId' => $this->receiverId,
        'senderId' => $this->senderId
    ];
}
}