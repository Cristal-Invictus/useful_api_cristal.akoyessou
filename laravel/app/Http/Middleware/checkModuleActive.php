<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Module;
use App\Models\UserModule;


class checkModuleActive
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $moduleId = null): Response
            {
            $user = $request->user();
            if (! $user) {
            return response()->json(['error' => 'Unauthenticated'], 401);
            }

            $moduleId = $moduleId ?? $request->route('module_id');

            if (! $moduleId) {
            return $next($request);
            }

            $isActive = $user->modules()
            ->where('module_id', $moduleId)
            ->wherePivot('active', true)
            ->exists();


            if (! $isActive) {
            return response()->json([
            'error' => 'Module inactive. Please activate this module to use it.'
            ], 403);
            }

            return $next($request);
            }
}
