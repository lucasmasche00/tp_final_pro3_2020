<?php
namespace App\Services;
use App\Models\Pedido;
use App\Models\Comanda;
use App\Models\Alimento;
use App\Models\Empleado;
use App\Models\Mesa;
use App\Models\Token;
use App\Models\JSend;

class PedidoService
{
    public static function GetOne($request, $response, array $args)
    {
        $jSend = new JSend('error');
        $pedidoId = $args['id'] ?? '';
        if($pedidoId !== '' && is_numeric($pedidoId))
        {
            $lista = Pedido::GetAll();
            
            if(Pedido::IsInList($lista, $pedidoId))
            {
                $pedido = Pedido::FindById($lista, $pedidoId);
                
                $jSend->status = 'success';
                $jSend->data->mensajeExito = $pedido;
            }
            else
            {
                $jSend->message = 'No hay pedido con ese id';
            }
        }
        else
        {
            $jSend->message = 'Id de pedido valido requerido: solo numeros';
        }
        return json_encode($jSend);
    }

    public static function GetAll($request, $response, array $args)
    {
        $jSend = new JSend('success');
        $jSend->data->pedidos = Pedido::GetAll();
        
        return json_encode($jSend);
    }

    public static function Insert($request, $response, $args)
    {
        $jSend = new JSend('error');
        $params = $request->getParsedBody();
        
        $comandaCode = $params['codigoComanda'] ?? '';
        if($comandaCode !== '')
        {
            $listaComandas = Comanda::GetAll();
        
            if(Comanda::IsInList($listaComandas, $comandaCode))
            {
                $alimentoId = $params['alimentoId'] ?? '';
                if($alimentoId !== '' && is_numeric($alimentoId))
                {
                    $listaAlimentos = Alimento::GetAll();
                    
                    if(Alimento::IsInList($listaAlimentos, $alimentoId))
                    {
                        $cantidad = $params['cantidad'] ?? '';
                        if($cantidad !== '' && is_numeric($cantidad))
                        {
                            $pedido = Pedido::Constructor('', 0, $comandaCode, $alimentoId, $cantidad, 'pendiente', '', '');
                            
                            $pedido->Insert();
                            
                            $jSend->status = 'success';
                            $jSend->data->mensajeExito = 'Guardado exitoso';
                        }
                        else
                        {
                            $jSend->message = 'Cantidad valida requerida: solo numeros';
                        }
                    }
                    else
                    {
                        $jSend->message = 'No hay alimento con ese id';
                    }
                }
                else
                {
                    $jSend->message = 'Id de alimento valido requerido: solo numeros';
                }
            }
            else
            {
                $jSend->message = 'No hay comanda con ese id';
            }
        }
        else
        {
            $jSend->message = 'Codigo de comanda valido requerido: debe contener 5 caracteres';
        }
        return json_encode($jSend);
    }
    
    public static function Update($request, $response, array $args)
    {
        $jSend = new JSend('error');
        $params = $request->getParsedBody();
        $pedidoId = $args['id'] ?? '';
        if($pedidoId !== '' && is_numeric($pedidoId))
        {
            $comandaCode = $params['codigoComanda'] ?? false;
            if($comandaCode !== '' || $comandaCode === false)
            {
                $legajo = $params['legajo'] ?? false;
                if($legajo === false || ($legajo !== '' && is_numeric($legajo)))
                {

                    $alimentoId = $params['alimentoId'] ?? false;
                    if($alimentoId === false || ($alimentoId !== '' && is_numeric($alimentoId)))
                    {
                        $cantidad = $params['cantidad'] ?? false;
                        if($cantidad === false || ($cantidad !== '' && is_numeric($cantidad)))
                        {
                            $estado = $params['estado'] ?? false;
                            if($estado === false || $estado === 'pendiente' || $estado === 'en preparacion' || $estado === 'listo para servir' || $estado === 'entregado fuera de tiempo' || $estado === 'cancelado' || $estado === 'entregado')
                            {
                                $lista = Pedido::GetAll();
                                
                                if(Pedido::IsInList($lista, $pedidoId))
                                {
                                    $oldPedido = Pedido::FindById($lista, $pedidoId);
                                    
                                    $legajo = $legajo != false ? $legajo : $oldPedido->legajo;
                                    $comandaCode = $comandaCode != false ? $comandaCode : $oldPedido->comandaCode;
                                    $alimentoId = $alimentoId != false ? $alimentoId : $oldPedido->alimentoId;
                                    $cantidad = $cantidad != false ? $cantidad : $oldPedido->cantidad;
                                    $estado = $estado != false ? $estado : $oldPedido->estado;
                                    
                                    $listaComandas = Comanda::GetAll();
                                    if(Comanda::IsInList($listaComandas, $comandaCode))
                                    {
                                        $listaAlimentos = Alimento::GetAll();
                                        if(Alimento::IsInList($listaAlimentos, $alimentoId))
                                        {
                                            $listaEmpleados = Empleado::GetAll();
                                            if(Empleado::IsInList($listaEmpleados, $legajo))
                                            {
                                                $empleado = Empleado::FindById($listaEmpleados, $legajo);
                                                if($empleado->ocupacion === 'bartender' || $empleado->ocupacion === 'cervecero' || $empleado->ocupacion === 'cocinero')
                                                {
                                                    $pedido = Pedido::Constructor($pedidoId, $legajo, $comandaCode, $alimentoId, $cantidad, $estado, '', '');
                                                
                                                    $pedido->Update();
                                                    
                                                    $jSend->status = 'success';
                                                    $jSend->data->mensajeExito = 'Modificacion exitosa';
                                                }
                                                else
                                                {
                                                    $jSend->message = 'Legajo no pertenece ningun empleado de preparacion: bartender, cervecero o cocinero';
                                                }
                                            }
                                            else
                                            {
                                                $jSend->message = 'Legajo no pertenece ningun empleado';
                                            }
                                        }
                                        else
                                        {
                                            $jSend->message = 'Id de alimento no pertenece ningun alimento';
                                        }
                                    }
                                    else
                                    {
                                        $jSend->message = 'Codigo de comanda no pertenece ningun comanda';
                                    }
                                }
                                else
                                {
                                    $jSend->message = 'No hay pedido con ese id';
                                }
                            }
                            else
                            {
                                $jSend->message = 'Estado valido requerido: pendiente, en preparacion, listo para servir, entregado fuera de tiempo, cancelado o entregado';
                            }
                        }
                        else
                        {
                            $jSend->message = 'Cantidad valida requerida: solo numeros';
                        }
                    }
                    else
                    {
                        $jSend->message = 'Id de alimento valido requerido: solo numeros';
                    }
                }
                else
                {
                    $jSend->message = 'Legajo valido requerido: solo numeros';
                }
            }
            else
            {
                $jSend->message = 'Codigo de comanda valido requerido: debe contener 5 caracteres';
            }
        }
        else
        {
            $jSend->message = 'Id de pedido valido requerido: solo numeros';
        }
        return json_encode($jSend);
    }
    
    public static function Delete($request, $response, array $args)
    {
        $jSend = new JSend('error');
        $pedidoId = $args['id'] ?? '';
        if($pedidoId !== '' && is_numeric($pedidoId))
        {
            $lista = Pedido::GetAll();
            
            if(Pedido::IsInList($lista, $pedidoId))
            {
                $user = Pedido::FindById($lista, $pedidoId);
                $user->Delete();

                $jSend->status = 'success';
                $jSend->data->mensajeExito = 'Borrado exitoso';
            }
            else
            {
                $jSend->message = 'No hay pedido con ese id';
            }
        }
        else
        {
            $jSend->message = 'Id de pedido valido requerido: solo numeros';
        }
        return json_encode($jSend);
    }

    public static function GetPendientes($request, $response, array $args)
    {
        $token = $_SERVER['HTTP_TOKEN'] ?? '';

        $usuarioLogueado = Token::DecodificarToken($token);
        
        $jSend = new JSend('success');

        $listaEmpleados = Empleado::GetAll();
        if(Empleado::IsInListByEmail($listaEmpleados, $usuarioLogueado->email))
        {
            $empleado = Empleado::FindByEmail($listaEmpleados, $usuarioLogueado->email);
            switch ($usuarioLogueado->ocupacion)
            {
                case 'bartender':
                    $pedidos = Pedido::GetPendientesBartender($empleado->legajo);
                break;
                case 'cervecero':
                    $pedidos = Pedido::GetPendientesCervecero($empleado->legajo);
                break;
                case 'cocinero':
                    $pedidos = Pedido::GetPendientesCocinero($empleado->legajo);
                break;
                default:
                    $jSend->status = 'error';
                    $jSend->message = 'Ocupacion de empleado valida requerida';
                break;
            }
            if(count($pedidos) > 0)
                $jSend->data->pendientes = $pedidos;
            else
                $jSend->message = 'No hay pedidos pendientes';
        }
        else
        {
            $jSend->status = 'error';
            $jSend->message = 'Error al validar empleado';
        }
        
        return json_encode($jSend);
    }

    public static function AsignarAPreparador($request, $response, $args)
    {
        $token = $_SERVER['HTTP_TOKEN'] ?? '';

        $usuarioLogueado = Token::DecodificarToken($token);

        $jSend = new JSend('error');
        $params = $request->getParsedBody();
        
        $pedidoId = $params['pedidoId'] ?? '';
        if($pedidoId !== '' && is_numeric($pedidoId))
        {
            $lista = Pedido::GetAll();
            $pedido = Pedido::FindById($lista, $pedidoId);
            
            if($pedido !== false)
            {
                if($pedido->estado === 'pendiente' && $pedido->legajo == 0)
                {
                    $listaEmpleados = Empleado::GetAll();
                    if(Empleado::IsInListByEmail($listaEmpleados, $usuarioLogueado->email))
                    {
                        $empleado = Empleado::FindByEmail($listaEmpleados, $usuarioLogueado->email);
                        
                        $pedido->legajo = $empleado->legajo;
                        $pedido->estado = 'en preparacion';
                        $pedido->horaInicio = date('Y-m-d H:i:s');
                        $pedido->Update();
                        
                        $jSend->status = 'success';
                        $jSend->data->mensajeExito = 'Modificacion exitosa: pedido asignado';
                    }
                    else
                    {
                        $jSend->message = 'Error al validar empleado';
                    }
                }
                else
                {
                    $jSend->message = 'Pedido ya tomado';
                }
            }
            else
            {
                $jSend->message = 'No hay pedido con ese id';
            }
        }
        else
        {
            $jSend->message = 'Numero de pedido valido requerido: solo numeros';
        }
        return json_encode($jSend);
    }

    public static function TerminarPreparacionDePedido($request, $response, $args)
    {
        $jSend = new JSend('error');
        $params = $request->getParsedBody();
        
        $pedidoId = $params['pedidoId'] ?? '';
        if($pedidoId !== '' && is_numeric($pedidoId))
        {
            $lista = Pedido::GetAll();
            $pedido = Pedido::FindById($lista, $pedidoId);
            
            if($pedido !== false)
            {
                if($pedido->estado === 'en preparacion' && $pedido->horaInicio !== '')
                {
                    $pedido->estado = 'listo para servir';
                    $pedido->horaFin = date('Y-m-d H:i:s');
                    $pedido->Update();
                    
                    $jSend->status = 'success';
                    $jSend->data->mensajeExito = 'Modificacion exitosa: pedido listo';
                }
                else
                {
                    $jSend->message = 'El pedido no esta siendo preparado';
                }
            }
            else
            {
                $jSend->message = 'No hay pedido con ese id';
            }
        }
        else
        {
            $jSend->message = 'Numero de pedido valido requerido: solo numeros';
        }
        return json_encode($jSend);
    }

    public static function EntregarPedido($request, $response, $args)
    {
        $jSend = new JSend('error');
        $params = $request->getParsedBody();
        
        $pedidoId = $params['pedidoId'] ?? '';
        if($pedidoId !== '' && is_numeric($pedidoId))
        {
            $lista = Pedido::GetAll();
            $pedido = Pedido::FindById($lista, $pedidoId);
            
            if($pedido !== false)
            {
                if($pedido->estado === 'listo para servir' && $pedido->horaInicio !== '' && $pedido->horaFin !== '')
                {
                    $listaAlimentos = Alimento::GetAll();
                    $alimento = Alimento::FindById($listaAlimentos, $pedido->alimentoId);
                    if($alimento !== false)
                    {
                        $minutosDesdeElPedido = (strtotime(date('Y-m-d H:i:s')) - strtotime($pedido->horaInicio)) / 60;
                        $minutosRestantes = $alimento->minutosDePreparacion - $minutosDesdeElPedido;
                        
                        if($minutosRestantes > 0)
                        {
                            $pedido->estado = 'entregado';
                        }
                        else
                        {
                            $pedido->estado = 'entregado fuera de tiempo';
                        }

                        $mesa = Mesa::GetMesaPorPedido($pedidoId);
                        if($mesa->estado !== 'cerrada')
                        {
                            $mesa->estado = 'con clientes comiendo';
    
                            $pedido->Update();
                            $mesa->Update();
                            
                            $jSend->status = 'success';
                            $jSend->data->mensajeExito = 'Modificacion exitosa: Pedido entregado';
                        }
                        else
                        {
                            $pedido->estado = 'cancelado';
                            $pedido->Update();

                            $jSend->message = 'La mesa esta cerrada';
                        }
                    }
                    else
                    {
                        $jSend->message = 'Error al cambiar de estado el pedido';
                    }
                }
                else
                {
                    $jSend->message = 'El pedido no esta listo para servir';
                }
            }
            else
            {
                $jSend->message = 'No hay pedido con ese id';
            }
        }
        else
        {
            $jSend->message = 'Numero de pedido valido requerido: solo numeros';
        }
        return json_encode($jSend);
    }

    public static function CerrarPedidosPendientes($mesaCode)
    {
        $lista = Pedido::GetPedidosDeMesa($mesaCode);
        
        if(count($lista) > 0)
        {
            foreach ($lista as $pedido)
            {
                if($pedido->estado === 'pendiente' || $pedido->estado === 'en preparacion' || $pedido->estado === 'listo para servir')
                {
                    $pedido->estado = 'cancelado';
                    $pedido->Update();
                }
            }
            return true;
        }
        return false;
    }
    
    public static function GetAlimentoMasVendido($request, $response, array $args)
    {
        $jSend = new JSend('success');
        $jSend->data->alimento = Pedido::GetAlimentoMasVendido();
        
        return json_encode($jSend);
    }

    public static function GetAlimentoMenosVendido($request, $response, array $args)
    {
        $jSend = new JSend('success');
        $jSend->data->alimento = Pedido::GetAlimentoMenosVendido();
        
        return json_encode($jSend);
    }

    public static function GetPedidosFueraDeTiempo($request, $response, array $args)
    {
        $jSend = new JSend('success');
        $jSend->data->pedidos = Pedido::GetPedidosFueraDeTiempo();
        
        return json_encode($jSend);
    }
    
    public static function GetPedidosCancelados($request, $response, array $args)
    {
        $jSend = new JSend('success');
        $jSend->data->pedidos = Pedido::GetPedidosCancelados();
        
        return json_encode($jSend);
    }
}
?>