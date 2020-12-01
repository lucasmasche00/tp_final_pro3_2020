<?php
namespace App\Models;
use Config\AccesoDatos;
use PDO;
use App\Models\FechasTabla;

class Empleado extends FechasTabla
{
    public $legajo;
    public $email;
    public $nombre;
    public $apellido;
    public $ocupacion;
    public $estado;

    public static function Constructor($legajo, $email, $nombre, $apellido, $ocupacion, $estado)
    {
        $obj = new Empleado();
        $obj->legajo = $legajo;
        $obj->email = $email;
        $obj->nombre = $nombre;
        $obj->apellido = $apellido;
        $obj->ocupacion = $ocupacion;
        $obj->estado = $estado;
        return $obj;
    }

    //==================== CLASS ============================
    public static function GetInstance($obj)
    {
        return self::Constructor($obj->legajo, $obj->email, $obj->nombre, $obj->apellido, $obj->ocupacion, $obj->estado);
    }
    public static function ListStdToEmpleado($lista)
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
            if($value->legajo != null && $id != null && $value->legajo == $id)
                return $value;
        }
        return false;
    }

    public static function IsInList($lista, $id)
    {
        return (!is_null($lista) && count($lista) > 0) ? ((self::FindById($lista, $id) === false) ? false : true) : false;
    }

    public static function FindByEmail($lista, $email)
    {
        foreach ($lista as $value)
        {
            if($value->email != null && $email != null && $value->email == $email)
                return $value;
        }
        return false;
    }

    public static function IsInListByEmail($lista, $email)
    {
        return (!is_null($lista) && count($lista) > 0) ? ((self::FindByEmail($lista, $email) === false) ? false : true) : false;
    }

    public static function Add($lista, $obj)
    {
        if(!self::IsInList($lista, $obj->legajo))
        {
            array_push($lista, $obj);
            return $lista;
        }
        return false;
    }

    public static function Remove($lista, $obj)
    {
        $obj = self::GetInstance($obj);
        if(self::IsInList($lista, $obj->legajo))
        {
            foreach ($lista as $key => $value)
            {
                if($value->legajo != null && $obj->legajo != null && $value->legajo === $obj->legajo)
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
        $query = $objetoAccesoDato->RetornarConsulta('SELECT * FROM empleados');
        return $query->execute() ? $query->fetchAll(PDO::FETCH_CLASS, 'App\Models\Empleado') : false;
    }

    public function Insert()
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $query = $objetoAccesoDato->RetornarConsulta("INSERT INTO empleados (legajo, email, nombre, apellido, ocupacion, estado, createDttm, updateDttm) VALUES (:legajo,:email,:nombre,:apellido,:ocupacion,:estado,:createDttm,:updateDttm)");
        $query->bindValue(':legajo',$this->legajo, PDO::PARAM_INT);
        $query->bindValue(':email', $this->email, PDO::PARAM_STR);
        $query->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
        $query->bindValue(':apellido', $this->apellido, PDO::PARAM_STR);
        $query->bindValue(':ocupacion', $this->ocupacion, PDO::PARAM_STR);
        $query->bindValue(':estado', $this->estado, PDO::PARAM_STR);
        $query->bindValue(':createDttm', date('Y-m-d H:i:s'), PDO::PARAM_STR);
        $query->bindValue(':updateDttm', date('Y-m-d H:i:s'), PDO::PARAM_STR);
        $query->execute();
        return $objetoAccesoDato->RetornarUltimoIdInsertado();
    }

    public function Update()
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $query = $objetoAccesoDato->RetornarConsulta("UPDATE empleados SET email = :email, nombre = :nombre, apellido = :apellido, ocupacion = :ocupacion, estado = :estado, updateDttm = :updateDttm WHERE legajo = :legajo");
        $query->bindValue(':legajo',$this->legajo, PDO::PARAM_INT);
        $query->bindValue(':email', $this->email, PDO::PARAM_STR);
        $query->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
        $query->bindValue(':apellido', $this->apellido, PDO::PARAM_STR);
        $query->bindValue(':ocupacion', $this->ocupacion, PDO::PARAM_STR);
        $query->bindValue(':estado', $this->estado, PDO::PARAM_STR);
        $query->bindValue(':updateDttm', date('Y-m-d H:i:s'), PDO::PARAM_STR);
        $query->execute();
        return $objetoAccesoDato->RetornarUltimoIdInsertado();
    }

    public function Delete()
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $query = $objetoAccesoDato->RetornarConsulta("UPDATE empleados SET estado = 'borrado', updateDttm = :updateDttm WHERE legajo = :legajo");
        $query->bindValue(':legajo',$this->legajo, PDO::PARAM_INT);
        $query->bindValue(':updateDttm', date('Y-m-d H:i:s'), PDO::PARAM_STR);
        $query->execute();
        return $objetoAccesoDato->RetornarUltimoIdInsertado();
    }
    
    public static function GetAllOperacionesPorSectorOnlyCantidad()
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $query = $objetoAccesoDato->RetornarConsulta("SELECT COUNT(p.pedidoId) AS cantidad, e.ocupacion".
                                                    " FROM empleados AS e".
                                                    " JOIN pedidos AS p ON p.legajo = e.legajo".
                                                    " WHERE e.ocupacion != 'mozo' GROUP BY e.ocupacion");
        return $query->execute() ? $query->fetchAll(PDO::FETCH_CLASS, 'stdClass') : false;
    }

    public static function GetAllOperacionesMozo()
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $query = $objetoAccesoDato->RetornarConsulta('SELECT COUNT(*) AS cantidad FROM comandas WHERE legajo != 0');
        return $query->execute() ? $query->fetchAll(PDO::FETCH_CLASS, 'stdClass')[0] : false;
    }

    public static function GetAllOperacionesMozoByLegajo()
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $query = $objetoAccesoDato->RetornarConsulta('SELECT legajo, COUNT(*) AS cantidad FROM comandas WHERE legajo != 0 GROUP BY legajo');
        return $query->execute() ? $query->fetchAll(PDO::FETCH_CLASS, 'stdClass') : false;
    }

    public static function GetAllOperacionesPorSectorByLegajo()
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $query = $objetoAccesoDato->RetornarConsulta("SELECT e.ocupacion, e.legajo, COUNT(p.pedidoId) AS cantidad".
                                                    " FROM empleados AS e".
                                                    " JOIN pedidos AS p ON p.legajo = e.legajo".
                                                    " WHERE e.ocupacion != 'mozo' GROUP BY e.ocupacion, e.legajo");
        return $query->execute() ? $query->fetchAll(PDO::FETCH_CLASS, 'stdClass') : false;
    }
}
?>