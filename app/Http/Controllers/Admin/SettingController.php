<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function index(): View
    {
        $settings = SiteSetting::all()->pluck('value', 'key')->toArray();

        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request): RedirectResponse
    {
        $group = $request->input('_group', 'general');

        $rules = [];
        $data = $request->except(['_token', '_group']);

        if ($group === 'general') {
            $rules = [
                'site_name' => ['nullable', 'string', 'max:255'],
                'site_tagline' => ['nullable', 'string', 'max:255'],
                'contact_email' => ['nullable', 'email', 'max:255'],
                'contact_phone' => ['nullable', 'string', 'max:50'],
                'announcement_text' => ['nullable', 'string', 'max:500'],
                'announcement_active' => ['nullable', 'in:0,1'],
                'free_shipping_threshold' => ['nullable', 'numeric', 'min:0'],
            ];
        } elseif ($group === 'shipping') {
            $rules = [
                'origin_biteship_area_id' => ['nullable', 'string', 'max:255'],
                'origin_postal_code' => ['nullable', 'string', 'max:10'],
                'origin_city' => ['nullable', 'string', 'max:100'],
                'origin_address' => ['nullable', 'string', 'max:500'],
                'biteship_api_key' => ['nullable', 'string', 'max:255'],
                'biteship_active' => ['nullable', 'in:0,1'],
            ];
        } elseif ($group === 'payment') {
            $rules = [
                'payment_gateway_active' => ['required', 'string', 'in:midtrans,sandbox,manual'],
                'midtrans_server_key' => ['nullable', 'string', 'max:255'],
                'midtrans_client_key' => ['nullable', 'string', 'max:255'],
                'midtrans_is_production' => ['nullable', 'in:0,1'],
            ];
        } elseif ($group === 'social') {
            $rules = [
                'instagram_url' => ['nullable', 'url', 'max:255'],
                'facebook_url' => ['nullable', 'url', 'max:255'],
                'tiktok_url' => ['nullable', 'url', 'max:255'],
                'youtube_url' => ['nullable', 'url', 'max:255'],
                'store_address' => ['nullable', 'string', 'max:500'],
            ];
        }

        $validated = $request->validate($rules);

        foreach ($validated as $key => $value) {
            SiteSetting::set($key, (string) $value, $group);
        }

        return redirect()->route('admin.settings.index', ['tab' => $group])
            ->with('success', "Pengaturan grup '{$group}' berhasil disimpan.");
    }
}
