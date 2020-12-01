<?php
namespace App\Services;
use App\Models\Usuario;
use App\Models\Empleado;
use App\Models\JSend;
use App\Models\Token;

class UsuarioService
{
    public static function GenerarToken($request, $response, array $args)
    {
        $jSend = new JSend('error');
        $params = $request->getParsedBody();
        $email = $params['email'] ?? '';
        $clave = $params['password'] ?? '';
        $lista = Usuario::GetAll();
        foreach ($lista as $value)
        {
            if($value->email === $email && $value->clave === sha1($clave) && $value->estado !== 'borrado')
            {
                if($value->estado === 'suspendido')
                {
                    $jSend->message = 'La cuenta esta suspendida';
                    return json_encode($jSend);
                }
                if($value->tipo === 'empleado')
                {
                    $listaEmpleados = Empleado::GetAll();
                    $empleado = Empleado::FindByEmail($listaEmpleados, $email);
                    if($empleado !== false)
                        $jwt = Token::CrearToken($value->email, $value->tipo, $empleado->ocupacion);
                    else
                        $jwt = Token::CrearToken($value->email, $value->tipo, 'sin ocupacion');
                }
                else
                    $jwt = Token::CrearToken($value->email, $value->tipo, 'sin ocupacion');
                $value->UpdateLoginDttm();
                $jSend->status = 'success';
                $jSend->data->token = $jwt;
                return json_encode($jSend);
            }
        }
        $jSend->message = 'Email y/o clave incorrecto/s';
        return json_encode($jSend);
    }

    public static function RegistroInsert($request, $response, $args)
    {
        $jSend = new JSend('error');
        $params = $request->getParsedBody();
        $email = $params['email'] ?? '';
        if($email !== '')
        {
            $clave = $params['clave'] ?? '';
            if($clave !== '')
            {
                $lista = Usuario::GetAll();
                
                if(!Usuario::IsInList($lista, $email))
                {
                    $user = Usuario::Constructor($email, $clave, 'cliente', 'activo');
                    
                    $user->Insert();
                    
                    $jSend->status = 'success';
                    $jSend->data->mensajeExito = 'Guardado exitoso';
                }
                else
                {
                    $jSend->message = 'Email repetido';
                }
            }
            else
            {
                $jSend->message = 'Clave valida requerida';
            }
        }
        else
        {
            $jSend->message = 'Email valido requerido';
        }
        return json_encode($jSend);
    }

    public static function GetOne($request, $response, array $args)
    {
        $jSend = new JSend('error');
        $email = $args['id'] ?? '';
        if($email !== '')
        {
            $lista = Usuario::GetAllWithoutPassword();
            
            if(Usuario::IsInList($lista, $email))
            {
                $user = Usuario::FindById($lista, $email);
                
                $jSend->status = 'success';
                $jSend->data->mensajeExito = $user;
            }
            else
            {
                $jSend->message = 'No hay usuario con ese email';
            }
        }
        else
        {
            $jSend->message = 'Email valido requerido';
        }
        return json_encode($jSend);
    }

    public static function GetAll($request, $response, array $args)
    {
        $jSend = new JSend('success');
        $jSend->data->usuarios = Usuario::GetAllWithoutPassword();
        
        return json_encode($jSend);
    }

    public static function Insert($request, $response, $args)
    {
        $jSend = new JSend('error');
        $params = $request->getParsedBody();
        $email = $params['email'] ?? '';
        if($email !== '')
        {
            $clave = $params['clave'] ?? '';
            if($clave !== '')
            {
                $tipo = $params['tipo'] ?? '';
                if($tipo === 'socio' || $tipo === 'empleado' || $tipo === 'cliente')
                {
                    $estado = $params['estado'] ?? '';
                    if($estado === 'suspendido' || $estado === 'activo')
                    {
                        $lista = Usuario::GetAll();
                        
                        if(!Usuario::IsInList($lista, $email))
                        {
                            $user = Usuario::Constructor($email, $clave, $tipo, $estado);
                            
                            $user->Insert();
                            
                            $jSend->status = 'success';
                            $jSend->data->mensajeExito = 'Guardado exitoso';
                        }
                        else
                        {
                            $jSend->message = 'Email repetido';
                        }
                    }
                    else
                    {
                        $jSend->message = 'Estado valido requerido: suspendido o activo';
                    }
                }
                else
                {
                    $jSend->message = 'Tipo valido requerido: socio, empleado o cliente';
                }
            }
            else
            {
                $jSend->message = 'Clave valida requerida';
            }
        }
        else
        {
            $jSend->message = 'Email valido requerido';
        }
        return json_encode($jSend);
    }
    
    public static function Update($request, $response, array $args)
    {
        $token = $_SERVER['HTTP_TOKEN'] ?? '';
        $usuarioLogeado = Token::DecodificarToken($token);

        $jSend = new JSend('error');
        $params = $request->getParsedBody();
        $email = $args['id'] ?? '';
        if($email !== '')
        {
            $lista = Usuario::GetAll();
            
            if(Usuario::IsInList($lista, $email))
            {
                $oldUser = Usuario::FindById($lista, $email);

                $clave = $params['clave'] ?? false;
                if($usuarioLogeado->email !== $email && $clave !== false)
                {
                    $jSend->message = 'No puedes cambiar la clave de otro usuario';
                    return json_encode($jSend);
                }

                if($clave !== '')
                {
                    $tipo = $params['tipo'] ?? false;
                    if($tipo === false || $tipo === 'socio' || $tipo === 'empleado' || $tipo === 'cliente')
                    {
                        $estado = $params['estado'] ?? false;
                        if($estado === false || $estado === 'suspendido' || $estado === 'activo')
                        {
                            $clave = $clave != false ? $clave : '';
                            $tipo = $tipo != false ? $tipo : $oldUser->tipo;
                            $estado = $estado != false ? $estado : $oldUser->estado;

                            $user = Usuario::Constructor($email, $clave, $tipo, $estado);
                            
                            if($clave === '')
                                $user->clave = $oldUser->clave;

                            $user->Update();

                            $jSend->status = 'success';
                            $jSend->data->mensajeExito = 'Modificacion exitosa';
                        }
                        else
                        {
                            $jSend->message = 'Estado valido requerido: suspendido o activo';
                        }
                    }
                    else
                    {
                        $jSend->message = 'Tipo valido requerido: socio, empleado o cliente';
                    }
                }
                else
                {
                    $jSend->message = 'Clave valida requerida';
                }
            }
            else
            {
                $jSend->message = 'Email no encontrado';
            }
        }
        else
        {
            $jSend->message = 'Email valido requerido';
        }
        return json_encode($jSend);
    }
    
    public static function Delete($request, $response, array $args)
    {
        $jSend = new JSend('error');
        $email = $args['id'] ?? '';
        if($email !== '')
        {
            $lista = Usuario::GetAll();
            
            if(Usuario::IsInList($lista, $email))
            {
                $user = Usuario::FindById($lista, $email);
                $user->Delete();

                $jSend->status = 'success';
                $jSend->data->mensajeExito = 'Borrado exitoso';
            }
            else
            {
                $jSend->message = 'Email no encontrado';
            }
        }
        else
        {
            $jSend->message = 'Email valido requerido';
        }
        return json_encode($jSend);
    }

    public static function GetIngresosAlSistema($request, $response, array $args)
    {
        $jSend = new JSend('success');
        $jSend->data->usuarios = Usuario::GetAllEmployeesLoginInfo();
        
        return json_encode($jSend);
    }
}
?>