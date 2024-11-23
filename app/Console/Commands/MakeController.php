<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeController extends Command
{
    protected $signature = 'make:controller-basic {name}';
    protected $description = 'Genera un controlador con funciones básicas (index, show, create, store, edit, update, destroy)';

    public function handle()
    {
        $name = $this->argument('name');
        $controllerPath = app_path("Http/Controllers/{$name}.php");

        // Verificar si el controlador ya existe
        if (File::exists($controllerPath)) {
            // Crear un backup con la fecha y hora actual
            $backupPath = app_path("Http/Controllers/{$name}" . '.php' . now()->format('YmdHis'));

            // Renombrar el archivo para hacer el respaldo
            File::move($controllerPath, $backupPath);
            $this->info("El controlador {$name} ya existe. Se ha realizado una copia de seguridad en: {$backupPath}");
        }

        $stubPath = app_path('Console/Commands/stubs/maker/controller.stub'); 
        // Obtener la plantilla desde el archivo .stub
        $stub = File::get($stubPath);

        // Reemplazar el marcador de posición con el nombre de la clase
        $controllerContent = str_replace('{{className}}', $name, $stub);

        // Crear el nuevo archivo del controlador
        File::put($controllerPath, $controllerContent);

        $this->info("Controlador {$name} creado con éxito en {$controllerPath}.");
    }
}
