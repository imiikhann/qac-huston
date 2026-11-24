<?php

namespace App\Filament\Resources\Tickets\Pages;

use App\Events\TicketAdminReplyCreated;
use App\Filament\Resources\Tickets\TicketResource;
use App\Mail\TicketReplyMail;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class EditTicket extends EditRecord
{
    protected static string $resource = TicketResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('reply')
                ->label('Reply to Customer')
                ->icon(Heroicon::OutlinedEnvelope)
                ->color('success')
                ->modalHeading(fn (): string => 'Reply to ' . $this->record->name)
                ->modalDescription('The customer will receive your reply by email.')
                ->modalSubmitActionLabel('Send Reply')
                ->form([
                    Textarea::make('message')
                        ->label('Your reply')
                        ->required()
                        ->rows(5)
                        ->placeholder('Type your reply to the customer...')
                        ->columnSpanFull(),
                    Select::make('status')
                        ->label('Set ticket status after replying')
                        ->options([
                            'open' => 'Open',
                            'pending' => 'Pending (awaiting customer)',
                            'resolved' => 'Resolved',
                            'closed' => 'Closed',
                        ])
                        ->default('pending')
                        ->required()
                        ->columnSpanFull(),
                ])
                ->action(function (array $data, $record): void {
                    $reply = $record->messages()->create([
                        'sender' => 'admin',
                        'message' => $data['message'],
                    ]);

                    $record->update([
                        'status' => $data['status'],
                        'last_message_at' => now(),
                    ]);

                    // Real-time: push the reply to the customer's chat
                    // widget instantly over WebSocket (Reverb).
                    try {
                        broadcast(new TicketAdminReplyCreated($record, $reply));
                    } catch (\Throwable $e) {
                        Log::error('Ticket reply broadcast failed', [
                            'ticket' => $record->uuid,
                            'error' => $e->getMessage(),
                        ]);
                    }

                    // Queue the email so the admin UI stays instant.
                    $email = $record->email;
                    try {
                        Mail::to($email)->queue(new TicketReplyMail($record, $reply));
                    } catch (\Throwable $e) {
                        Log::error('Ticket reply email queue failed', [
                            'ticket' => $record->uuid,
                            'error' => $e->getMessage(),
                        ]);
                    }

                    Notification::make()
                        ->title('Reply saved & email queued to ' . $email)
                        ->success()
                        ->send();
                }),
            DeleteAction::make(),
        ];
    }
}
