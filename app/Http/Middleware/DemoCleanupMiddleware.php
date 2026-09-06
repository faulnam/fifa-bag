<?php

namespace App\Http\Middleware;

use App\Services\DemoCleanupService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class DemoCleanupMiddleware
{
    public function __construct(
        protected DemoCleanupService $demoCleanupService
    ) {}

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Throttled cleanup check every 30 seconds
        try {
            if (! Cache::has('last_demo_cleanup_check')) {
                Cache::put('last_demo_cleanup_check', true, 30);
                $this->demoCleanupService->cleanupExpired();
            }
        } catch (Throwable) {
            // Silently ignore if cache/db is momentarily busy
        }

        return $next($request);
    }
}
