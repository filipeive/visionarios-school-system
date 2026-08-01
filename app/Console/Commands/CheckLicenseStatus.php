<?php

namespace App\Console\Commands;

use App\Models\License;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CheckLicenseStatus extends Command
{
    /**
     * O nome e assinatura do comando.
     *
     * @var string
     */
    protected $signature = 'license:check';

    /**
     * A descrição do comando.
     *
     * @var string
     */
    protected $description = 'Verificar o estado das licenças do sistema ZamEdu e sincronizar com servidor central';

    /**
     * Executar o comando.
     */
    public function handle()
    {
        $license = License::first();

        if (!$license) {
            $this->info('Nenhuma licença registada no sistema.');
            return 0;
        }

        // 1. Recalcular estado local baseado em datas
        $oldStatus = $license->status;
        $newStatus = $license->evaluateStatus();

        // 2. Verificar integridade de tempo local (detetar recuo de relógio)
        if ($license->last_ping_at && now()->lessThan($license->last_ping_at)) {
            Log::warning("ZamEdu Security: Detetada alteração no relógio do sistema local. Último ping: {$license->last_ping_at}, Hora atual: " . now());
        }

        // 3. Registar hora de validação (last_ping_at)
        $license->last_ping_at = now();
        $license->save();

        $this->info("Licença avaliada: Status anterior [{$oldStatus}] -> Status atual [{$newStatus}]");

        return 0;
    }
}
