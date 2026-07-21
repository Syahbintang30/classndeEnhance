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
