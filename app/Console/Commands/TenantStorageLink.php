<?php

namespace App\Console\Commands;

use App\Models\Tenant;
use Illuminate\Console\Command;

class TenantStorageLink extends Command
{
    protected $signature = 'tenants:storage-link {--dry-run : Mostrar qué haría sin ejecutar}';

    protected $description = 'Crea el symlink public/storage-{id} para cada tenant que no lo tenga';

    public function handle(): int
    {
        $tenants = Tenant::all();
        $dryRun = $this->option('dry-run');
        $created = 0;
        $skipped = 0;

        foreach ($tenants as $tenant) {
            $id = $tenant->getTenantKey();
            $linkPath = public_path("storage-{$id}");
            $targetPath = storage_path("tenant{$id}/app/public");

            if (is_link($linkPath)) {
                $this->line("  <fg=gray>SKIP</> {$id} → symlink ya existe");
                $skipped++;

                continue;
            }

            if (! is_dir($targetPath)) {
                $this->warn("  WARN {$id} → directorio target no existe: {$targetPath}");

                continue;
            }

            if ($dryRun) {
                $this->line("  <fg=yellow>DRY-RUN</> {$id} → ln -s {$targetPath} {$linkPath}");
                $created++;

                continue;
            }

            if (! symlink($targetPath, $linkPath)) {
                $this->error("  ERROR {$id} → no se pudo crear el symlink (¿ya existe?)");

                continue;
            }

            $this->info("  OK {$id} → symlink creado");
            $created++;
        }

        $this->newLine();
        $this->line("Resultado: {$created} creados, {$skipped} ya existían.");

        return self::SUCCESS;
    }
}
