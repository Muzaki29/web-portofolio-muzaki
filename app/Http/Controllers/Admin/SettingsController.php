<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Inertia\Inertia;

/** Profil & Kontak: key/value settings described in config/cms.php. */
class SettingsController extends Controller
{
    public function edit()
    {
        return Inertia::render('settings', [
            'fields' => config('cms.settings'),
            'values' => (object) Setting::query()->get()->mapWithKeys(fn (Setting $s) => [$s->key => $s->value])->all(),
        ]);
    }

    public function update(Request $request)
    {
        $rules = [];
        foreach (config('cms.settings') as $f) {
            $base = match ($f['type']) {
                'url' => ['url', 'max:255'],
                'email' => ['email', 'max:255'],
                'textarea' => ['string', 'max:3000'],
                default => ['string', 'max:255'],
            };
            if ($f['translatable'] ?? false) {
                $rules["values.{$f['name']}.id"] = ['nullable', ...$base];
                $rules["values.{$f['name']}.en"] = ['nullable', ...$base];
            } else {
                $rules["values.{$f['name']}"] = ['nullable', ...$base];
            }
        }

        foreach ($request->validate($rules)['values'] ?? [] as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }
        Setting::flush();

        return back()->with('success', 'Profil & kontak disimpan.');
    }
}
