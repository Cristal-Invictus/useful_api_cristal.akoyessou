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
    public function handle(Request $request, Closure $next, $moduleName)
    {
        $module = Module::where('name', $moduleName)->first();

        if (!$module) {
            return response()->json(['message' => 'Module not found'], 404);
        }

        $userModule = UserModule::where('user_id', $request->user()->id)
                                ->where('module_id', $module->id)
                                ->first();

        if (!$userModule || !$userModule->active) {
            return response()->json(['message' => 'Module not activated'], 403);
        }

        return $next($request);
    }
}
