<?php

namespace App\Console\Commands;

use Doctrine\DBAL\Driver\Connection;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class MakeModel extends Command
{
    protected $signature = 'make:model {table}';
    protected $description = 'Genera un modelo con columnas y el trait FormattableModelTrait';

    public function handle()
    {
        $tableName = $this->argument('table');

        // Verificar si la tabla existe en la base de datos
        if (!Schema::hasTable($tableName)) {
            $this->error("La tabla {$tableName} no existe en la base de datos.");
            return;
        }

        // Generar el nombre del modelo en PascalCase
        $modelName = Str::studly(Str::singular($tableName)); // Convierte el nombre de la tabla a PascalCase (singular)

        // Obtener las columnas de la tabla
        $columns = Schema::getColumnListing($tableName);
        // Convertir columnas a formato de propiedad fillable
        $fillableFields = implode(', ', array_map(fn($col) => "'$col'", $columns));


        // Definir la ruta del stub correctamente
        $stubPath = app_path('Console/Commands/stubs/maker/model.stub'); // Ruta del stub

        // Verificar si el archivo stub existe
        if (!file_exists($stubPath)) {
            $this->error("El archivo stub 'model.stub' no se encontró en la ruta {$stubPath}.");
            return;
        }

        // Obtener el contenido del stub
        $stub = file_get_contents($stubPath);

        // Obtener las relaciones
        $relations = $this->generateRelations($tableName);

        // Sustituir las variables en el stub
        $stub = str_replace([
            '{{ traitUse }}',
            '{{ modelName }}',
            '{{ traitName }}',
            '{{ fillableFields }}',
            '{{ relations }}',
            '{{ tableName }}'
        ], [
            'use App\Traits\FormattableModelTrait;',
            $modelName,
            'FormattableModelTrait',
            $fillableFields,
            $relations, 
            $tableName
        ], $stub);

        // Crear el archivo del modelo
        $modelPath = app_path("Models/{$modelName}.php");

        // Verificar si el modelo ya existe
        if (file_exists($modelPath)) {
            // Crear un backup del modelo existente con la fecha actual
            $backupPath = app_path("Models/{$modelName}" . '.php' . now()->format('YmdHis'));

            rename($modelPath, $backupPath); // Renombrar el archivo existente para hacer un respaldo
            $this->info("El modelo {$modelName} ya existe. Se ha realizado una copia de seguridad en: {$backupPath}");
        }
        // Escribir el archivo del modelo
        file_put_contents($modelPath, $stub);
        $this->info("Modelo {$modelName} creado con éxito.");
    }

    public function getForeignKeys($table)
    {
        $foreignKeys = DB::select('SHOW CREATE TABLE ' . $table);

        preg_match_all('/FOREIGN KEY \(`([^`]+)`\) REFERENCES `([^`]+)` \(`([^`]+)`\)/', $foreignKeys[0]->{'Create Table'}, $matches);

        $foreignKeyRelations = [];

        foreach ($matches[1] as $key => $column) {
            $foreignKeyRelations[] = [
                'column' => $column,
                'referenced_table' => $matches[2][$key],
                'referenced_column' => $matches[3][$key],
            ];
        }

        return $foreignKeyRelations;
    }

    public function generateRelations($tableName)
    {
        $belongsTo = []; // Array para las relaciones "belongsTo"
        $hasMany = []; // Array para las relaciones "hasMany"

        // Obtener las restricciones de la base de datos
        $constraints = DB::connection('mysql')
            ->table('information_schema.KEY_COLUMN_USAGE')
            ->where('CONSTRAINT_SCHEMA', env('DB_DATABASE'))
            ->get();

        foreach ($constraints as $constraint) {
            
            if ($constraint->CONSTRAINT_NAME == 'PRIMARY') {
                continue; // Ignorar las claves primarias
            }

            // Obtener las relaciones "belongsTo" (cuando la tabla tiene una clave foránea)
            if ($constraint->TABLE_NAME == $tableName) {
                $belongsTo[] = [
                    "field" => Str::camel(str_replace([$constraint->TABLE_NAME . "_", "_foreign"], ["", ""], $constraint->CONSTRAINT_NAME)),
                    "referenced_table" => $constraint->REFERENCED_TABLE_NAME,
                ];
            }

            // Obtener las relaciones "hasMany" (cuando otra tabla hace referencia a la tabla actual)
            if ($constraint->REFERENCED_TABLE_NAME == $tableName) {
                // Usar Str::plural para obtener el nombre pluralizado de la tabla hija

                $hasMany[] = [
                    "field" => Str::camel(str_replace([$constraint->TABLE_NAME . "_", "_foreign"], ["", ""], $constraint->CONSTRAINT_NAME)),
                    "referencing_table" => Str::plural(Str::camel($constraint->TABLE_NAME)),
                ];
            }
        }

        // Crear las funciones para las relaciones "belongsTo"
        $belongsToRelations = [];
        foreach ($belongsTo as $relation) {
            $belongsToRelations[] = sprintf(
                "\tpublic function %s() \n\t{\n     \treturn \$this->belongsTo(%s::class);\n\t}",
                $relation['field'],
                ucfirst($relation['referenced_table'])
            );
        }

        // Crear las funciones para las relaciones "hasMany"
        $hasManyRelations = [];
        foreach ($hasMany as $relation) {

            $hasManyRelations[] = sprintf(
                "\tpublic function %s() \n\t{\n    \treturn \$this->hasMany(%s::class);\n\t}",
                $relation['referencing_table'],
                ucfirst($relation['referencing_table'])
            );
        }

        // Concatenar las relaciones y devolverlas
        return implode("\n\n", array_merge($belongsToRelations, $hasManyRelations));
    }

}
