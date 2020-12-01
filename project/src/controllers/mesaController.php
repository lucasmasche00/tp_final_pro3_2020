<?php
namespace App\Controllers;
use App\Services\MesaService;
use App\Interfaces\IBaseABM;

class MesaController implements IBaseABM
{
    public function GetOne($request, $response, array $args)
    {
        $jSend = MesaService::GetOne($request, $response, $args);
        $response->getBody()->write($jSend);
        return $response;
    }

    public function GetAll($request, $response, array $args)
    {
        $jSend = MesaService::GetAll($request, $response, $args);
        $response->getBody()->write($jSend);
        return $response;
    }

    public function Insert($request, $response, array $args)
    {
        $jSend = MesaService::Insert($request, $response, $args);
        $response->getBody()->write($jSend);
        return $response;
    }

    public function Update($request, $response, array $args)
    {
        $jSend = MesaService::Update($request, $response, $args);
        $response->getBody()->write($jSend);
        return $response;
    }

    public function Delete($request, $response, array $args)
    {
        $jSend = MesaService::Delete($request, $response, $args);
        $response->getBody()->write($jSend);
        return $response;
    }

    public function CobrarMesa($request, $response, array $args)
    {
        $jSend = MesaService::CobrarMesa($request, $response, $args);
        $response->getBody()->write($jSend);
        return $response;
    }
    
    public function GetMesaMasUsada($request, $response, array $args)
    {
        $jSend = MesaService::GetMesaMasUsada($request, $response, $args);
        $response->getBody()->write($jSend);
        return $response;
    }
    
    public function GetMesaMenosUsada($request, $response, array $args)
    {
        $jSend = MesaService::GetMesaMenosUsada($request, $response, $args);
        $response->getBody()->write($jSend);
        return $response;
    }
    
    public function GetMesaMasFacturo($request, $response, array $args)
    {
        $jSend = MesaService::GetMesaMasFacturo($request, $response, $args);
        $response->getBody()->write($jSend);
        return $response;
    }
    
    public function GetMesaMenosFacturo($request, $response, array $args)
    {
        $jSend = MesaService::GetMesaMenosFacturo($request, $response, $args);
        $response->getBody()->write($jSend);
        return $response;
    }
    
    public function GetMesaMayorFacturaIndividual($request, $response, array $args)
    {
        $jSend = MesaService::GetMesaMayorFacturaIndividual($request, $response, $args);
        $response->getBody()->write($jSend);
        return $response;
    }
    
    public function GetMesaMenorFacturaIndividual($request, $response, array $args)
    {
        $jSend = MesaService::GetMesaMenorFacturaIndividual($request, $response, $args);
        $response->getBody()->write($jSend);
        return $response;
    }

    public function GetMesaFacturoPorFechas($request, $response, array $args)
    {
        $jSend = MesaService::GetMesaFacturoPorFechas($request, $response, $args);
        $response->getBody()->write($jSend);
        return $response;
    }
}
?>