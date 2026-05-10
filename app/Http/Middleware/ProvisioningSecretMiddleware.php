<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ProvisioningSecretMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $secret = config('services.provisioning_secret');

        if (! $secret || $request->header('X-Provisioning-Secret') !== $secret) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $next($request);
    }
}
