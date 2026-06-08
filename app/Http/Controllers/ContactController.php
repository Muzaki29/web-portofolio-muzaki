<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\ContactMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class ContactController extends Controller
{
    public function sendEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:5000',
            'captcha' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // Validate Captcha Answer
        $expected = session('captcha_answer');
        $generatedAt = session('captcha_generated_at');

        // Captcha expires after 5 minutes
        if (is_null($generatedAt) || (now()->timestamp - intval($generatedAt)) > 300) {
            return response()->json([
                'success' => false,
                'message' => __('contact.captcha_failed')
            ], 422);
        }

        if (is_null($expected) || intval($request->captcha) !== intval($expected)) {
            return response()->json([
                'success' => false,
                'message' => __('contact.captcha_failed')
            ], 422);
        }

        // Clear captcha session after success
        session()->forget(['captcha_answer', 'captcha_generated_at']);

        try {
            Mail::to(config('mail.from.address'))->send(
                new ContactMail(
                    $request->name,
                    $request->email,
                    $request->subject,
                    $request->message
                )
            );

            return response()->json([
                'success' => true,
                'message' => __('contact.success_message')
            ]);
        } catch (\Exception $e) {
            \Log::error('Email sending failed: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => __('contact.error_message')
            ], 500);
        }
    }
}
