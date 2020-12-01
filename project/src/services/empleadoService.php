<?php
namespace App\Services;
use App\Models\Empleado;
use App\Models\Usuario;
use App\Models\JSend;
use stdClass;

class EmpleadoService
{
    public static function GetOne($request, $response, array $args)
    {
        $jSend = new JSend('error');
        $legajo = $args['id'] ?? '';
        if($legajo !== '' && is_numeric($legajo))
        {
            $lista = Empleado::GetAll();
            
            if(Empleado::IsInList($lista, $legajo))
            {
                $empleado = Empleado::FindById($lista, $legajo);
                
                $jSend->status = 'success';
                $jSend->data->mensajeExito = $empleado;
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
        return json_encode($jSend);
    }

    public static function GetAll($request, $response, array $args)
    {
        $jSend = new JSend('success');
        $jSend->data->empleados = Empleado::GetAll();
        
        return json_encode($jSend);
    }

    public static function Insert($request, $response, $args)
    {
        $jSend = new JSend('error');
        $params = $request->getParsedBody();
        
        $legajo = $params['legajo'] ?? '';
        if($legajo !== '' && is_numeric($legajo))
        {
            $email = $params['email'] ?? '';
            if($email !== '')
            {
                $listaUsuario = Usuario::GetAllWithoutPassword();
            
                if(Usuario::IsInList($listaUsuario, $email))
                {
                    $user = Usuario::FindById($listaUsuario, $email);
                    if($user !== false && $user->tipo === 'empleado')
                    {
                        $lista = Empleado::GetAll();
    
                        if(!Empleado::IsInListByEmail($lista, $email))
                        {
                            $nombre = $params['nombre'] ?? '';
                            if($nombre !== '')
                            {
                                $apellido = $params['apellido'] ?? '';
                                if($apellido !== '')
                                {
                                    $ocupacion = $params['ocupacion'] ?? '';
                                    if($ocupacion === 'bartender' || $ocupacion === 'cervecero' || $ocupacion === 'cocinero' || $ocupacion === 'mozo')
                                    {
                                        $estado = $params['estado'] ?? '';
                                        if($estado === 'suspendido' || $estado === 'activo')
                                        {
                                            if(!Empleado::IsInList($lista, $legajo))
                                            {
                                                $user = Empleado::Constructor($legajo, $email, $nombre, $apellido, $ocupacion, $estado);
                                                
                                                $user->Insert();
                                                
                                                $jSend->status = 'success';
                                                $jSend->data->mensajeExito = 'Guardado exitoso';
                                            }
                                            else
                                            {
                                                $jSend->message = 'Legajo repetido';
                                            }
                                        }
                                        else
                                        {
                                            $jSend->message = 'Estado valido requerido: suspendido o activo';
                                        }
                                    }
                                    else
                                    {
                                        $jSend->message = 'Ocupacion valida requerida: bartender, cervecero, cocinero o mozo';
                                    }
                                }
                                else
                                {
                                    $jSend->message = 'Apellido valido requerido';
                                }
                            }
                            else
                            {
                                $jSend->message = 'Nombre valido requerido';
                            }
                        }
                        else
                        {
                            $jSend->message = 'Email repetido';
                        }
                    }
                    else
                    {
                        $jSend->message = 'El email debe ser de tipo empleado';
                    }
                }
                else
                {
                    $jSend->message = 'No hay empleado con ese email';
                }
            }
            else
            {
                $jSend->message = 'Email valido requerido';
            }
        }
        else
        {
            $jSend->message = 'Legajo valido requerido: solo numeros';
        }
        return json_encode($jSend);
    }
    
    public static function Update($request, $response, array $args)
    {
        $jSend = new JSend('error');
        $params = $request->getParsedBody();
        $legajo = $args['id'] ?? '';
        if($legajo !== '' && is_numeric($legajo))
        {
            $email = $params['email'] ?? false;
            if($email !== '' || $email === false)
            {
                $nombre = $params['nombre'] ?? false;
                if($nombre !== '' || $nombre === false)
                {
                    $apellido = $params['apellido'] ?? false;
                    if($apellido !== '' || $apellido === false)
                    {
                        $ocupacion = $params['ocupacion'] ?? false;
                        if($ocupacion === false || $ocupacion === 'bartender' || $ocupacion === 'cervecero' || $ocupacion === 'cocinero' || $ocupacion === 'mozo')
                        {
                            $estado = $params['estado'] ?? false;
                            if($estado === false || $estado === 'suspendido' || $estado === 'activo')
                            {
                                $lista = Empleado::GetAll();
                                
                                if(Empleado::IsInList($lista, $legajo))
                                {
                                    if(!Empleado::IsInListByEmail($lista, $email))
                                    {
                                        $oldEmpleado = Empleado::FindById($lista, $legajo);

                                        $email = $email != false ? $email : $oldEmpleado->email;
                                        $nombre = $nombre != false ? $nombre : $oldEmpleado->nombre;
                                        $apellido = $apellido != false ? $apellido : $oldEmpleado->apellido;
                                        $ocupacion = $ocupacion != false ? $ocupacion : $oldEmpleado->ocupacion;
                                        $estado = $estado != false ? $estado : $oldEmpleado->estado;

                                        $listaUsuarios = Usuario::GetAll();
                                        if(Usuario::IsInList($listaUsuarios, $email))
                                        {
                                            $user = Usuario::FindById($listaUsuarios, $email);
                                            if($user->estado != 'borrado')
                                            {
                                                if($user->tipo == 'empleado')
                                                {
                                                    $empleado = Empleado::Constructor($legajo, $email, $nombre, $apellido, $ocupacion, $estado);
                                                    
                                                    $empleado->Update();
            
                                                    $jSend->status = 'success';
                                                    $jSend->data->mensajeExito = 'Modificacion exitosa';
                                                }
                                                else
                                                {
                                                    $jSend->message = 'Email no pertenece a un usuario de tipo empleado';
                                                }
                                            }
                                            else
                                            {
                                                $jSend->message = 'Email no pertenece a un usuario existente';
                                            }
                                        }
                                        else
                                        {
                                            $jSend->message = 'Email no pertenece a un usuario';
                                        }
                                    }
                                    else
                                    {
                                        $jSend->message = 'Email repetido';
                                    }
                                }
                                else
                                {
                                    $jSend->message = 'No hay empleado con ese legajo';
                                }
                            }
                            else
                            {
                                $jSend->message = 'Estado valido requerido: suspendido o activo';
                            }
                        }
                        else
                        {
                            $jSend->message = 'Ocupacion valida requerida: bartender, cervecero, cocinero o mozo';
                        }
                    }
                    else
                    {
                        $jSend->message = 'Apellido valido requerido';
                    }
                }
                else
                {
                    $jSend->message = 'Nombre valido requerido';
                }
            }
            else
            {
                $jSend->message = 'Email valido requerido';
            }
        }
        else
        {
            $jSend->message = 'Legajo valido requerido: solo numeros';
        }
        return json_encode($jSend);
    }
    
    public static function Delete($request, $response, array $args)
    {
        $jSend = new JSend('error');
        $legajo = $args['id'] ?? '';
        if($legajo !== '')
        {
            $lista = Empleado::GetAll();
            
            if(Empleado::IsInList($lista, $legajo))
            {
                $empleado = Empleado::FindById($lista, $legajo);
                $empleado->Delete();

                $jSend->status = 'success';
                $jSend->data->mensajeExito = 'Borrado exitoso';
            }
            else
            {
                $jSend->message = 'Legajo no encontrado';
            }
        }
        else
        {
            $jSend->message = 'Legajo valido requerido';
        }
        return json_encode($jSend);
    }
    
    public static function GetCantidadOperacionesPorSector($request, $response, array $args)
    {
        $jSend = new JSend('success');
        $operacionesMozo = Empleado::GetAllOperacionesMozo();
        $obj = new stdClass();
        $obj->mozo = $operacionesMozo->cantidad;
        $operacionesPreparadores = Empleado::GetAllOperacionesPorSectorOnlyCantidad();
        $obj->bartender = 0;
        $obj->cervecero = 0;
        $obj->cocinero = 0;
        foreach ($operacionesPreparadores as $operacion)
        {
            switch ($operacion->ocupacion)
            {
                case 'bartender':
                    $obj->bartender = $operacion->cantidad;
                    break;
                case 'cervecero':
                    $obj->cervecero = $operacion->cantidad;
                    break;
                case 'cocinero':
                    $obj->cocinero = $operacion->cantidad;
                    break;
                default:
                    break;
            }
        }
        $jSend->data->operaciones = $obj;
        
        return json_encode($jSend);
    }

    public static function GetCantidadOperacionesPorSectorYEmpleado($request, $response, array $args)
    {
        $jSend = new JSend('success');
        $arrayOcupaciones = array('mozos' => array(), 'bartenders' => array(), 'cerveceros' => array(), 'cocineros' => array());
        $operacionesMozo = Empleado::GetAllOperacionesMozoByLegajo();
        if(count($operacionesMozo) > 0)
        {
            foreach ($operacionesMozo as $op)
            {
                $obj = new stdClass();
                $obj->legajo = $op->legajo;
                $obj->cantidad = $op->cantidad;
                array_push($arrayOcupaciones['mozos'], $obj);
            }
        }
        else
        {
            $obj = new stdClass();
            $obj->cantidad = 'sin operaciones';
            array_push($arrayOcupaciones['mozos'], $obj);
        }

        $operacionesPreparadores = Empleado::GetAllOperacionesPorSectorByLegajo();
        if(count($operacionesPreparadores) > 0)
        {
            foreach ($operacionesPreparadores as $op)
            {
                $obj = new stdClass();
                $obj->legajo = $op->legajo;
                $obj->cantidad = $op->cantidad;

                switch ($op->ocupacion)
                {
                    case 'bartender':
                        array_push($arrayOcupaciones['bartenders'], $obj);
                        break;
                    case 'cervecero':
                        array_push($arrayOcupaciones['cerveceros'], $obj);
                        break;
                    case 'cocinero':
                        array_push($arrayOcupaciones['cocineros'], $obj);
                        break;
                    default:
                        break;
                }
            }
        }
        else
        {
            $obj = new stdClass();
            $obj->cantidad = 'sin operaciones';
            array_push($arrayOcupaciones['bartenders'], $obj);
            array_push($arrayOcupaciones['cerveceros'], $obj);
            array_push($arrayOcupaciones['cocineros'], $obj);
        }
        $jSend->data->operaciones = $arrayOcupaciones;
        
        return json_encode($jSend);
    }

    public static function GetCantidadOperacionesPorEmpleado($request, $response, array $args)
    {
        $jSend = new JSend('success');
        $arrayEmpleados = array();
        $operacionesMozo = Empleado::GetAllOperacionesMozoByLegajo();
        if(count($operacionesMozo) > 0)
        {
            foreach ($operacionesMozo as $op)
            {
                $obj = new stdClass();
                $obj->ocupacion = 'mozo';
                $obj->legajo = $op->legajo;
                $obj->cantidad = $op->cantidad;
                array_push($arrayEmpleados, $obj);
            }
        }
        $operacionesPreparadores = Empleado::GetAllOperacionesPorSectorByLegajo();
        if(count($operacionesPreparadores) > 0)
        {
            foreach ($operacionesPreparadores as $op)
            {
                $obj = new stdClass();
                $obj->ocupacion = $op->ocupacion;
                $obj->legajo = $op->legajo;
                $obj->cantidad = $op->cantidad;
                array_push($arrayEmpleados, $obj);
            }
        }
        $jSend->data->operaciones = $arrayEmpleados;
        
        return json_encode($jSend);
    }
}
?>