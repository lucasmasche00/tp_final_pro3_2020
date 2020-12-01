<?php
namespace App\Controllers;
use App\Services\EncuestaService;
use App\Interfaces\IBaseABM;

class EncuestaController implements IBaseABM
{
    public function GetOne($request, $response, array $args)
    {
        $jSend = EncuestaService::GetOne($request, $response, $args);
        $response->getBody()->write($jSend);
        return $response;
    }

    public function GetAll($request, $response, array $args)
    {
        $jSend = EncuestaService::GetAll($request, $response, $args);
        $response->getBody()->write($jSend);
        return $response;
    }

    public function Insert($request, $response, array $args)
    {
        $jSend = EncuestaService::Insert($request, $response, $args);
        $response->getBody()->write($jSend);
        return $response;
    }

    public function Update($request, $response, array $args)
    {
        $jSend = EncuestaService::Update($request, $response, $args);
        $response->getBody()->write($jSend);
        return $response;
    }

    public function Delete($request, $response, array $args)
    {
        $jSend = EncuestaService::Delete($request, $response, $args);
        $response->getBody()->write($jSend);
        return $response;
    }
    
    public function GetMejoresComentarios($request, $response, array $args)
    {
        $jSend = EncuestaService::GetMejoresComentarios($request, $response, $args);
        $response->getBody()->write($jSend);
        return $response;
    }
    
    public function GetPeoresComentarios($request, $response, array $args)
    {
        $jSend = EncuestaService::GetPeoresComentarios($request, $response, $args);
        $response->getBody()->write($jSend);
        return $response;
    }
}
?>