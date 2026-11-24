<?php

namespace App\Events;

use App\Models\Ticket;
use App\Models\TicketMessage;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

/**
 * Broadcast to the website chat widget as soon as an admin replies
 * from Filament. Public channel per ticket (UUID4 in the name) so the
 * widget needs no Laravel session — the access key already gated the
 * HTTP API, and the UUID is unguessable.
 */
class TicketAdminReplyCreated implements ShouldBroadcastNow
{
    use InteractsWithSockets;

    public function __construct(
        public Ticket $ticket,
        public TicketMessage $message,
    ) {}

    public function broadcastOn(): Channel
    {
        return new Channel('ticket.'.$this->ticket->uuid);
    }

    public function broadcastAs(): string
    {
        return 'admin-reply';
    }

    public function broadcastWith(): array
    {
        return [
            'id' => $this->message->id,
            'sender' => $this->message->sender,
            'message' => $this->message->message,
            'status' => $this->ticket->status,
            'created_at' => $this->message->created_at?->toISOString(),
        ];
    }
}
