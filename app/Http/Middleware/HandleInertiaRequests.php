<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): string|null
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return [
            ...parent::share($request),
            'auth' => [
                'user' => $request->user(),
                'role' => $request->user() ? $request->user()->getRoleNames()[0] : null
            ],
            'flash' => fn () => [
                'success' => fn () => $request->session()->get('success'),
                'warning' => fn () => $request->session()->get('warning'),
                'error' => fn () => $request->session()->get('error'),
                'info' => fn () => $request->session()->get('info'),
                'value' => fn () => $request->session()->get('value'),
                'kabupaten' => fn () => $request->session()->get('kabupaten'),
                'unit' => fn () => $request->session()->get('unit'),
                'opd' => fn () => $request->session()->get('opd'),
            ],

            'cookies' => fn () => $request->session()->get('cookies'),
        ];
    }
}
