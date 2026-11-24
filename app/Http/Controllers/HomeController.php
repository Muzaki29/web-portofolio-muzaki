<?php

namespace App\Http\Controllers;

use App\Services\Captcha;
use App\Services\LandingPage;

/** The public Landing page at `/`. */
class HomeController extends Controller
{
    public function __invoke(LandingPage $landing, Captcha $captcha)
    {
        return view('portfolio', $landing->data() + ['captchaQuestion' => $captcha->issue()]);
    }
}
