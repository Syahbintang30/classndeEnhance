<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Mailtrap\Helper\ResponseHelper;
use Mailtrap\MailtrapClient;
use Mailtrap\Mime\MailtrapEmail;
use Symfony\Component\Mime\Address;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('send-mail {--to=syahbintang30@gmail.com}', function () {
    $toEmail = $this->option('to');
    $apiKey = env('MAILTRAP_API_KEY', env('MAIL_PASSWORD'));

    if (empty($apiKey) || $apiKey === '<YOUR_API_TOKEN>') {
        $this->error('MAILTRAP_API_KEY is missing in your .env file!');
        $this->info('Please add MAILTRAP_API_KEY=your_mailtrap_api_token in your .env file.');
        return 1;
    }

    $senderAddress = env('MAIL_FROM_ADDRESS', 'hello@demomailtrap.co');
    $senderName = env('MAIL_FROM_NAME', 'Guitarclassbynde');

    $this->info("Sending test email via Mailtrap Real Sending API to {$toEmail}...");

    try {
        $email = (new MailtrapEmail())
            ->from(new Address($senderAddress, $senderName))
            ->to(new Address($toEmail))
            ->subject('You are awesome! - Guitarclassbynde')
            ->category('Integration Test')
            ->text('Congrats for sending test email with Mailtrap Sending API!')
        ;

        $response = MailtrapClient::initSendingEmails(
            apiKey: $apiKey
        )->send($email);

        $this->info('Email sent successfully!');
        dump(ResponseHelper::toArray($response));
        return 0;
    } catch (\Throwable $e) {
        $this->error('Failed to send email: ' . $e->getMessage());
        return 1;
    }
})->purpose('Send test email with Mailtrap Real Sending API');

Artisan::command('send-invoice {--to=syahbintang30@gmail.com}', function () {
    $toEmail = $this->option('to');
    $user = \App\Models\User::where('email', $toEmail)->first() ?: new \App\Models\User([
        'name' => 'Syah Bintang',
        'email' => $toEmail,
    ]);

    $package = \App\Models\Package::first() ?: new \App\Models\Package([
        'name' => 'Mastering Acoustic & Electric Guitar',
        'description' => 'Complete step-by-step video lessons and 1-on-1 coaching session access.',
    ]);

    $txn = new \App\Models\Transaction([
        'order_id' => 'nde-' . strtoupper(\Illuminate\Support\Str::random(8)),
        'user_id' => $user->id ?? 1,
        'package_id' => $package->id ?? 1,
        'method' => 'Midtrans (QRIS / Bank Transfer)',
        'amount' => 499000,
        'original_amount' => 549000,
        'status' => 'settlement',
    ]);
    $txn->setRelation('package', $package);

    $this->info("Sending sample invoice email to {$toEmail}...");

    try {
        $user->notify(new \App\Notifications\SendPaymentInvoiceNotification($txn));
        $this->info('Invoice email sent successfully!');
        return 0;
    } catch (\Throwable $e) {
        $this->error('Failed to send invoice email: ' . $e->getMessage());
        return 1;
    }
})->purpose('Send sample payment invoice email');
