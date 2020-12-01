<?php
namespace App\Services;
use App\Models\Encuesta;
use App\Models\Usuario;
use App\Models\JSend;

class EncuestaService
{
    public static function GetOne($request, $response, array $args)
    {
        $jSend = new JSend('error');
        $encuestaId = $args['id'] ?? '';
        if($encuestaId !== '' && is_numeric($encuestaId))
        {
            $lista = Encuesta::GetAll();
            
            if(Encuesta::IsInList($lista, $encuestaId))
            {
                $encuesta = Encuesta::FindById($lista, $encuestaId);
                
                $jSend->status = 'success';
                $jSend->data->mensajeExito = $encuesta;
            }
            else
            {
                $jSend->message = 'No hay encuesta con ese id';
            }
        }
        else
        {
            $jSend->message = 'Id de encuesta valida requerida: solo numeros';
        }
        return json_encode($jSend);
    }

    public static function GetAll($request, $response, array $args)
    {
        $jSend = new JSend('success');
        $jSend->data->encuestas = Encuesta::GetAll();
        
        return json_encode($jSend);
    }

    public static function Insert($request, $response, $args)
    {
        $jSend = new JSend('error');
        $params = $request->getParsedBody();
        
        $puntajeMesa = $params['puntajeMesa'] ?? '';
        if($puntajeMesa !== '' && is_numeric($puntajeMesa) && $puntajeMesa >= 1 && $puntajeMesa <= 10)
        {
            $puntajeRestaurante = $params['puntajeRestaurante'] ?? '';
            if($puntajeRestaurante !== '' && is_numeric($puntajeRestaurante) && $puntajeRestaurante >= 1 && $puntajeRestaurante <= 10)
            {
                $puntajeMozo = $params['puntajeMozo'] ?? '';
                if($puntajeMozo !== '' && is_numeric($puntajeMozo) && $puntajeMozo >= 1 && $puntajeMozo <= 10)
                {
                    $puntajeCocinero = $params['puntajeCocinero'] ?? '';
                    if($puntajeCocinero !== '' && is_numeric($puntajeCocinero) && $puntajeCocinero >= 1 && $puntajeCocinero <= 10)
                    {
                        $descripcion = $params['descripcion'] ?? '';
                        if($descripcion !== '' && strlen($descripcion) <= 66)
                        {
                            $encuesta = Encuesta::Constructor('', $puntajeMesa, $puntajeRestaurante, $puntajeMozo, $puntajeCocinero, $descripcion);
                            
                            $encuesta->Insert();
                            
                            $jSend->status = 'success';
                            $jSend->data->mensajeExito = 'Guardado exitoso';
                        }
                        else
                        {
                            $jSend->message = 'Descripcion valida requerida: maximo 66 caracteres';
                        }
                    }
                    else
                    {
                        $jSend->message = 'Puntaje de cocinero valido requerido: solo numeros enteros del 1 al 10';
                    }
                }
                else
                {
                    $jSend->message = 'Puntaje de mozo valido requerido: solo numeros enteros del 1 al 10';
                }
            }
            else
            {
                $jSend->message = 'Puntaje de restaurante valido requerido: solo numeros enteros del 1 al 10';
            }
        }
        else
        {
            $jSend->message = 'Puntaje de mesa valido requerido: solo numeros enteros del 1 al 10';
        }
        return json_encode($jSend);
    }
    
    public static function Update($request, $response, array $args)
    {
        $jSend = new JSend('error');
        $params = $request->getParsedBody();
        $encuestaId = $args['id'] ?? '';
        if($encuestaId !== '' && is_numeric($encuestaId))
        {
            $puntajeMesa = $params['puntajeMesa'] ?? false;
            if($puntajeMesa === false || ($puntajeMesa !== '' && is_numeric($puntajeMesa) && $puntajeMesa >= 1 && $puntajeMesa <= 10))
            {
                $puntajeRestaurante = $params['puntajeRestaurante'] ?? false;
                if($puntajeRestaurante === false || ($puntajeRestaurante !== '' && is_numeric($puntajeRestaurante) && $puntajeRestaurante >= 1 && $puntajeRestaurante <= 10))
                {
                    $puntajeMozo = $params['puntajeMozo'] ?? false;
                    if($puntajeMozo === false || ($puntajeMozo !== '' && is_numeric($puntajeMozo) && $puntajeMozo >= 1 && $puntajeMozo <= 10))
                    {
                        $puntajeCocinero = $params['puntajeCocinero'] ?? false;
                        if($puntajeCocinero === false || ($puntajeCocinero !== '' && is_numeric($puntajeCocinero) && $puntajeCocinero >= 1 && $puntajeCocinero <= 10))
                        {
                            $descripcion = $params['descripcion'] ?? false;
                            if($descripcion === false || ($descripcion !== '' && strlen($descripcion) <= 66))
                            {
                                $lista = Encuesta::GetAll();
                                
                                if(Encuesta::IsInList($lista, $encuestaId))
                                {
                                    $oldEncuesta = Encuesta::FindById($lista, $encuestaId);

                                    $puntajeMesa = $puntajeMesa != false ? $puntajeMesa : $oldEncuesta->puntajeMesa;
                                    $puntajeRestaurante = $puntajeRestaurante != false ? $puntajeRestaurante : $oldEncuesta->puntajeRestaurante;
                                    $puntajeMozo = $puntajeMozo != false ? $puntajeMozo : $oldEncuesta->puntajeMozo;
                                    $puntajeCocinero = $puntajeCocinero != false ? $puntajeCocinero : $oldEncuesta->puntajeCocinero;
                                    $descripcion = $descripcion != false ? $descripcion : $oldEncuesta->descripcion;

                                    $encuesta = Encuesta::Constructor($encuestaId, $puntajeMesa, $puntajeRestaurante, $puntajeMozo, $puntajeCocinero, $descripcion);
                                    
                                    $encuesta->Update();

                                    $jSend->status = 'success';
                                    $jSend->data->mensajeExito = 'Modificacion exitosa';
                                }
                                else
                                {
                                    $jSend->message = 'No hay encuesta con ese id';
                                }
                            }
                            else
                            {
                                $jSend->message = 'Descripcion valida requerida: maximo 66 caracteres';
                            }
                        }
                        else
                        {
                            $jSend->message = 'Puntaje de cocinero valido requerido: solo numeros enteros del 1 al 10';
                        }
                    }
                    else
                    {
                        $jSend->message = 'Puntaje de mozo valido requerido: solo numeros enteros del 1 al 10';
                    }
                }
                else
                {
                    $jSend->message = 'Puntaje de restaurante valido requerido: solo numeros enteros del 1 al 10';
                }
            }
            else
            {
                $jSend->message = 'Puntaje de mesa valido requerido: solo numeros enteros del 1 al 10';
            }
        }
        else
        {
            $jSend->message = 'Id de encuesta valida requerida: solo numeros';
        }
        return json_encode($jSend);
    }
    
    public static function Delete($request, $response, array $args)
    {
        $jSend = new JSend('error');
        $encuestaId = $args['id'] ?? '';
        if($encuestaId !== '' && is_numeric($encuestaId))
        {
            $lista = Encuesta::GetAll();
            
            if(Encuesta::IsInList($lista, $encuestaId))
            {
                $encuesta = Encuesta::FindById($lista, $encuestaId);
                $encuesta->Delete();

                $jSend->status = 'success';
                $jSend->data->mensajeExito = 'Borrado exitoso';
            }
            else
            {
                $jSend->message = 'No hay encuesta con ese id';
            }
        }
        else
        {
            $jSend->message = 'Id de encuesta valida requerida: solo numeros';
        }
        return json_encode($jSend);
    }
    
    public static function GetMejoresComentarios($request, $response, array $args)
    {
        $jSend = new JSend('success');
        $jSend->data->comentario = Encuesta::GetMejoresComentarios();
        
        return json_encode($jSend);
    }
    
    public static function GetPeoresComentarios($request, $response, array $args)
    {
        $jSend = new JSend('success');
        $jSend->data->comentario = Encuesta::GetPeoresComentarios();
        
        return json_encode($jSend);
    }
}
?>