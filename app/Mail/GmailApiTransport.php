<?php

namespace App\Mail;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;
use Symfony\Component\Mailer\SentMessage;
use Symfony\Component\Mailer\Transport\AbstractTransport;

class GmailApiTransport extends AbstractTransport
{
    public function __construct(
        private readonly string $clientId,
        private readonly string $clientSecret,
        private readonly string $refreshToken,
    ) {
        parent::__construct();
    }

    protected function doSend(SentMessage $message): void
    {
        if ($this->clientId === '' || $this->clientSecret === '' || $this->refreshToken === '') {
            throw new RuntimeException('Konfigurasi OAuth Gmail API belum lengkap.');
        }

        $tokenResponse = Http::asForm()
            ->acceptJson()
            ->timeout(15)
            ->post('https://oauth2.googleapis.com/token', [
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret,
                'refresh_token' => $this->refreshToken,
                'grant_type' => 'refresh_token',
            ]);

        if (! $tokenResponse->successful()) {
            Log::error('Gmail API OAuth token request failed.', [
                'status' => $tokenResponse->status(),
            ]);

            throw new RuntimeException('Gmail API gagal mendapatkan token akses.');
        }

        $accessToken = $tokenResponse->json('access_token');

        if (! is_string($accessToken) || $accessToken === '') {
            Log::error('Gmail API OAuth response did not include an access token.');

            throw new RuntimeException('Gmail API tidak memberikan token akses yang valid.');
        }

        $rawMessage = rtrim(strtr(base64_encode($message->toString()), '+/', '-_'), '=');
        $sendResponse = Http::withToken($accessToken)
            ->acceptJson()
            ->timeout(20)
            ->post('https://gmail.googleapis.com/gmail/v1/users/me/messages/send', [
                'raw' => $rawMessage,
            ]);

        if (! $sendResponse->successful()) {
            Log::error('Gmail API rejected an outgoing message.', [
                'status' => $sendResponse->status(),
                'reason' => $sendResponse->json('error.message'),
            ]);

            throw new RuntimeException('Gmail API gagal mengirim email pemulihan kata sandi.');
        }

        Log::info('Gmail API accepted an outgoing message.', [
            'message_id' => $sendResponse->json('id'),
        ]);
    }

    public function __toString(): string
    {
        return 'gmail-api://gmail.googleapis.com';
    }
}
