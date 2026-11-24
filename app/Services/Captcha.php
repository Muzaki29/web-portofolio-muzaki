<?php

namespace App\Services;

use Illuminate\Support\Facades\Session;

/**
 * The contact form's math captcha. One module issues the question and checks the answer,
 * so the session keys and the 5-minute expiry rule live in exactly one place.
 */
class Captcha
{
    private const LIFETIME_SECONDS = 300;

    /** Issues a new question such as "3 + 7" and remembers its answer in the session. */
    public function issue(): string
    {
        $first = rand(1, 9);
        $second = rand(1, 9);

        Session::put('captcha_answer', $first + $second);
        Session::put('captcha_generated_at', now()->timestamp); // expiry check

        return "$first + $second";
    }

    /** True when $answer matches the issued question and it is at most 5 minutes old. */
    public function passes(mixed $answer): bool
    {
        $expected = session('captcha_answer');
        $issuedAt = session('captcha_generated_at');

        if (is_null($issuedAt) || (now()->timestamp - intval($issuedAt)) > self::LIFETIME_SECONDS) {
            return false;
        }

        return ! is_null($expected) && intval($answer) === intval($expected);
    }

    /** Forgets the issued question; called after a successful submit so it cannot be replayed. */
    public function forget(): void
    {
        session()->forget(['captcha_answer', 'captcha_generated_at']);
    }
}
