<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TenantProvisioningController extends Controller
{
    public function provision(Request $request): JsonResponse
    {
        $request->validate([
            'subdomain' => ['required', 'string', 'regex:/^[a-z0-9][a-z0-9\-]+[a-z0-9]$/'],
            'business_name' => ['required', 'string'],
            'plan' => ['required', 'string'],
            'admin_email' => ['required', 'email'],
            'admin_name' => ['required', 'string'],
        ]);

        $subdomain = $request->subdomain;
        $centralDomain = config('app.central_domain', 'citas.test');

        if (Tenant::find($subdomain)) {
            return response()->json(['error' => 'Tenant already exists'], 409);
        }

        try {
            $tenant = Tenant::create([
                'id' => $subdomain,
                'business_name' => $request->business_name,
                'plan' => $request->plan,
                'admin_email' => $request->admin_email,
                'admin_name' => $request->admin_name,
            ]);

            $tenant->domains()->create([
                'domain' => $subdomain,
            ]);

            return response()->json([
                'success' => true,
                'tenant_id' => $tenant->id,
                'domain' => "{$subdomain}.{$centralDomain}",
            ], 201);
        } catch (\Exception $e) {
            Log::error('Tenant provisioning failed', ['error' => $e->getMessage(), 'subdomain' => $subdomain]);

            return response()->json(['error' => 'Provisioning failed'], 500);
        }
    }

    public function deprovision(string $subdomain): JsonResponse
    {
        $tenant = Tenant::find($subdomain);

        if (! $tenant) {
            return response()->json(['error' => 'Tenant not found'], 404);
        }

        $tenant->delete();

        return response()->json(['success' => true]);
    }
}
