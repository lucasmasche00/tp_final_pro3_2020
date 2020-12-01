<?php
namespace App\Controllers;
use App\Services\AlimentoService;
use App\Interfaces\IBaseABM;

class AlimentoController implements IBaseABM
{
    public function GetOne($request, $response, array $args)
    {
        $jSend = AlimentoService::GetOne($request, $response, $args);
        $response->getBody()->write($jSend);
        return $response;
    }

    public function GetAll($request, $response, array $args)
    {
        $jSend = AlimentoService::GetAll($request, $response, $args);
        $response->getBody()->write($jSend);
        return $response;
    }

    public function Insert($request, $response, array $args)
    {
        $jSend = AlimentoService::Insert($request, $response, $args);
        $response->getBody()->write($jSend);
        return $response;
    }

    public function Update($request, $response, array $args)
    {
        $jSend = AlimentoService::Update($request, $response, $args);
        $response->getBody()->write($jSend);
        return $response;
    }

    public function Delete($request, $response, array $args)
    {
        $jSend = AlimentoService::Delete($request, $response, $args);
        $response->getBody()->write($jSend);
        return $response;
    }
}
?>