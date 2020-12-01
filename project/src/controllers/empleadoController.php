<?php
namespace App\Controllers;
use App\Services\EmpleadoService;
use App\Interfaces\IBaseABM;

class EmpleadoController implements IBaseABM
{
    public function GetOne($request, $response, array $args)
    {
        $jSend = EmpleadoService::GetOne($request, $response, $args);
        $response->getBody()->write($jSend);
        return $response;
    }

    public function GetAll($request, $response, array $args)
    {
        $jSend = EmpleadoService::GetAll($request, $response, $args);
        $response->getBody()->write($jSend);
        return $response;
    }

    public function Insert($request, $response, array $args)
    {
        $jSend = EmpleadoService::Insert($request, $response, $args);
        $response->getBody()->write($jSend);
        return $response;
    }

    public function Update($request, $response, array $args)
    {
        $jSend = EmpleadoService::Update($request, $response, $args);
        $response->getBody()->write($jSend);
        return $response;
    }

    public function Delete($request, $response, array $args)
    {
        $jSend = EmpleadoService::Delete($request, $response, $args);
        $response->getBody()->write($jSend);
        return $response;
    }

    public function GetCantidadOperacionesPorSector($request, $response, array $args)
    {
        $jSend = EmpleadoService::GetCantidadOperacionesPorSector($request, $response, $args);
        $response->getBody()->write($jSend);
        return $response;
    }

    public function GetCantidadOperacionesPorSectorYEmpleado($request, $response, array $args)
    {
        $jSend = EmpleadoService::GetCantidadOperacionesPorSectorYEmpleado($request, $response, $args);
        $response->getBody()->write($jSend);
        return $response;
    }

    public function GetCantidadOperacionesPorEmpleado($request, $response, array $args)
    {
        $jSend = EmpleadoService::GetCantidadOperacionesPorEmpleado($request, $response, $args);
        $response->getBody()->write($jSend);
        return $response;
    }
}
?>