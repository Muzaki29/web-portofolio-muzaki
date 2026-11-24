<?php

namespace Tests\Feature;

use App\Services\Captcha;
use Tests\TestCase;

class CaptchaTest extends TestCase
{
    public function test_an_issued_question_passes_with_its_answer_and_expires_after_five_minutes(): void
    {
        $captcha = app(Captcha::class);

        [$first, $second] = array_map('intval', explode(' + ', $captcha->issue()));
        $answer = $first + $second;

        $this->assertTrue($captcha->passes($answer));
        $this->assertTrue($captcha->passes((string) $answer)); // form fields arrive as strings
        $this->assertFalse($captcha->passes($answer + 1));

        $this->travel(301)->seconds();
        $this->assertFalse($captcha->passes($answer));
    }

    public function test_nothing_passes_before_a_question_is_issued_or_after_it_is_forgotten(): void
    {
        $captcha = app(Captcha::class);
        $this->assertFalse($captcha->passes(0));

        [$first, $second] = array_map('intval', explode(' + ', $captcha->issue()));
        $captcha->forget();

        $this->assertFalse($captcha->passes($first + $second));
    }
}
