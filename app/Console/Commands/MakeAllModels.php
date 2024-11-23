<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class MakeAllModels extends Command
{
    protected $signature = 'make:all-models';
    protected $description = 'Genera modelos para todas las tablas en la base de datos.';
    // Lista de tablas a excluir
    protected $excludedTables = [
        'users',
        'password_resets',
        'migrations',
        'failed_jobs',
        'personal_access_tokens',
        'cache',
        'cache_locks',
        'jobs',
        'job_batches',
        'password_reset_tokens',
        'sessions',
    ];

    public function handle()
    {
        // Obtener todas las tablas en la base de datos
        $tables = DB::select('SHOW TABLES');

        // Recorrer todas las tablas
        foreach ($tables as $table) {
            $tableName = $table->{'Tables_in_' . env('DB_DATABASE')};

            // Verificar si la tabla está en la lista de exclusión
            if (in_array($tableName, $this->excludedTables)) {
                $this->info("La tabla {$tableName} está excluida. Saltando...");
                continue;
            }

            // Verificar si la tabla existe
            if (!Schema::hasTable($tableName)) {
                $this->error("La tabla {$tableName} no existe en la base de datos.");
                continue;
            }

            // Generar el nombre del modelo en PascalCase
            $modelName = $tableName; // Convierte el nombre de la tabla a PascalCase (singular)

            $this->call('make:model', [
                'table' => "{$modelName}", // El nombre y la ubicación del modelo
            ]);

            $this->info("Modelo {$modelName} creado con éxito.");
        }
    }
}
