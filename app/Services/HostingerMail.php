<?php

namespace App\Services;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use RuntimeException;

/**
 * Client for the Hostinger Mail API (https://api.mail.hostinger.com, Bearer token) used by the admin.
 * Reads, searches, flags, moves (to Trash at most) and sends mail. It never deletes permanently.
 */
class HostingerMail
{
    public function enabled(): bool
    {
        return filled(config('services.hostinger_mail.token'));
    }

    private function http(): PendingRequest
    {
        return Http::baseUrl(rtrim((string) config('services.hostinger_mail.base_url'), '/'))
            ->withToken((string) config('services.hostinger_mail.token'))
            ->acceptJson()
            ->timeout(10)
            ->retry(1, 300, throw: false);
    }

    /** GET a path and return the decoded "data" envelope (plus pagination when present). */
    private function get(string $path, array $query = []): array
    {
        return $this->decode($this->http()->get($path, $query));
    }

    private function send_(string $method, string $path, array $body = []): array
    {
        return $this->decode($this->http()->{$method}($path, $body));
    }

    private function decode(\Illuminate\Http\Client\Response $response): array
    {
        if ($response->failed()) {
            throw new RuntimeException(match ($response->status()) {
                401, 403 => 'Token Hostinger Mail ditolak. Periksa HOSTINGER_MAIL_TOKEN.',
                404 => 'Mailbox, folder, atau pesan tidak ditemukan.',
                422 => 'Data tidak valid: '.($response->json('message') ?? 'periksa isian.'),
                429 => 'Terlalu banyak permintaan ke Hostinger. Coba lagi sebentar.',
                default => 'Hostinger Mail tidak merespons (HTTP '.$response->status().').',
            });
        }

        return $response->json() ?? [];
    }

    /** Mailboxes the token can read: [['resourceId' => ..., 'address' => ...]]. Cached 10 minutes. */
    public function mailboxes(): array
    {
        return Cache::remember('hostinger.mailboxes', 600, fn () => $this->get('/api/v1/me')['data']['mailboxes'] ?? []);
    }

    public function folders(string $mailbox): array
    {
        return Cache::remember("hostinger.folders.$mailbox", 60, fn () => $this->get('/api/v1/mailboxes/'.rawurlencode($mailbox).'/folders', ['perPage' => 100])['data'] ?? []);
    }

    public function quota(string $mailbox): ?array
    {
        return Cache::remember("hostinger.quota.$mailbox", 600, fn () => $this->get('/api/v1/mailboxes/'.rawurlencode($mailbox).'/quota')['data'] ?? null);
    }

    /** @return array{data: array, pagination: array} */
    public function messages(string $mailbox, string $folder = 'INBOX', int $page = 1, int $perPage = 20): array
    {
        $version = (int) Cache::get("hostinger.listing-version.$mailbox", 0);
        $key = "hostinger.messages.$mailbox.v$version.".md5($folder).".$page.$perPage";

        return Cache::remember($key, 60, function () use ($mailbox, $folder, $page, $perPage) {
            $res = $this->get('/api/v1/mailboxes/'.rawurlencode($mailbox).'/folders/'.rawurlencode($folder).'/messages', [
                'page' => $page, 'perPage' => min($perPage, 100), 'sort' => '-date',
            ]);

            return ['data' => $res['data'] ?? [], 'pagination' => $res['pagination'] ?? []];
        });
    }

    /** Plain text (or HTML) body of one message. Not cached: opened on demand. */
    public function messageText(string $mailbox, string $folder, int $uid): array
    {
        return $this->get('/api/v1/mailboxes/'.rawurlencode($mailbox).'/folders/'.rawurlencode($folder).'/messages/'.$uid.'/text')['data'] ?? [];
    }

    private function base(string $mailbox, string $folder = ''): string
    {
        return '/api/v1/mailboxes/'.rawurlencode($mailbox).($folder !== '' ? '/folders/'.rawurlencode($folder) : '');
    }

    /** Full-text search across headers and body; same shape as messages(). */
    public function search(string $mailbox, string $folder, array $criteria, int $page = 1, int $perPage = 20): array
    {
        $res = $this->http()->post($this->base($mailbox, $folder).'/messages/search?'.http_build_query(['page' => $page, 'perPage' => $perPage, 'sort' => '-date']), $criteria);
        $res = $this->decode($res);

        return ['data' => $res['data'] ?? [], 'pagination' => $res['pagination'] ?? ['page' => $page, 'totalPages' => 1, 'total' => count($res['data'] ?? [])]];
    }

    /** Add/remove IMAP flags (\Seen, \Flagged) on one message. */
    public function flags(string $mailbox, string $folder, int $uid, array $add = [], array $remove = []): void
    {
        $this->send_('patch', $this->base($mailbox, $folder).'/messages/'.$uid, array_filter(['addFlags' => $add, 'removeFlags' => $remove]));
        $this->forgetListing($mailbox);
    }

    public function move(string $mailbox, string $folder, int $uid, string $target): void
    {
        $this->send_('post', $this->base($mailbox, $folder).'/messages/'.$uid.'/move', ['targetFolder' => $target]);
        $this->forgetListing($mailbox);
    }

    /** Send from the mailbox. $payload follows V1SendRequest (to, cc, subject, text, inReplyTo, forwardOf...). */
    public function sendMail(string $mailbox, array $payload): array
    {
        $res = $this->send_('post', $this->base($mailbox).'/send', $payload);
        $this->forgetListing($mailbox);

        return $res['data'] ?? [];
    }

    /** Raw attachment download: [body, contentType]. */
    public function attachment(string $mailbox, string $folder, int $uid, string $attachmentId): array
    {
        $res = $this->http()->get($this->base($mailbox, $folder).'/messages/'.$uid.'/attachments/'.rawurlencode($attachmentId));
        if ($res->failed()) {
            $this->decode($res);
        }

        return [$res->body(), $res->header('Content-Type') ?: 'application/octet-stream'];
    }

    /** Drop cached folder counts and message pages after a change. */
    private function forgetListing(string $mailbox): void
    {
        Cache::forget("hostinger.folders.$mailbox");
        Cache::increment("hostinger.listing-version.$mailbox");
    }

    public function flush(): void
    {
        Cache::forget('hostinger.mailboxes');
        foreach ($this->mailboxesSafe() as $box) {
            Cache::forget('hostinger.folders.'.$box['resourceId']);
            Cache::forget('hostinger.quota.'.$box['resourceId']);
            Cache::increment('hostinger.listing-version.'.$box['resourceId']);
        }
    }

    private function mailboxesSafe(): array
    {
        try {
            return $this->mailboxes();
        } catch (RuntimeException) {
            return [];
        }
    }
}
