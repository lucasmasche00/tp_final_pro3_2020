<?php
namespace App\Controllers;
use App\Services\UsuarioService;
use App\Interfaces\IBaseABM;

class UsuarioController implements IBaseABM
{
    public function GenerarToken($request, $response, array $args)
    {
        $jSend = UsuarioService::GenerarToken($request, $response, $args);
        $response->getBody()->write($jSend);
        return $response;
    }

    public function RegistroInsert($request, $response, array $args)
    {
        $jSend = UsuarioService::RegistroInsert($request, $response, $args);
        $response->getBody()->write($jSend);
        return $response;
    }

    public function GetOne($request, $response, array $args)
    {
        $jSend = UsuarioService::GetOne($request, $response, $args);
        $response->getBody()->write($jSend);
        return $response;
    }

    public function GetAll($request, $response, array $args)
    {
        $jSend = UsuarioService::GetAll($request, $response, $args);
        $response->getBody()->write($jSend);
        return $response;
    }

    public function Insert($request, $response, array $args)
    {
        $jSend = UsuarioService::Insert($request, $response, $args);
        $response->getBody()->write($jSend);
        return $response;
    }

    public function Update($request, $response, array $args)
    {
        $jSend = UsuarioService::Update($request, $response, $args);
        $response->getBody()->write($jSend);
        return $response;
    }

    public function Delete($request, $response, array $args)
    {
        $jSend = UsuarioService::Delete($request, $response, $args);
        $response->getBody()->write($jSend);
        return $response;
    }

    public function GetIngresosAlSistema($request, $response, array $args)
    {
        $jSend = UsuarioService::GetIngresosAlSistema($request, $response, $args);
        $response->getBody()->write($jSend);
        return $response;
    }
}
?>