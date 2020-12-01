<?php
namespace App\Controllers;
use App\Services\ComandaService;
use App\Interfaces\IBaseABM;

class ComandaController implements IBaseABM
{
    public function GetOne($request, $response, array $args)
    {
        $jSend = ComandaService::GetOne($request, $response, $args);
        $response->getBody()->write($jSend);
        return $response;
    }

    public function GetAll($request, $response, array $args)
    {
        $jSend = ComandaService::GetAll($request, $response, $args);
        $response->getBody()->write($jSend);
        return $response;
    }

    public function Insert($request, $response, array $args)
    {
        $jSend = ComandaService::Insert($request, $response, $args);
        $response->getBody()->write($jSend);
        return $response;
    }

    public function Update($request, $response, array $args)
    {
        $jSend = ComandaService::Update($request, $response, $args);
        $response->getBody()->write($jSend);
        return $response;
    }

    public function Delete($request, $response, array $args)
    {
        $jSend = ComandaService::Delete($request, $response, $args);
        $response->getBody()->write($jSend);
        return $response;
    }
    
    public function CrearComanda($request, $response, array $args)
    {
        $jSend = ComandaService::CrearComanda($request, $response, $args);
        $response->getBody()->write($jSend);
        return $response;
    }

    public function ConsultarComanda($request, $response, array $args)
    {
        $jSend = ComandaService::ConsultarComanda($request, $response, $args);
        $response->getBody()->write($jSend);
        return $response;
    }
}
?>