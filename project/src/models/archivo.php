<?php
namespace App\Models;

class Archivo
{
    const DIR_IMG = __DIR__ . '/../../img/';

    //=============================== IMAGENES ===============================
    public static function GuardarArchivo($file, $nombre)
    {
        if($file['size'] > 3584000 || !self::IsImage($file['type'])) //3.5MB
            return false;
        $fecha = date('Y-m-d_H-i-s');
        $arrayName = explode(".", $file['name']);
        $extension = '.' . array_reverse($arrayName)[0];

        $origen = $file['tmp_name'];
        $nombreNuevo = $nombre . '_' . $fecha . $extension;
        $destino = self::DIR_IMG . $nombreNuevo;

        return move_uploaded_file($origen, $destino) ? $nombreNuevo : false;
    }

    public static function ModificarArchivo($file, $oldFileNameWithExtension, $newFileName)
    {
        if(file_exists(self::DIR_IMG . $oldFileNameWithExtension) && !self::BorrarArchivo(self::DIR_IMG, $oldFileNameWithExtension))
            return false;
        return self::GuardarArchivo($file, $newFileName);
    }

    public static function BorrarArchivo($fileName)
    {
        return unlink(self::DIR_IMG . $fileName);
    }

    private static function IsImage($mimeType)
    {
        /*// images
            'png' => 'image/png',
            'jpe' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'jpg' => 'image/jpeg',
            'gif' => 'image/gif',
            'bmp' => 'image/bmp',
            'ico' => 'image/vnd.microsoft.icon',
            'tiff' => 'image/tiff',
            'tif' => 'image/tiff',
            'svg' => 'image/svg+xml',
            'svgz' => 'image/svg+xml'*/
        switch ($mimeType)
        {
            case 'image/png':
            case 'image/jpeg':
            case 'image/gif':
            case 'image/bmp':
            case 'image/vnd.microsoft.icon':
            case 'image/tiff':
            case 'image/svg+xml':
                return true;
            default:
                return false;
        }
    }
}
?>