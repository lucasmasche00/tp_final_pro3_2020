<?php
namespace App\Models;
use \Firebase\JWT\JWT;

class Token
{
    private static $llave = '11d6736f75956c584857663fc8720ccb59899492';

    public static function CrearToken($email, $tipo, $ocupacion)
    {
        $key = self::$llave;
        $payload = array(
            "email" => $email,
            "tipo" => $tipo,
            "ocupacion" => $ocupacion
        );
        
        return JWT::encode($payload, $key);
    }

    public static function DecodificarToken($jwt)
    {
        return JWT::decode($jwt, self::$llave, array('HS256'));
    }
}
?>