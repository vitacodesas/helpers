<?php

namespace Vitacode\Database\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ExportDatabaseCommand extends Command
{
    protected $signature = 'db:export {--path=database/exports : Ruta donde guardar los archivos} {--conection=mysql : Conexión de la base de datos}';
    protected $description = 'Exporta la base de datos en archivos separados para cada tabla (estructura y datos)';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            //code...
            $outputPath = $this->option('path');

            $connName = $this->option('conection');



            // Imprimir al final de la ejecución el tiempo que tomó la exportación
            $startTime = microtime(true);
            register_shutdown_function(function () use ($startTime) {
                $endTime = microtime(true);
                $elapsed = $endTime - $startTime;
                $this->info("Tiempo de ejecución: $elapsed segundos");
            });
            $this->info("Exportando base de datos...");

            // Aliminando archivos anteriores
            Storage::deleteDirectory($outputPath);


            Storage::makeDirectory($outputPath);

            $database = config("database.connections.{$connName}.database");

            $tables = DB::select("SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = ? AND TABLE_TYPE = 'BASE TABLE'", [$database]);


            // exportar stored procedures
            $procedures = DB::select("SHOW PROCEDURE STATUS WHERE Db = ?", [$database]);
            foreach ($procedures as $procedure) {
                $procedureName = $procedure->Name;
                $procedureDef = DB::select("SHOW CREATE PROCEDURE $procedureName")[0]->{'Create Procedure'};
                $filePath = "$outputPath/procedures/$procedureName.sql";
                Storage::put($filePath, $procedureDef . ";\n");
            }

            // exportar funciones
            $functions = DB::select("SHOW FUNCTION STATUS WHERE Db = ?", [$database]);
            foreach ($functions as $function) {
                $functionName = $function->Name;
                $functionDef = DB::select("SHOW CREATE FUNCTION $functionName")[0]->{'Create Function'};
                $filePath = "$outputPath/functions/$functionName.sql";
                Storage::put($filePath, $functionDef . ";\n");
            }


            foreach ($tables as $table) {
                $tableName = $table->TABLE_NAME;

                if ($tableName == 'export_matriz_emolumentos' || true) {
                    // continue;
                    $this->info("Exportando: $tableName");

                    // Exportar estructura
                    $this->exportTableStructure($tableName, $outputPath);

                    // Exportar datos en lotes
                    $this->exportTableData($tableName, $outputPath);
                    // break;


                }
            }
        } catch (\Throwable $th) {
            dd([
                'error' => $th->getMessage(),
                'line' => $th->getLine(),
                'file' => $th->getFile()
            ]);
        }
        $this->info("Exportación completada. Archivos guardados en $outputPath.");
    }


    protected function exportTableStructure($tableName, $outputPath)
    {
        $structure = DB::select("SHOW CREATE TABLE `$tableName`")[0]->{'Create Table'};
        $filePath = "$outputPath/tables/$tableName-structure.sql";
        Storage::put($filePath, $structure . ";\n");
    }

    protected function getPrimaryKey($tableName)
    {
        $indexInfo = DB::select("SHOW KEYS FROM `$tableName` WHERE Key_name = 'PRIMARY'");
        return $indexInfo[0]->Column_name ?? null; // Suponer 'id' si no hay clave primaria
    }

    protected function getFirstColumn($tableName)
    {
        // Obtener la primera columna de la tabla usando SHOW COLUMNS
        $columns = DB::select("SHOW COLUMNS FROM `$tableName`");
        return $columns[0]->Field ?? null;
    }


    protected function exportTableData($tableName, $outputPath)
    {
        $filePath = "$outputPath/data/data-$tableName.sql";

        // Determinar la columna para ordenar
        $orderColumn = $this->getPrimaryKey($tableName) ?? $this->getFirstColumn($tableName);
        
        $rows = DB::table($tableName)
        ->orderBy($orderColumn)
        ->limit(1000)->get();
        
        $inserts = [];
        foreach ($rows as $row) {
            $values = array_map(fn($value) => is_null($value) ? 'NULL' : DB::getPdo()->quote($value), (array)$row);
            $inserts[] = '(' . implode(', ', $values) . ')';
        }
        
        if (empty($inserts)) {
            return;
        }
        $sql = "INSERT INTO `$tableName` VALUES " . implode(",\n", $inserts) . ";\n";
        Storage::append($filePath, $sql);
    }

    

    
}
