<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\ContactMail;
use App\Services\Captcha;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class ContactController extends Controller
{
    public function sendEmail(Request $request, Captcha $captcha)
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

        if (! $captcha->passes($request->captcha)) {
            return response()->json([
                'success' => false,
                'message' => __('contact.captcha_failed')
            ], 422);
        }

        try {
            // Deliver to the contact email set in admin (Profil & Kontak), else the mail sender address.
            Mail::to(\App\Models\Setting::get('contact_email', config('mail.from.address')))->send(
                new ContactMail(
                    $request->name,
                    $request->email,
                    $request->subject,
                    $request->message
                )
            );

            $captcha->forget();

            return response()->json([
                'success' => true,
                'message' => __('contact.success_message')
            ]);
        } catch (\Exception $e) {
            Log::error('Contact email delivery failed', ['exception' => $e::class]);
            
            return response()->json([
                'success' => false,
                'message' => __('contact.error_message')
            ], 500);
        }
    }
}
