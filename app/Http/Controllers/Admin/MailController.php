<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\HostingerMail;
use Illuminate\Http\Request;
use Inertia\Inertia;
use RuntimeException;

/**
 * Hostinger Mail in the admin: browse, search, flag, move (Trash at most), compose/reply/forward,
 * download attachments. Permanent deletion is intentionally not offered.
 */
class MailController extends Controller
{
    public function __construct(private HostingerMail $mail) {}

    public function index(Request $request)
    {
        if (! $this->mail->enabled()) {
            return Inertia::render('mail/index', ['enabled' => false]);
        }

        try {
            $mailboxes = $this->mail->mailboxes();
            $mailbox = collect($mailboxes)->firstWhere('resourceId', $request->query('mailbox')) ?? ($mailboxes[0] ?? null);
            if (! $mailbox) {
                return Inertia::render('mail/index', ['enabled' => true, 'error' => 'Token ini belum punya akses ke mailbox mana pun.']);
            }

            $id = $mailbox['resourceId'];
            $folder = (string) $request->query('folder', 'INBOX');
            $page = max(1, (int) $request->query('page', 1));
            $q = trim((string) $request->query('q', ''));
            $filter = in_array($request->query('filter'), ['unread', 'flagged'], true) ? $request->query('filter') : 'all';

            $criteria = array_filter([
                'text' => $q !== '' ? mb_substr($q, 0, 120) : null,
                'flags' => $filter === 'flagged' ? ['\\Flagged'] : null,
            ]);
            $messages = $criteria
                ? $this->mail->search($id, $folder, $criteria, $page)
                : $this->mail->messages($id, $folder, $page);
            if ($filter === 'unread') {
                $messages['data'] = array_values(array_filter($messages['data'], fn ($m) => $m['unseen'] ?? false));
            }

            return Inertia::render('mail/index', [
                'enabled' => true,
                'mailboxes' => $mailboxes,
                'mailbox' => $mailbox,
                'folders' => $this->mail->folders($id),
                'folder' => $folder,
                'q' => $q,
                'filter' => $filter,
                'quota' => rescue(fn () => $this->mail->quota($id), null, false),
                'messages' => $messages,
            ]);
        } catch (RuntimeException $e) {
            return Inertia::render('mail/index', ['enabled' => true, 'error' => $e->getMessage()]);
        }
    }

    /** JSON body of one message for the reading pane; marks it as read. */
    public function show(Request $request, string $mailbox, int $uid)
    {
        $this->authorizeMailbox($mailbox);
        $folder = (string) $request->query('folder', 'INBOX');

        try {
            $body = $this->mail->messageText($mailbox, $folder, $uid);
            if ($request->boolean('markRead', true)) {
                rescue(fn () => $this->mail->flags($mailbox, $folder, $uid, ['\\Seen']), report: false);
            }
        } catch (RuntimeException $e) {
            return response()->json(['message' => $e->getMessage()], 502);
        }

        // HTML is never rendered raw in the admin; send plain text, or HTML reduced to text.
        $text = filled($body['text'] ?? null) ? $body['text'] : trim(html_entity_decode(strip_tags(preg_replace(['/<(script|style)\b[^>]*>.*?<\/\1>/is', '/<(br|\/p|\/div|\/li|\/tr)[^>]*>/i'], ['', "\n"], (string) ($body['html'] ?? '')))));

        return response()->json(['text' => $text]);
    }

    public function flags(Request $request, string $mailbox, int $uid)
    {
        $this->authorizeMailbox($mailbox);
        $v = $request->validate(['folder' => ['required', 'string', 'max:255'], 'seen' => ['nullable', 'boolean'], 'flagged' => ['nullable', 'boolean']]);

        $add = $remove = [];
        foreach (['seen' => '\\Seen', 'flagged' => '\\Flagged'] as $key => $flag) {
            if (array_key_exists($key, $v) && $v[$key] !== null) {
                $v[$key] ? $add[] = $flag : $remove[] = $flag;
            }
        }

        return $this->attempt(fn () => $this->mail->flags($mailbox, $v['folder'], $uid, $add, $remove));
    }

    public function move(Request $request, string $mailbox, int $uid)
    {
        $this->authorizeMailbox($mailbox);
        $v = $request->validate(['folder' => ['required', 'string', 'max:255'], 'target' => ['required', 'string', 'max:255']]);
        // Only into folders that actually exist in this mailbox.
        abort_unless(collect($this->mail->folders($mailbox))->contains('path', $v['target']), 422, 'Folder tujuan tidak dikenal.');

        return $this->attempt(fn () => $this->mail->move($mailbox, $v['folder'], $uid, $v['target']), 'Pesan dipindahkan ke '.$v['target'].'.');
    }

    public function send(Request $request, string $mailbox)
    {
        $this->authorizeMailbox($mailbox);
        $v = $request->validate([
            'to' => ['required', 'array', 'min:1', 'max:20'], 'to.*' => ['email:rfc'],
            'cc' => ['nullable', 'array', 'max:20'], 'cc.*' => ['email:rfc'],
            'subject' => ['required', 'string', 'max:200'],
            'text' => ['required', 'string', 'max:20000'],
            'mode' => ['nullable', 'in:new,reply,forward'],
            'source.uid' => ['required_if:mode,reply,forward', 'integer'],
            'source.folder' => ['required_if:mode,reply,forward', 'string', 'max:255'],
        ]);

        $payload = array_filter([
            'to' => $v['to'],
            'cc' => $v['cc'] ?? null,
            'subject' => $v['subject'],
            'text' => $v['text'],
            'displayName' => 'Muzaki Abdullah Irsyad',
            'inReplyTo' => ($v['mode'] ?? null) === 'reply' ? ['uid' => (int) $v['source']['uid'], 'folder' => $v['source']['folder']] : null,
            'forwardOf' => ($v['mode'] ?? null) === 'forward' ? ['uid' => (int) $v['source']['uid'], 'folder' => $v['source']['folder']] : null,
        ]);

        return $this->attempt(fn () => $this->mail->sendMail($mailbox, $payload), 'Email terkirim.');
    }

    public function attachment(Request $request, string $mailbox, int $uid, string $attachment)
    {
        $this->authorizeMailbox($mailbox);
        try {
            [$body, $type] = $this->mail->attachment($mailbox, (string) $request->query('folder', 'INBOX'), $uid, $attachment);
        } catch (RuntimeException $e) {
            abort(502, $e->getMessage());
        }
        $name = preg_replace('/[^\w.\- ]+/u', '_', (string) $request->query('name', 'lampiran')) ?: 'lampiran';

        // Always a download, never rendered inline in the admin origin.
        return response($body, 200, [
            'Content-Type' => $type,
            'Content-Disposition' => 'attachment; filename="'.$name.'"',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }

    public function refresh()
    {
        $this->mail->flush();

        return back()->with('success', 'Data email diperbarui.');
    }

    private function authorizeMailbox(string $mailbox): void
    {
        abort_unless($this->mail->enabled(), 404);
        abort_unless(collect($this->mail->mailboxes())->contains('resourceId', $mailbox), 404);
    }

    private function attempt(callable $action, ?string $success = null)
    {
        try {
            $action();
        } catch (RuntimeException $e) {
            return back()->withErrors(['mail' => $e->getMessage()]);
        }

        return $success ? back()->with('success', $success) : back();
    }
}
