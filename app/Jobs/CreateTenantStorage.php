<?php

namespace App\Jobs;

use Stancl\Tenancy\Contracts\TenantWithDatabase;

class CreateTenantStorage
{
    public function __construct(public TenantWithDatabase $tenant) {}

    public function handle(): void
    {
        $base = storage_path('tenant'.$this->tenant->getTenantKey());

        $dirs = [
            $base.'/framework/cache/data',
            $base.'/framework/views',
            $base.'/framework/sessions',
            $base.'/app/public',
            $base.'/logs',
        ];

        foreach ($dirs as $dir) {
            if (! is_dir($dir)) {
                mkdir($dir, 0755, true);
            }
        }

        // Symlink público: public/storage-{tenantId} → storage/tenant{tenantId}/app/public
        $linkPath = public_path('storage-'.$this->tenant->getTenantKey());
        $targetPath = $base.'/app/public';

        if (! is_link($linkPath)) {
            symlink($targetPath, $linkPath);
        }
    }
}
