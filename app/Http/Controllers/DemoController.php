<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\DemoCleanupService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DemoController extends Controller
{
    public function __construct(
        protected DemoCleanupService $demoCleanupService
    ) {}

    /**
     * 1-Click Quick Login for demo & testing roles.
     */
    public function quickLogin(Request $request): RedirectResponse
    {
        $role = $request->input('role', 'customer');
        $isDemo = $request->boolean('is_demo', true);

        $emailMap = [
            'super_admin' => $isDemo ? 'demo.superadmin@fifa.test' : 'superadmin@fifa.com',
            'admin' => $isDemo ? 'demo.admin@fifa.test' : 'admin@fifa.com',
            'customer' => $isDemo ? 'demo.customer@fifa.test' : 'customer@fifa.com',
        ];

        $targetEmail = $emailMap[$role] ?? ($isDemo ? 'demo.customer@fifa.test' : 'customer@fifa.com');

        $user = User::where('email', $targetEmail)->first();

        if (! $user) {
            // Fallback find by role
            $user = User::where('role', $role)->where('is_demo', $isDemo)->first();
        }

        if (! $user) {
            return back()->with('error', "Akun {$role} belum di-seed. Silakan jalankan seeder.");
        }

        Auth::login($user, true);
        $request->session()->regenerate();

        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard')->with('success', "Masuk sebagai {$user->name} ({$user->role_label}). Mode Demo aktif!");
        }

        return redirect()->intended(route('home'))->with('success', "Masuk sebagai {$user->name}. Mode Demo aktif!");
    }

    /**
     * Manually reset all changes made by the active demo session immediately.
     */
    public function reset(Request $request): RedirectResponse
    {
        $user = Auth::user();

        if (! $user || ! $user->isDemo()) {
            return back()->with('error', 'Hanya akun demo yang dapat melakukan reset demo data.');
        }

        $count = $this->demoCleanupService->resetAllDemoData($user->id);

        return back()->with('success', "Data demo berhasil direset kembali ke versi awal ({$count} aktivitas dipulihkan).");
    }
}
