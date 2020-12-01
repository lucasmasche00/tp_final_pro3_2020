<?php
namespace App\Controllers;
use App\Services\PedidoService;
use App\Interfaces\IBaseABM;

class PedidoController implements IBaseABM
{
    public function GetOne($request, $response, array $args)
    {
        $jSend = PedidoService::GetOne($request, $response, $args);
        $response->getBody()->write($jSend);
        return $response;
    }

    public function GetAll($request, $response, array $args)
    {
        $jSend = PedidoService::GetAll($request, $response, $args);
        $response->getBody()->write($jSend);
        return $response;
    }

    public function Insert($request, $response, array $args)
    {
        $jSend = PedidoService::Insert($request, $response, $args);
        $response->getBody()->write($jSend);
        return $response;
    }

    public function Update($request, $response, array $args)
    {
        $jSend = PedidoService::Update($request, $response, $args);
        $response->getBody()->write($jSend);
        return $response;
    }

    public function Delete($request, $response, array $args)
    {
        $jSend = PedidoService::Delete($request, $response, $args);
        $response->getBody()->write($jSend);
        return $response;
    }

    public function GetPendientes($request, $response, array $args)
    {
        $jSend = PedidoService::GetPendientes($request, $response, $args);
        $response->getBody()->write($jSend);
        return $response;
    }

    public function AsignarAPreparador($request, $response, array $args)
    {
        $jSend = PedidoService::AsignarAPreparador($request, $response, $args);
        $response->getBody()->write($jSend);
        return $response;
    }

    public function TerminarPreparacionDePedido($request, $response, array $args)
    {
        $jSend = PedidoService::TerminarPreparacionDePedido($request, $response, $args);
        $response->getBody()->write($jSend);
        return $response;
    }

    public function EntregarPedido($request, $response, array $args)
    {
        $jSend = PedidoService::EntregarPedido($request, $response, $args);
        $response->getBody()->write($jSend);
        return $response;
    }

    public function GetAlimentoMasVendido($request, $response, array $args)
    {
        $jSend = PedidoService::GetAlimentoMasVendido($request, $response, $args);
        $response->getBody()->write($jSend);
        return $response;
    }

    public function GetAlimentoMenosVendido($request, $response, array $args)
    {
        $jSend = PedidoService::GetAlimentoMenosVendido($request, $response, $args);
        $response->getBody()->write($jSend);
        return $response;
    }
    
    public function GetPedidosFueraDeTiempo($request, $response, array $args)
    {
        $jSend = PedidoService::GetPedidosFueraDeTiempo($request, $response, $args);
        $response->getBody()->write($jSend);
        return $response;
    }
    
    public function GetPedidosCancelados($request, $response, array $args)
    {
        $jSend = PedidoService::GetPedidosCancelados($request, $response, $args);
        $response->getBody()->write($jSend);
        return $response;
    }
}
?>