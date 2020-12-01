<?php
namespace App\Services;
use App\Models\Alimento;
use App\Models\Usuario;
use App\Models\JSend;

class AlimentoService
{
    public static function GetOne($request, $response, array $args)
    {
        $jSend = new JSend('error');
        $alimentoId = $args['id'] ?? '';
        if($alimentoId !== '' && is_numeric($alimentoId))
        {
            $lista = Alimento::GetAll();
            
            if(Alimento::IsInList($lista, $alimentoId))
            {
                $alimento = Alimento::FindById($lista, $alimentoId);
                
                $jSend->status = 'success';
                $jSend->data->mensajeExito = $alimento;
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
        return json_encode($jSend);
    }

    public static function GetAll($request, $response, array $args)
    {
        $jSend = new JSend('success');
        $jSend->data->alimentos = Alimento::GetAll();
        
        return json_encode($jSend);
    }

    public static function Insert($request, $response, $args)
    {
        $jSend = new JSend('error');
        $params = $request->getParsedBody();
        
        $categoria = $params['categoria'] ?? '';
        if($categoria === 'trago' || $categoria === 'vino' || $categoria === 'cerveza' || $categoria === 'comida' || $categoria === 'postre')
        {
            $descripcion = $params['descripcion'] ?? '';
            if($descripcion !== '')
            {
                $precio = $params['precio'] ?? '';
                if($precio !== '' && is_numeric($precio) && $precio >= 0)
                {
                    $minutosDePreparacion = $params['minutosDePreparacion'] ?? '';
                    if($minutosDePreparacion !== '' && is_numeric($minutosDePreparacion) && $minutosDePreparacion >= 0)
                    {
                        $alimento = Alimento::Constructor('', $categoria, $descripcion, $precio, $minutosDePreparacion);
                        
                        $alimento->Insert();
                        
                        $jSend->status = 'success';
                        $jSend->data->mensajeExito = 'Guardado exitoso';
                    }
                    else
                    {
                        $jSend->message = 'Minutos de preparacion validos requeridos: debe ser numerico y no puede ser negativo';
                    }
                }
                else
                {
                    $jSend->message = 'Precio valido requerido: debe ser numerico y no puede ser negativo';
                }
            }
            else
            {
                $jSend->message = 'Descripcion valida requerida';
            }
        }
        else
        {
            $jSend->message = 'Categoria valida requerida: trago, vino, cerveza, comida o postre';
        }
        return json_encode($jSend);
    }
    
    public static function Update($request, $response, array $args)
    {
        $jSend = new JSend('error');
        $params = $request->getParsedBody();
        $alimentoId = $args['id'] ?? '';
        if($alimentoId !== '' && is_numeric($alimentoId))
        {
            $categoria = $params['categoria'] ?? false;
            if($categoria === false || $categoria === 'trago' || $categoria === 'vino' || $categoria === 'cerveza' || $categoria === 'comida' || $categoria === 'postre')
            {
                $descripcion = $params['descripcion'] ?? false;
                if($descripcion === false || $descripcion !== '')
                {
                    $precio = $params['precio'] ?? false;
                    if($precio === false || ($precio !== '' && is_numeric($precio) && $precio >= 0))
                    {
                        $minutosDePreparacion = $params['minutosDePreparacion'] ?? false;
                        if($minutosDePreparacion === false || ($minutosDePreparacion !== '' && is_numeric($minutosDePreparacion) && $minutosDePreparacion >= 0))
                        {
                            $lista = Alimento::GetAll();
                            
                            if(Alimento::IsInList($lista, $alimentoId))
                            {
                                $oldAlimento = Alimento::FindById($lista, $alimentoId);

                                $categoria = $categoria != false ? $categoria : $oldAlimento->categoria;
                                $descripcion = $descripcion != false ? $descripcion : $oldAlimento->descripcion;
                                $precio = $precio != false ? $precio : $oldAlimento->precio;
                                $minutosDePreparacion = $minutosDePreparacion != false ? $minutosDePreparacion : $oldAlimento->minutosDePreparacion;

                                $alimento = Alimento::Constructor($alimentoId, $categoria, $descripcion, $precio, $minutosDePreparacion);
                                
                                $alimento->Update();

                                $jSend->status = 'success';
                                $jSend->data->mensajeExito = 'Modificacion exitosa';
                            }
                            else
                            {
                                $jSend->message = 'No hay alimento con ese id';
                            }
                        }
                        else
                        {
                            $jSend->message = 'Minutos de preparacion validos requeridos: debe ser numerico y no puede ser negativo';
                        }
                    }
                    else
                    {
                        $jSend->message = 'Precio valido requerido: debe ser numerico y no puede ser negativo';
                    }
                }
                else
                {
                    $jSend->message = 'Descripcion valida requerida';
                }
            }
            else
            {
                $jSend->message = 'Categoria valida requerida: trago, vino, cerveza, comida o postre';
            }
        }
        else
        {
            $jSend->message = 'Id de alimento valido requerido: solo numeros';
        }
        return json_encode($jSend);
    }
    
    public static function Delete($request, $response, array $args)
    {
        $jSend = new JSend('error');
        $alimentoId = $args['id'] ?? '';
        if($alimentoId !== '' && is_numeric($alimentoId))
        {
            $lista = Alimento::GetAll();
            
            if(Alimento::IsInList($lista, $alimentoId))
            {
                $alimento = Alimento::FindById($lista, $alimentoId);
                $alimento->Delete();

                $jSend->status = 'success';
                $jSend->data->mensajeExito = 'Borrado exitoso';
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
        return json_encode($jSend);
    }
}
?>