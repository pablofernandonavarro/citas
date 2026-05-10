<?php

namespace App\Jobs;

use App\Models\CompanySetting;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;
use Spatie\Permission\Models\Role;
use Stancl\Tenancy\Contracts\TenantWithDatabase;

class SendTenantWelcomeEmail
{
    public function __construct(public TenantWithDatabase $tenant) {}

    public function handle(): void
    {
        $tenantId = $this->tenant->getTenantKey();

        $missingItems = [];

        if (User::count() === 0) {
            $missingItems[] = 'users';
        }

        if (Role::count() === 0) {
            $missingItems[] = 'roles';
        }

        if (CompanySetting::count() === 0) {
            $missingItems[] = 'company_settings';
        }

        if (! empty($missingItems)) {
            Log::error('Tenant provisioning incomplete — skipping welcome email', [
                'tenant' => $tenantId,
                'missing' => $missingItems,
            ]);

            return;
        }

        $adminEmail = $this->tenant->admin_email;

        if (! $adminEmail) {
            Log::warning('Tenant has no admin_email — skipping welcome email', ['tenant' => $tenantId]);

            return;
        }

        $status = Password::sendResetLink(['email' => $adminEmail]);

        if ($status === Password::RESET_LINK_SENT) {
            Log::info('Welcome email sent to tenant admin', ['tenant' => $tenantId, 'email' => $adminEmail]);
        } else {
            Log::error('Failed to send welcome email to tenant admin', [
                'tenant' => $tenantId,
                'email' => $adminEmail,
                'status' => $status,
            ]);
        }
    }
}
