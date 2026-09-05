<?php

namespace App\Http\Controllers;

use App\Models\NewsletterSubscriber;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    public function subscribe(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email:rfc,dns', 'max:255'],
        ], [
            'email.required' => 'Silakan masukkan alamat email Anda.',
            'email.email' => 'Format email tidak valid.',
        ]);

        $email = strtolower(trim($validated['email']));

        NewsletterSubscriber::updateOrCreate(
            ['email' => $email],
            [
                'subscribed_at' => now(),
                'is_active' => true,
            ]
        );

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Terima kasih telah berlangganan newsletter fifa!',
            ]);
        }

        return redirect()->back()->with('success', 'Terima kasih telah berlangganan newsletter fifa!');
    }
}
