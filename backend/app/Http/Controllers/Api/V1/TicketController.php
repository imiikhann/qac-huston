<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Events\TicketCustomerMessageCreated;
use App\Http\Requests\V1\StoreTicketMessageRequest;
use App\Http\Requests\V1\StoreTicketRequest;
use App\Mail\AdminTicketNotificationMail;
use App\Models\Ticket;
use App\Models\TicketMessage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class TicketController extends Controller
{
    /**
     * Start a new support ticket from the website chat widget.
     */
    public function store(StoreTicketRequest $request): JsonResponse
    {
        $ticket = Ticket::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'subject' => Str::limit(trim($request->message), 80, ''),
            'status' => 'open',
        ]);

        $message = $ticket->messages()->create([
            'sender' => 'customer',
            'message' => $request->message,
        ]);

        $ticket->update(['last_message_at' => now()]);

        $this->notifyAdmin($ticket, $message, 'new_ticket');
        $this->broadcastToAdmin($ticket, $message, 'new_ticket');

        return response()->json([
            'message' => 'Your conversation has been started. Our team will reply shortly.',
            'data' => [
                'uuid' => $ticket->uuid,
                'key' => $ticket->access_key,
                'status' => $ticket->status,
                'name' => $ticket->name,
                'email' => $ticket->email,
                'created_at' => $ticket->created_at,
                'messages' => [$message],
            ],
        ], 201);
    }

    /**
     * Fetch the conversation thread (requires the private access key).
     */
    public function show(Request $request, string $uuid): JsonResponse
    {
        $ticket = $this->findAuthorizedTicket($request, $uuid);

        if (! $ticket) {
            return response()->json(['message' => 'Conversation not found.'], 404);
        }

        return response()->json([
            'data' => [
                'uuid' => $ticket->uuid,
                'status' => $ticket->status,
                'name' => $ticket->name,
                'email' => $ticket->email,
                'subject' => $ticket->subject,
                'created_at' => $ticket->created_at,
                'last_message_at' => $ticket->last_message_at,
                'messages' => $ticket->messages,
            ],
        ]);
    }

    /**
     * Customer sends another message in an existing conversation.
     */
    public function reply(StoreTicketMessageRequest $request, string $uuid): JsonResponse
    {
        $ticket = $this->findAuthorizedTicket($request, $uuid);

        if (! $ticket) {
            return response()->json(['message' => 'Conversation not found.'], 404);
        }

        $message = $ticket->messages()->create([
            'sender' => 'customer',
            'message' => $request->message,
        ]);

        $updates = ['last_message_at' => now()];

        // Customer replied after the conversation was closed — reopen it.
        if (in_array($ticket->status, ['resolved', 'closed'], true)) {
            $updates['status'] = 'open';
        }

        $ticket->update($updates);

        $this->notifyAdmin($ticket, $message, 'new_message');
        $this->broadcastToAdmin($ticket, $message, 'new_message');

        return response()->json([
            'message' => 'Message sent.',
            'data' => $message,
        ], 201);
    }

    protected function findAuthorizedTicket(Request $request, string $uuid): ?Ticket
    {
        $ticket = Ticket::where('uuid', $uuid)->first();

        if (! $ticket) {
            return null;
        }

        $key = (string) $request->header('X-Ticket-Key');

        if ($key === '' || ! hash_equals($ticket->access_key, $key)) {
            return null;
        }

        return $ticket;
    }

    /**
     * Push a real-time event to the admin panel (WebSocket via Reverb).
     * Failures are logged but never break the public API — email
     * notification and polling remain as fallbacks.
     */
    protected function broadcastToAdmin(Ticket $ticket, TicketMessage $message, string $type): void
    {
        try {
            broadcast(new TicketCustomerMessageCreated($ticket, $message, $type));
        } catch (\Throwable $e) {
            Log::error('Failed to broadcast ticket message to admin', [
                'ticket' => $ticket->uuid,
                'type' => $type,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Notify the admin by email about a new ticket / new customer message.
     * The email goes through the database queue so the API responds
     * instantly (a queue worker sends the actual email in the background).
     * Failures are logged but never break the public API.
     */
    protected function notifyAdmin(Ticket $ticket, TicketMessage $message, string $type): void
    {
        $adminEmail = config('app.admin_notification_email', 'hello@qachouston.com');

        try {
            Mail::to($adminEmail)->queue(new AdminTicketNotificationMail($ticket, $message, $type));
        } catch (\Throwable $e) {
            Log::error('Failed to queue ticket admin notification', [
                'ticket' => $ticket->uuid,
                'type' => $type,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
