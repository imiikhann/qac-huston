<?php

namespace App\Events;

use App\Models\Ticket;
use App\Models\TicketMessage;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Support\Str;

/**
 * Broadcast to the admin panel in real time whenever a customer
 * starts a ticket or sends a new message from the website chat widget.
 * ShouldBroadcastNow = sent instantly (no queue hop) so the admin
 * notification appears immediately.
 */
class TicketCustomerMessageCreated implements ShouldBroadcastNow
{
    use InteractsWithSockets;

    public function __construct(
        public Ticket $ticket,
        public TicketMessage $message,
        public string $type = 'new_message',
    ) {}

    public function broadcastOn(): Channel
    {
        // Private: only authenticated admins (Filament session) can listen.
        return new PrivateChannel('admin.tickets');
    }

    public function broadcastAs(): string
    {
        return 'customer-message';
    }

    public function broadcastWith(): array
    {
        return [
            'id' => $this->ticket->id,
            'ticket_uuid' => $this->ticket->uuid,
            'type' => $this->type,
            'name' => $this->ticket->name,
            'email' => $this->ticket->email,
            'subject' => $this->ticket->subject,
            'status' => $this->ticket->status,
            'message' => Str::limit($this->message->message, 200),
            'created_at' => $this->message->created_at?->toISOString(),
        ];
    }
}
