<?php
namespace App\Services;
use App\Models\Comanda;
use App\Models\Empleado;
use App\Models\Mesa;
use App\Models\Pedido;
use App\Models\Archivo;
use App\Models\Token;
use App\Models\JSend;

class ComandaService
{
    public static function GetOne($request, $response, array $args)
    {
        $jSend = new JSend('error');
        $comandaCode = $args['id'] ?? '';
        if($comandaCode !== '' && strlen($comandaCode) === 5)
        {
            $lista = Comanda::GetAll();
            
            if(Comanda::IsInList($lista, $comandaCode))
            {
                $comanda = Comanda::FindById($lista, $comandaCode);
                
                $jSend->status = 'success';
                $jSend->data->mensajeExito = $comanda;
            }
            else
            {
                $jSend->message = 'No hay comanda con ese codigo';
            }
        }
        else
        {
            $jSend->message = 'Codigo del comanda valido requerido: debe contener 5 caracteres';
        }
        return json_encode($jSend);
    }

    public static function GetAll($request, $response, array $args)
    {
        $jSend = new JSend('success');
        $jSend->data->comandas = Comanda::GetAll();
        
        return json_encode($jSend);
    }

    public static function GetUniqueCode($n)
    {
        $lista = Comanda::GetAll();
        $isUnique = false;
        $cont = 0;
        while (!$isUnique)
        {
            $comandaCode = Comanda::RandomCode($n);
            if(!Comanda::IsInList($lista, $comandaCode))
                $isUnique = true;
            $cont++;
            if($cont > 99)
                return '';
        }
        return $comandaCode;
    }

    public static function Insert($request, $response, $args)
    {
        $jSend = new JSend('error');
        $params = $request->getParsedBody();
        
        $comandaCode = self::GetUniqueCode(5);
        if($comandaCode !== '')
        {
            $mesaCode = $params['codigoMesa'] ?? '';
            if($mesaCode !== '' && strlen($mesaCode) === 5)
            {
                $listaMesas = Mesa::GetAll();
                
                if(Mesa::IsInList($listaMesas, $mesaCode))
                {
                    $lista = Comanda::GetAll();
                    
                    $auxComandas = Comanda::FindComandasByMesa($lista, $mesaCode);
                    $isOnlyMesaAbierta = true;
                    if($auxComandas !== false)
                    {
                        foreach ($auxComandas as $value)
                        {
                            if($value->mesaCode === $mesaCode && $value->horaFin === '')
                            {
                                $isOnlyMesaAbierta = false;
                                break;
                            }
                        }
                    }
                    if($auxComandas !== false && $isOnlyMesaAbierta)
                    {
                        $legajo = $params['legajo'] ?? '';
                        if($legajo !== '' && is_numeric($legajo))
                        {
                            $listaEmpleados = Empleado::GetAll();
                            
                            if(Empleado::IsInList($listaEmpleados, $legajo))
                            {
                                $empleado = Empleado::FindById($listaEmpleados, $legajo);
                                if($empleado->estado === 'activo')
                                {
                                    $comanda = Comanda::Constructor($comandaCode, $legajo, $mesaCode, '', '');
                                    
                                    $comanda->Insert();
                                    
                                    $jSend->status = 'success';
                                    $jSend->data->mensajeExito = "Guardado exitoso: el codigo del comanda es $comandaCode";
                                }
                                else
                                {
                                    $jSend->message = 'El empleado no esta activo';
                                }
                            }
                            else
                            {
                                $jSend->message = 'No hay empleado con ese legajo';
                            }
                        }
                        else
                        {
                            $jSend->message = 'Legajo valido requerido: solo numeros';
                        }
                    }
                    else
                    {
                        $jSend->message = 'Comanda repetida: solo una comanda por mesa';
                    }
                }
                else
                {
                    $jSend->message = 'El codigo de mesa no existe';
                }
            }
            else
            {
                $jSend->message = 'Codigo de mesa valido requerido: debe contener 5 caracteres';
            }
        }
        else
        {
            $jSend->message = 'Hubo un error al conseguir un codigo unico';
        }
        return json_encode($jSend);
    }
    
    public static function Update($request, $response, array $args)
    {
        $jSend = new JSend('error');
        $params = $request->getParsedBody();
        $comandaCode = $args['id'] ?? '';

        if($comandaCode !== '' && strlen($comandaCode) === 5)
        {
            $lista = Comanda::GetAll();

            if(Comanda::IsInList($lista, $comandaCode))
            {
                $legajo = $params['legajo'] ?? false;
                if(($legajo !== '' && is_numeric($legajo)) || $legajo === false)
                {
                    $listaEmpleados = Empleado::GetAll();

                    if(Empleado::IsInList($listaEmpleados, $legajo) || $legajo === false)
                    {
                        if($legajo != false)
                            $empleado = Empleado::FindById($listaEmpleados, $legajo);
                        if($legajo === false || $empleado->estado === 'activo')
                        {
                            $mesaCode = $params['codigoMesa'] ?? false;
                            if(($mesaCode !== '' && strlen($mesaCode) === 5) || $mesaCode === false)
                            {
                                if($mesaCode !== false)
                                {
                                    $auxComandas = Comanda::FindComandasByMesa($lista, $mesaCode);
                                    $isOnlyMesaAbierta = true;
                                    if($auxComandas !== false)
                                    {
                                        foreach ($auxComandas as $value)
                                        {
                                            if($value->mesaCode === $mesaCode && $value->horaFin === '')
                                            {
                                                $isOnlyMesaAbierta = false;
                                                break;
                                            }
                                        }
                                    }
                                }
                                if($mesaCode === false || $isOnlyMesaAbierta)
                                {
                                    $listaMesas = Mesa::GetAll();

                                    if(Mesa::IsInList($listaMesas, $mesaCode) || $mesaCode === false)
                                    {
                                        $oldComanda = Comanda::FindById($lista, $comandaCode);

                                        $legajo = $legajo != false ? $legajo : $oldComanda->legajo;
                                        $mesaCode = $mesaCode != false ? $mesaCode : $oldComanda->mesaCode;

                                        $comanda = Comanda::Constructor($comandaCode, $legajo, $mesaCode, '', '');
                                        
                                        $comanda->Update();

                                        $jSend->status = 'success';
                                        $jSend->data->mensajeExito = 'Modificacion exitosa';
                                    }
                                    else
                                    {
                                        $jSend->message = 'No hay mesa con ese codigo';
                                    }
                                }
                                else
                                {
                                    $jSend->message = 'Comanda repetida: solo una comanda por mesa';
                                }
                            }
                            else
                            {
                                $jSend->message = 'Codigo de mesa valido requerido: debe contener 5 caracteres';
                            }
                        }
                        else
                        {
                            $jSend->message = 'El empleado no esta activo';
                        }
                    }
                    else
                    {
                        $jSend->message = 'No hay empleado con ese legajo';
                    }
                }
                else
                {
                    $jSend->message = 'Legajo valido requerido: solo numeros';
                }
            }
            else
            {
                $jSend->message = 'No hay comanda con ese codigo';
            }
        }
        else
        {
            $jSend->message = 'Codigo de comanda valido requerido: debe contener 5 caracteres';
        }
        return json_encode($jSend);
    }
    
    public static function Delete($request, $response, array $args)
    {
        $jSend = new JSend('error');
        $comandaCode = $args['id'] ?? '';
        if($comandaCode !== '' && strlen($comandaCode) === 5)
        {
            $lista = Comanda::GetAll();
            
            if(Comanda::IsInList($lista, $comandaCode))
            {
                $comanda = Comanda::FindById($lista, $comandaCode);
                $comanda->Delete();

                $jSend->status = 'success';
                $jSend->data->mensajeExito = 'Borrado exitoso';
            }
            else
            {
                $jSend->message = 'Comanda no encontrado';
            }
        }
        else
        {
            $jSend->message = 'Codigo de comanda valido requerido: debe contener 5 caracteres';
        }
        return json_encode($jSend);
    }

    public static function CrearComanda($request, $response, $args)
    {
        $token = $_SERVER['HTTP_TOKEN'] ?? '';
        $usuarioLogeado = Token::DecodificarToken($token);

        $jSend = new JSend('error');
        $params = $request->getParsedBody();
        
        $comandaCode = self::GetUniqueCode(5);
        if($comandaCode !== '')
        {
            $mesaCode = $params['codigoMesa'] ?? '';
            if($mesaCode !== '' && strlen($mesaCode) === 5)
            {
                $listaMesas = Mesa::GetAll();
                
                if(Mesa::IsInList($listaMesas, $mesaCode))
                {
                    $mesa = Mesa::FindById($listaMesas, $mesaCode);
                    if($mesa->estado === 'cerrada')
                    {
                        $listaEmpleados = Empleado::GetAll();
                        
                        $email = $usuarioLogeado->email;
                        $empleado = Empleado::FindByEmail($listaEmpleados, $email);
                        if($empleado !== false)
                        {
                            if($empleado->legajo !== '' && is_numeric($empleado->legajo))
                            {
                                if($empleado->estado === 'activo')
                                {
                                    $file = $_FILES['foto'] ?? false;
                                    if($file === false || $file['size'] > 0)
                                    {
                                        $foto = $file != false ? ($mesa->foto != '' ? Archivo::ModificarArchivo($file, $mesa->foto, $mesaCode) : Archivo::GuardarArchivo($file, $mesaCode)) : $mesa->foto;
                                        
                                        if($foto !== false)
                                        {            
                                            $newMesa = Mesa::Constructor($mesaCode, 'con cliente esperando pedido', $foto);
                                            
                                            $comanda = Comanda::Constructor($comandaCode, $empleado->legajo, $mesaCode, date('Y-m-d H:i:s'), '');
                                            
                                            $newMesa->Update();
                                            $comanda->Insert();
                                            
                                            $jSend->status = 'success';
                                            $jSend->data->mensajeExito = "Guardado exitoso: el codigo del comanda es $comandaCode";
                                        }
                                        else
                                        {
                                            $jSend->message = 'Error al guardar la foto';
                                        }
                                    }
                                    else
                                    {
                                        $jSend->message = 'Imagen valida requerida';
                                    }
                                }
                                else
                                {
                                    $jSend->message = 'El empleado no esta activo';
                                }
                            }
                            else
                            {
                                $jSend->message = 'Legajo valido requerido: solo numeros';
                            }
                        }
                        else
                        {
                            $jSend->message = 'Empleado valido requerido';
                        }
                    }
                    else
                    {
                        $jSend->message = 'La mesa debe estar cerrada para aceptar comandas';
                    }
                }
                else
                {
                    $jSend->message = 'El codigo de mesa no existe';
                }
            }
            else
            {
                $jSend->message = 'Codigo de mesa valido requerido: debe contener 5 caracteres';
            }
        }
        else
        {
            $jSend->message = 'Hubo un error al conseguir un codigo unico';
        }
        return json_encode($jSend);
    }

    public static function ConsultarComanda($request, $response, array $args)
    {
        $jSend = new JSend('error');
        $comandaCode = $args['comandaCode'] ?? '';
        $mesaCode = $args['mesaCode'] ?? '';
        if($comandaCode !== '' && strlen($comandaCode) === 5)
        {
            $lista = Comanda::GetAll();
            
            if(Comanda::IsInList($lista, $comandaCode))
            {
                if($mesaCode !== '' && strlen($mesaCode) === 5)
                {
                    if(Comanda::IsInListByMesa($lista, $mesaCode))
                    {

                        $arrayDeObjs = Comanda::GetMinutosEstimadosDePedidoActivo($comandaCode, $mesaCode);
                        $minutosTiempoMasAlto = 0;
                        $pedidoIdTiempoMasAlto = '';
                        foreach ($arrayDeObjs as $obj)
                        {
                            foreach ($obj as $key => $value)
                            {
                                if($key == 'minutosDePreparacion' && $value > $minutosTiempoMasAlto)
                                {
                                    $minutosTiempoMasAlto = $value;
                                    $pedidoIdTiempoMasAlto = $obj->pedidoId;
                                }
                            }
                        }

                        $listaPedidos = Pedido::GetAll();
                        $pedido = Pedido::FindById($listaPedidos, $pedidoIdTiempoMasAlto);
                        if($pedido !== false && $pedido->horaInicio !== '')
                        {
                            $minutosDesdeElPedido = (strtotime(date('Y-m-d H:i:s')) - strtotime($pedido->horaInicio)) / 60;
                            $minutosRestantes = $minutosTiempoMasAlto - $minutosDesdeElPedido;
                            
                            $jSend->status = 'success';
                            if($minutosRestantes <= 0)
                                $jSend->data->mensajeExito = "Su pedido esta listo";
                            else
                                $jSend->data->mensajeExito = "Faltan $minutosRestantes minutos para su pedido";
                        }
                        else if($pedido !== false && $pedido->horaInicio === '')
                        {
                            $jSend->status = 'success';
                            $jSend->message = 'Su pedido esta en lista de espera';
                        }
                        else
                        {
                            $jSend->message = 'Error al verificar el pedido';
                        }
                    }
                    else
                    {
                        $jSend->message = 'No hay mesa con ese codigo';
                    }
                }
                else
                {
                    $jSend->message = 'Codigo de mesa valido requerido: debe contener 5 caracteres';
                }
            }
            else
            {
                $jSend->message = 'No hay comanda con ese codigo';
            }
        }
        else
        {
            $jSend->message = 'Codigo del comanda valido requerido: debe contener 5 caracteres';
        }
        return json_encode($jSend);
    }
}
?>