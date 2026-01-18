<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\Transaction;

class StatusNotification extends Notification
{
    use Queueable;

    protected $transaction;
    protected $message;
    protected $title;

    public function __construct(Transaction $transaction, $title, $message)
    {
        $this->transaction = $transaction;
        $this->title = $title;
        $this->message = $message;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => $this->title,
            'message' => $this->message,
            'transaction_id' => $this->transaction->id,
            'status' => $this->transaction->status,
            'link' => route('tracking', ['id' => $this->transaction->id]),
        ];
    }
}
