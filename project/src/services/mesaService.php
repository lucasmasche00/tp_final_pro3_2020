<?php
namespace App\Services;
use App\Models\Mesa;
use App\Models\Comanda;
use App\Models\Pedido;
use App\Models\JSend;
use App\Models\Archivo;

class MesaService
{
    public static function GetOne($request, $response, array $args)
    {
        $jSend = new JSend('error');
        $mesaCode = $args['id'] ?? '';
        if($mesaCode !== '' && strlen($mesaCode) === 5)
        {
            $lista = Mesa::GetAll();
            
            if(Mesa::IsInList($lista, $mesaCode))
            {
                $mesa = Mesa::FindById($lista, $mesaCode);
                
                $jSend->status = 'success';
                $jSend->data->mensajeExito = $mesa;
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
        return json_encode($jSend);
    }

    public static function GetAll($request, $response, array $args)
    {
        $jSend = new JSend('success');
        $jSend->data->mesas = Mesa::GetAll();
        
        return json_encode($jSend);
    }

    public static function Insert($request, $response, $args)
    {
        $jSend = new JSend('error');
        $params = $request->getParsedBody();
        
        $mesaCode = $params['mesaCode'] ?? '';
        if($mesaCode !== '' && strlen($mesaCode) === 5)
        {
            $lista = Mesa::GetAll();
    
            if(!Mesa::IsInList($lista, $mesaCode))
            {
                $mesa = Mesa::Constructor($mesaCode, 'cerrada', '');
                
                $mesa->Insert();
                
                $jSend->status = 'success';
                $jSend->data->mensajeExito = 'Guardado exitoso';
            }
            else
            {
                $jSend->message = 'Codigo de mesa repetido';
            }
        }
        else
        {
            $jSend->message = 'Codigo de mesa valido requerido: debe contener 5 caracteres';
        }
        return json_encode($jSend);
    }
    
    public static function Update($request, $response, array $args)
    {
        $jSend = new JSend('error');
        $params = $request->getParsedBody();
        $mesaCode = $args['id'] ?? '';
        if($mesaCode !== '' && strlen($mesaCode) === 5)
        {
            $estado = $params['estado'] ?? false;
            if($estado === false || $estado === 'con cliente esperando pedido' || $estado === 'con clientes comiendo' || $estado === 'con clientes pagando' || $estado === 'cerrada')
            {
                $lista = Mesa::GetAll();
                                
                if(Mesa::IsInList($lista, $mesaCode))
                {
                    $file = $_FILES['foto'] ?? false;
                    if($file === false || $file['size'] > 0)
                    {
                        $oldMesa = Mesa::FindById($lista, $mesaCode);

                        $foto = $file != false ? ($oldMesa->foto != '' ? Archivo::ModificarArchivo($file, $oldMesa->foto, $mesaCode) : Archivo::GuardarArchivo($file, $mesaCode)) : $oldMesa->foto;
                        
                        if($foto !== false)
                        {            
                            $estado = $estado != false ? $estado : $oldMesa->estado;
                            
                            if($estado === 'cerrada')
                            {
                                $listaComandas = Comanda::GetAll();
                                if(Comanda::IsInListByMesa($listaComandas, $mesaCode))
                                {
                                    if(PedidoService::CerrarPedidosPendientes($mesaCode))
                                    {
                                        $comandas = Comanda::FindComandasByMesa($listaComandas, $mesaCode);
                                        
                                        $comanda = new Comanda();
                                        if($comandas !== false)
                                        {
                                            foreach ($comandas as $comanda)
                                            {
                                                if($comanda->mesaCode === $mesaCode && $comanda->horaFin === '')
                                                {
                                                    $comanda->horaFin = date('Y-m-d H:i:s');
                                                    $comanda->Update();
                                                    break;
                                                }
                                            }
                                        }
                                        else
                                        {
                                            $jSend->message = 'Error al cerrar la mesa';
                                            return json_encode($jSend);
                                        }
                                    }
                                    else
                                    {
                                        $jSend->message = 'Error al cerrar la mesa';
                                        return json_encode($jSend);
                                    }
                                }
                                else
                                {
                                    $jSend->message = 'Error al cerrar la mesa';
                                    return json_encode($jSend);
                                }
                                
                                if($foto !== '')
                                {
                                    Archivo::BorrarArchivo($foto);
                                    $foto = '';
                                }
                            }

                            $mesa = Mesa::Constructor($mesaCode, $estado, $foto);
                            
                            $mesa->Update();
            
                            $jSend->status = 'success';
                            $jSend->data->mensajeExito = 'Modificacion exitosa';
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
                    $jSend->message = 'No hay mesa con ese codigo';
                }
            }
            else
            {
                $jSend->message = 'Estado valido requerido: con cliente esperando pedido, con clientes comiendo, con clientes pagando o cerrada';
            }
        }
        else
        {
            $jSend->message = 'Codigo de mesa valido requerido: debe contener 5 caracteres';
        }
        return json_encode($jSend);
    }
    
    public static function Delete($request, $response, array $args)
    {
        $jSend = new JSend('error');
        $mesaCode = $args['id'] ?? '';
        if($mesaCode !== '' && strlen($mesaCode) === 5)
        {
            $lista = Mesa::GetAll();
            
            if(Mesa::IsInList($lista, $mesaCode))
            {
                $mesa = Mesa::FindById($lista, $mesaCode);
                $mesa->Delete();

                $jSend->status = 'success';
                $jSend->data->mensajeExito = 'Borrado exitoso';
            }
            else
            {
                $jSend->message = 'Codigo de mesa no encontrado';
            }
        }
        else
        {
            $jSend->message = 'Codigo de mesa valido requerido: debe contener 5 caracteres';
        }
        return json_encode($jSend);
    }

    public static function CobrarMesa($request, $response, $args)
    {
        $jSend = new JSend('error');
        $params = $request->getParsedBody();
        
        $mesaCode = $params['codigoMesa'] ?? '';
        if($mesaCode !== '' && strlen($mesaCode) === 5)
        {
            $lista = Mesa::GetAll();
            $mesa = Mesa::FindById($lista, $mesaCode);

            if($mesa !== false)
            {
                if($mesa->estado !== 'cerrada')
                {
                    if($mesa->estado !== 'con clientes pagando')
                    {
                        $mesa->estado = 'con clientes pagando';
                        $mesa->Update();
                        
                        $jSend->status = 'success';
                        $jSend->data->mensajeExito = 'Modificacion exitosa: mesa cobrada';
                    }
                    else
                    {
                        $jSend->message = 'La mesa ya ha sido cobrada';
                    }
                }
                else
                {
                    $jSend->message = 'La mesa se encuentra cerrada';
                }
            }
            else
            {
                $jSend->message = 'Error al encontrar la mesa';
            }
        }
        else
        {
            $jSend->message = 'Codigo de mesa valido requerido: debe contener 5 caracteres';
        }
        return json_encode($jSend);
    }
    
    public static function GetMesaMasUsada($request, $response, array $args)
    {
        $jSend = new JSend('success');
        $jSend->data->mesa = Mesa::GetMesaMasUsada();
        
        return json_encode($jSend);
    }
    
    public static function GetMesaMenosUsada($request, $response, array $args)
    {
        $jSend = new JSend('success');
        $jSend->data->mesa = Mesa::GetMesaMenosUsada();
        
        return json_encode($jSend);
    }
    
    public static function GetMesaMasFacturo($request, $response, array $args)
    {
        $jSend = new JSend('success');
        $jSend->data->mesa = Mesa::GetMesaMasFacturo();
        
        return json_encode($jSend);
    }
    
    public static function GetMesaMenosFacturo($request, $response, array $args)
    {
        $jSend = new JSend('success');
        $jSend->data->mesa = Mesa::GetMesaMenosFacturo();
        
        return json_encode($jSend);
    }

    public static function GetMesaMayorFacturaIndividual($request, $response, array $args)
    {
        $jSend = new JSend('error');
        //considero que todos los valores facturados estan ordenados por la query
        $mesas = Mesa::GetMesaMayorFacturaIndividual();
        if(count($mesas) > 0)
        {
            $arrayMesas = array($mesas[0]);
            if(count($mesas) > 1)
            {
                for ($i=1; $i < count($mesas); $i++)
                {
                    if($mesas[$i]->facturado == $mesas[$i - 1]->facturado)
                        array_push($arrayMesas, $mesas[$i]);
                    else
                        break;
                }
            }
            $jSend->status = 'success';
            $jSend->data->mesas = $arrayMesas;
        }
        else
        {
            $jSend->data->mesas = 'No hay facturas';
        }
        
        return json_encode($jSend);
    }
    
    public static function GetMesaMenorFacturaIndividual($request, $response, array $args)
    {
        $jSend = new JSend('error');
        //considero que todos los valores facturados estan ordenados por la query
        $mesas = Mesa::GetMesaMenorFacturaIndividual();
        if(count($mesas) > 0)
        {
            $arrayMesas = array($mesas[0]);
            if(count($mesas) > 1)
            {
                for ($i=1; $i < count($mesas); $i++)
                {
                    if($mesas[$i]->facturado == $mesas[$i - 1]->facturado)
                        array_push($arrayMesas, $mesas[$i]);
                    else
                        break;
                }
            }
            $jSend->status = 'success';
            $jSend->data->mesas = $arrayMesas;
        }
        else
        {
            $jSend->data->mesas = 'No hay facturas';
        }
        
        return json_encode($jSend);
    }
    
    public static function GetMesaFacturoPorFechas($request, $response, array $args)
    {
        $jSend = new JSend('error');
        
        $mesaCode = $args['codigoMesa'] ?? '';
        if($mesaCode !== '' && strlen($mesaCode) === 5)
        {
            $lista = Mesa::GetAll();
            if(Mesa::IsInList($lista, $mesaCode))
            {
                $fechaDesde = $args['fechaDesde'] ?? '';
                if($fechaDesde !== '' && strlen($fechaDesde) === 10)
                {
                    $fechaHasta = $args['fechaHasta'] ?? '';
                    if($fechaHasta !== '' && strlen($fechaHasta) === 10)
                    {
                        $jSend->status = 'success';
                        $jSend->data->mesa = Mesa::GetMesaFacturoByDates($mesaCode, $fechaDesde, $fechaHasta);
                    }
                    else
                    {
                        $jSend->message = 'Fecha valida requerida: formato aaaa-mm-dd';
                    }
                }
                else
                {
                    $jSend->message = 'Fecha valida requerida: formato aaaa-mm-dd';
                }
            }
            else
            {
                $jSend->message = 'Error al encontrar la mesa';
            }
        }
        else
        {
            $jSend->message = 'Codigo de mesa valido requerido: debe contener 5 caracteres';
        }
        return json_encode($jSend);
    }
}
?>