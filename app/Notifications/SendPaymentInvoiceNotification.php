<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Transaction;

class SendPaymentInvoiceNotification extends Notification
{
    use Queueable;

    public $transaction;

    public function __construct(Transaction $transaction)
    {
        $this->transaction = $transaction;
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $txn = $this->transaction;
        $orderId = $txn->order_id;
        $package = $txn->package;

        return (new MailMessage)
            ->subject('Invoice #' . $orderId . ' - Payment Confirmed | Guitarclassbynde')
            ->view('emails.invoice', [
                'user' => $notifiable,
                'transaction' => $txn,
                'package' => $package,
                'orderId' => $orderId,
            ]);
    }
}
