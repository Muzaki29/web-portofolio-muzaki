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
            'values' => (object) [
                ...collect(config('cms.settings'))->where('type', 'toggle')->mapWithKeys(fn ($f) => [$f['name'] => Setting::flag($f['name'])])->all(),
                ...Setting::query()->get()->mapWithKeys(fn (Setting $s) => [$s->key => $s->value])->all(),
            ],
        ]);
    }

    public function update(Request $request)
    {
        $rules = [];
        foreach (config('cms.settings') as $f) {
            if ($f['type'] === 'toggle') {
                $rules["values.{$f['name']}"] = ['nullable', 'boolean'];
                continue;
            }
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

        $values = $request->validate($rules)['values'] ?? [];
        foreach (config('cms.settings') as $f) {
            if ($f['type'] === 'toggle' && array_key_exists($f['name'], $values)) {
                $values[$f['name']] = (bool) $values[$f['name']];
            }
        }
        foreach ($values as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }
        Setting::flush();

        return back()->with('success', 'Profil & kontak disimpan.');
    }
}
