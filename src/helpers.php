<?php

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Vitacode\Helpers\Types\ResponseUploadFile;

function uploadFile(String $folder, UploadedFile $file, $nameFile = null): ResponseUploadFile
{
    try {
        // Definir el nombre del archivo
        $name = '';
        if ($nameFile == null || strlen($file->getClientOriginalName()) >= 170) {
            $originalname = (strlen($file->getClientOriginalName()) >= 170) ? substr($file->getClientOriginalName(), 0, 170) : $file->getClientOriginalName();
            $name = time() . $originalname;
        } else {
            $name = $nameFile;
        }
        // cargar el archivo al disco correspondiente
        $path = "flexmaker/{$folder}{$name}";
        $resp_upload = Storage::put($path, File::get($file), [
            'visibility' => 'public',
        ]);
        if (!$resp_upload) {
            throw new Exception("No se pudo cargar el archivo");
        }

        $data = [
            'url' => Storage::url($path),
            'path' => $path,
            'name' => $name,
            'disk' => config('filesystems.default'),
        ];
        return new ResponseUploadFile(true, $data, "Archivo cargado correctamente", $path, Storage::url($path));
    } catch (\Throwable $th) {
        return new ResponseUploadFile(false, message: $th->getMessage());
    }
}

function removeFile($path)
{
    try {

        if (!Storage::exists($path)) {
            throw new Exception("El archivo no existe");
        }
        $resp_delete = Storage::delete($path);
        if (!$resp_delete) {
            throw new Exception("No se pudo eliminar el archivo");
        }
        return [
            'status' => true,
            'message' => "Archivo eliminado correctamente"
        ];
    } catch (\Throwable $th) {
        return [
            'status' => false,
            'message' => $th->getMessage()
        ];
    }
}