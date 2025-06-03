<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Inspiring;
use Illuminate\Http\Request;
use Inertia\Middleware;
use Tighten\Ziggy\Ziggy;
use Illuminate\Support\Facades\Log;
use Throwable;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        try {
            [$message, $author] = str(Inspiring::quotes()->random())->explode('-');

            return [
                ...parent::share($request),
                'name' => config('app.name'),
                'quote' => ['message' => trim($message), 'author' => trim($author)],
                'auth' => [
                    'user' => $request->user(),
                ],
                'ziggy' => fn(): array => [
                    ...(new Ziggy)->toArray(),
                    'location' => $request->url(),
                ],
                'sidebarOpen' => ! $request->hasCookie('sidebar_state') || $request->cookie('sidebar_state') === 'true',
                'success' => fn() => $request->session()->get('success'),
                'error' => fn() => $request->session()->get('error'),
            ];
        } catch (Throwable $e) {
            Log::error('Error en HandleInertiaRequests@share: ' . $e->getMessage(), [
                'exception' => $e,
                'route' => $request->route() ? $request->route()->getName() : null,
                'user_id' => $request->user()?->id,
            ]);

            // Retornar datos básicos o vacíos para no romper la aplicación
            return [
                ...parent::share($request),
                'name' => config('app.name'),
                'quote' => ['message' => '', 'author' => ''],
                'auth' => [
                    'user' => null,
                ],
                'ziggy' => [],
                'sidebarOpen' => true,
                'success' => fn() => $request->session()->get('success'),
                'error' => fn() => $request->session()->get('error'),
            ];
        }
    }
}
