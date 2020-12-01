<?php
namespace App\Models;
use Config\AccesoDatos;
use PDO;
use App\Models\FechasTabla;

class Alimento extends FechasTabla
{
    public $alimentoId;
    public $categoria;
    public $descripcion;
    public $precio;
    public $minutosDePreparacion;

    public static function Constructor($alimentoId, $categoria, $descripcion, $precio, $minutosDePreparacion)
    {
        $obj = new Alimento();
        $obj->alimentoId = $alimentoId;
        $obj->categoria = $categoria;
        $obj->descripcion = $descripcion;
        $obj->precio = $precio;
        $obj->minutosDePreparacion = $minutosDePreparacion;
        return $obj;
    }

    //==================== CLASS ============================
    public static function GetInstance($obj)
    {
        return self::Constructor($obj->alimentoId, $obj->categoria, $obj->descripcion, $obj->precio, $obj->minutosDePreparacion);
    }
    public static function ListStdToAlimento($lista)
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
            if($value->alimentoId != null && $id != null && $value->alimentoId == $id)
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
        if(!self::IsInList($lista, $obj->alimentoId))
        {
            array_push($lista, $obj);
            return $lista;
        }
        return false;
    }

    public static function Remove($lista, $obj)
    {
        $obj = self::GetInstance($obj);
        if(self::IsInList($lista, $obj->alimentoId))
        {
            foreach ($lista as $key => $value)
            {
                if($value->alimentoId != null && $obj->alimentoId != null && $value->alimentoId === $obj->alimentoId)
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
        $query = $objetoAccesoDato->RetornarConsulta('SELECT * FROM alimentos');
        return $query->execute() ? $query->fetchAll(PDO::FETCH_CLASS, 'App\Models\Alimento') : false;
    }

    public function Insert()
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $query = $objetoAccesoDato->RetornarConsulta("INSERT INTO alimentos (categoria, descripcion, precio, minutosDePreparacion, createDttm, updateDttm) VALUES (:categoria,:descripcion,:precio,:minutosDePreparacion,:createDttm,:updateDttm)");
        $query->bindValue(':categoria', $this->categoria, PDO::PARAM_STR);
        $query->bindValue(':descripcion', $this->descripcion, PDO::PARAM_STR);
        $query->bindValue(':precio', $this->precio, PDO::PARAM_STR);
        $query->bindValue(':minutosDePreparacion', $this->minutosDePreparacion, PDO::PARAM_INT);
        $query->bindValue(':createDttm', date('Y-m-d H:i:s'), PDO::PARAM_STR);
        $query->bindValue(':updateDttm', date('Y-m-d H:i:s'), PDO::PARAM_STR);
        $query->execute();
        return $objetoAccesoDato->RetornarUltimoIdInsertado();
    }

    public function Update()
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $query = $objetoAccesoDato->RetornarConsulta("UPDATE alimentos SET categoria = :categoria, descripcion = :descripcion, precio = :precio, minutosDePreparacion = :minutosDePreparacion, updateDttm = :updateDttm WHERE alimentoId = :alimentoId");
        $query->bindValue(':alimentoId',$this->alimentoId, PDO::PARAM_INT);
        $query->bindValue(':categoria', $this->categoria, PDO::PARAM_STR);
        $query->bindValue(':descripcion', $this->descripcion, PDO::PARAM_STR);
        $query->bindValue(':precio', $this->precio, PDO::PARAM_STR);
        $query->bindValue(':minutosDePreparacion', $this->minutosDePreparacion, PDO::PARAM_INT);
        $query->bindValue(':updateDttm', date('Y-m-d H:i:s'), PDO::PARAM_STR);
        $query->execute();
        return $objetoAccesoDato->RetornarUltimoIdInsertado();
    }

    public function Delete()
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $query = $objetoAccesoDato->RetornarConsulta("DELETE FROM alimentos WHERE alimentoId = :alimentoId");
        $query->bindValue(':alimentoId',$this->alimentoId, PDO::PARAM_INT);
        $query->execute();
        return $query->rowCount();
    }
}
?>