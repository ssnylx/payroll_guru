<?php

namespace App\Http\Controllers;

use App\Models\FoundationSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FoundationSettingController extends Controller
{
    public function index()
    {
        $setting = FoundationSetting::first();
        return view('foundation_settings.index', compact('setting'));
    }

    public function update(Request $request)
    {
        $setting = FoundationSetting::first();
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Handle logo upload
        if ($request->hasFile('logo')) {
            if ($setting && $setting->logo_path) {
                Storage::disk('public')->delete($setting->logo_path);
            }
            $logoPath = $request->file('logo')->store('foundation-logos', 'public');
            $validated['logo_path'] = $logoPath;
        }

        if ($setting) {
            $setting->update($validated);
        } else {
            $setting = FoundationSetting::create($validated);
        }

        return redirect()->route('foundation-settings.index')->with('success', 'Pengaturan yayasan berhasil disimpan.');
    }
}
