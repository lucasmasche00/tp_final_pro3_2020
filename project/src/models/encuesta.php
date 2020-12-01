<?php
namespace App\Models;
use Config\AccesoDatos;
use PDO;
use App\Models\FechasTabla;

class Encuesta extends FechasTabla
{
    public $encuestaId;
    public $puntajeMesa;
    public $puntajeRestaurante;
    public $puntajeMozo;
    public $puntajeCocinero;
    public $descripcion;

    public static function Constructor($encuestaId, $puntajeMesa, $puntajeRestaurante, $puntajeMozo, $puntajeCocinero, $descripcion)
    {
        $obj = new Encuesta();
        $obj->encuestaId = $encuestaId;
        $obj->puntajeMesa = $puntajeMesa;
        $obj->puntajeRestaurante = $puntajeRestaurante;
        $obj->puntajeMozo = $puntajeMozo;
        $obj->puntajeCocinero = $puntajeCocinero;
        $obj->descripcion = $descripcion;
        return $obj;
    }

    //==================== CLASS ============================
    public static function GetInstance($obj)
    {
        return self::Constructor($obj->encuestaId, $obj->puntajeMesa, $obj->puntajeRestaurante, $obj->puntajeMozo, $obj->puntajeCocinero, $obj->descripcion);
    }
    public static function ListStdToEncuesta($lista)
    {
        $listaObj = array();
        foreach ($lista as $value)
        {
            array_push($listaObj, self::GetInstance($value));
        }
        return $listaObj;
    }
    
    public static function FindById($lista, $id)
    {
        foreach ($lista as $value)
        {
            if($value->encuestaId != null && $id != null && $value->encuestaId == $id)
                return $value;
        }
        return false;
    }

    public static function IsInList($lista, $id)
    {
        return (!is_null($lista) && count($lista) > 0) ? ((self::FindById($lista, $id) === false) ? false : true) : false;
    }

    public static function Add($lista, $obj)
    {
        if(!self::IsInList($lista, $obj->encuestaId))
        {
            array_push($lista, $obj);
            return $lista;
        }
        return false;
    }

    public static function Remove($lista, $obj)
    {
        $obj = self::GetInstance($obj);
        if(self::IsInList($lista, $obj->encuestaId))
        {
            foreach ($lista as $key => $value)
            {
                if($value->encuestaId != null && $obj->encuestaId != null && $value->encuestaId === $obj->encuestaId)
                {
                    unset($lista[$key]);
                    return $lista;
                }
            }
        }
        return false;
    }
    
    //==================== DAO ============================
    public static function GetAll()
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $query = $objetoAccesoDato->RetornarConsulta('SELECT * FROM encuestas');
        return $query->execute() ? $query->fetchAll(PDO::FETCH_CLASS, 'App\Models\Encuesta') : false;
    }

    public function Insert()
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $query = $objetoAccesoDato->RetornarConsulta("INSERT INTO encuestas (puntajeMesa, puntajeRestaurante, puntajeMozo, puntajeCocinero, descripcion, createDttm, updateDttm) VALUES (:puntajeMesa,:puntajeRestaurante,:puntajeMozo,:puntajeCocinero,:descripcion,:createDttm,:updateDttm)");
        $query->bindValue(':puntajeMesa', $this->puntajeMesa, PDO::PARAM_INT);
        $query->bindValue(':puntajeRestaurante', $this->puntajeRestaurante, PDO::PARAM_INT);
        $query->bindValue(':puntajeMozo', $this->puntajeMozo, PDO::PARAM_INT);
        $query->bindValue(':puntajeCocinero', $this->puntajeCocinero, PDO::PARAM_INT);
        $query->bindValue(':descripcion', $this->descripcion, PDO::PARAM_STR);
        $query->bindValue(':createDttm', date('Y-m-d H:i:s'), PDO::PARAM_STR);
        $query->bindValue(':updateDttm', date('Y-m-d H:i:s'), PDO::PARAM_STR);
        $query->execute();
        return $objetoAccesoDato->RetornarUltimoIdInsertado();
    }

    public function Update()
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $query = $objetoAccesoDato->RetornarConsulta("UPDATE encuestas SET puntajeMesa = :puntajeMesa, puntajeRestaurante = :puntajeRestaurante, puntajeMozo = :puntajeMozo, puntajeCocinero = :puntajeCocinero, descripcion = :descripcion, updateDttm = :updateDttm WHERE encuestaId = :encuestaId");
        $query->bindValue(':encuestaId',$this->encuestaId, PDO::PARAM_INT);
        $query->bindValue(':puntajeMesa', $this->puntajeMesa, PDO::PARAM_INT);
        $query->bindValue(':puntajeRestaurante', $this->puntajeRestaurante, PDO::PARAM_INT);
        $query->bindValue(':puntajeMozo', $this->puntajeMozo, PDO::PARAM_INT);
        $query->bindValue(':puntajeCocinero', $this->puntajeCocinero, PDO::PARAM_INT);
        $query->bindValue(':descripcion', $this->descripcion, PDO::PARAM_STR);
        $query->bindValue(':updateDttm', date('Y-m-d H:i:s'), PDO::PARAM_STR);
        $query->execute();
        return $objetoAccesoDato->RetornarUltimoIdInsertado();
    }

    public function Delete()
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $query = $objetoAccesoDato->RetornarConsulta("DELETE FROM encuestas WHERE encuestaId = :encuestaId");
        $query->bindValue(':encuestaId',$this->encuestaId, PDO::PARAM_INT);
        $query->execute();
        return $query->rowCount();
    }

    public static function GetMejoresComentarios()
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $query = $objetoAccesoDato->RetornarConsulta("SELECT ((puntajeMesa + puntajeRestaurante + puntajeMozo + puntajeCocinero) / 4) AS promedio, descripcion AS comentario".
                                                    " FROM encuestas".
                                                    " ORDER BY promedio DESC".
                                                    " LIMIT 1");
        return $query->execute() ? $query->fetchAll(PDO::FETCH_CLASS, 'stdClass') : false;
    }

    public static function GetPeoresComentarios()
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $query = $objetoAccesoDato->RetornarConsulta("SELECT ((puntajeMesa + puntajeRestaurante + puntajeMozo + puntajeCocinero) / 4) AS promedio, descripcion AS comentario".
                                                    " FROM encuestas".
                                                    " ORDER BY promedio ASC".
                                                    " LIMIT 1");
        return $query->execute() ? $query->fetchAll(PDO::FETCH_CLASS, 'stdClass') : false;
    }
}
?>