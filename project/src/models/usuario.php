<?php
namespace App\Models;
use Config\AccesoDatos;
use PDO;
use App\Models\FechasTabla;

class Usuario extends FechasTabla
{
    public $email;
    public $clave;
    public $tipo;
    public $estado;
    public $loginDttm;

    public static function Constructor($email, $clave, $tipo, $estado)
    {
        $obj = new Usuario();
        $obj->email = $email;
        $obj->clave = sha1($clave);
        $obj->tipo = $tipo;
        $obj->estado = $estado;
        return $obj;
    }

    //==================== CLASS ============================
    public static function GetInstance($obj)
    {
        return self::Constructor($obj->email, $obj->clave, $obj->tipo, $obj->estado);
    }
    public static function ListStdToUsuario($lista)
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
            if($value->email != null && $id != null && $value->email == $id)
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
        if(!self::IsInList($lista, $obj->email))
        {
            array_push($lista, $obj);
            return $lista;
        }
        return false;
    }

    public static function Remove($lista, $obj)
    {
        $obj = self::GetInstance($obj);
        if(self::IsInList($lista, $obj->email))
        {
            foreach ($lista as $key => $value)
            {
                if($value->email != null && $obj->email != null && $value->email === $obj->email)
                {
                    unset($lista[$key]);
                    return $lista;
                }
            }
        }
        return false;
    }
    
    //==================== DAO ============================
    public static function GetAllWithoutPassword()
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $query = $objetoAccesoDato->RetornarConsulta('SELECT email,tipo,estado,loginDttm,createDttm,updateDttm FROM usuarios');
        return $query->execute() ? $query->fetchAll(PDO::FETCH_CLASS, 'App\Models\Usuario') : false;
    }

    public static function GetAll()
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $query = $objetoAccesoDato->RetornarConsulta('SELECT * FROM usuarios');
        return $query->execute() ? $query->fetchAll(PDO::FETCH_CLASS, 'App\Models\Usuario') : false;
    }

    public function Insert()
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $query = $objetoAccesoDato->RetornarConsulta("INSERT INTO usuarios (email, clave, tipo, estado, createDttm, updateDttm) VALUES (:email,:clave,:tipo,:estado,:createDttm,:updateDttm)");
        $query->bindValue(':email',$this->email, PDO::PARAM_STR);
        $query->bindValue(':clave', $this->clave, PDO::PARAM_STR);
        $query->bindValue(':tipo', $this->tipo, PDO::PARAM_STR);
        $query->bindValue(':estado', $this->estado, PDO::PARAM_STR);
        $query->bindValue(':createDttm', date('Y-m-d H:i:s'), PDO::PARAM_STR);
        $query->bindValue(':updateDttm', date('Y-m-d H:i:s'), PDO::PARAM_STR);
        $query->execute();
        return $objetoAccesoDato->RetornarUltimoIdInsertado();
    }

    public function Update()
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $query = $objetoAccesoDato->RetornarConsulta("UPDATE usuarios SET clave = :clave, tipo = :tipo, estado = :estado, updateDttm = :updateDttm WHERE email = :email");
        $query->bindValue(':email',$this->email, PDO::PARAM_STR);
        $query->bindValue(':clave', $this->clave, PDO::PARAM_STR);
        $query->bindValue(':tipo', $this->tipo, PDO::PARAM_STR);
        $query->bindValue(':estado', $this->estado, PDO::PARAM_STR);
        $query->bindValue(':updateDttm', date('Y-m-d H:i:s'), PDO::PARAM_STR);
        $query->execute();
        return $objetoAccesoDato->RetornarUltimoIdInsertado();
    }

    public function Delete()
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $query = $objetoAccesoDato->RetornarConsulta("UPDATE usuarios SET estado = 'borrado', updateDttm = :updateDttm WHERE email = :email");
        $query->bindValue(':email',$this->email, PDO::PARAM_STR);
        $query->bindValue(':updateDttm', date('Y-m-d H:i:s'), PDO::PARAM_STR);
        $query->execute();
        return $objetoAccesoDato->RetornarUltimoIdInsertado();
    }

    public function UpdateLoginDttm()
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $query = $objetoAccesoDato->RetornarConsulta("UPDATE usuarios SET loginDttm = :loginDttm, updateDttm = :updateDttm WHERE email = :email");
        $query->bindValue(':email',$this->email, PDO::PARAM_STR);
        $query->bindValue(':loginDttm', date('Y-m-d H:i:s'), PDO::PARAM_STR);
        $query->bindValue(':updateDttm', date('Y-m-d H:i:s'), PDO::PARAM_STR);
        $query->execute();
        return $objetoAccesoDato->RetornarUltimoIdInsertado();
    }
    
    public static function GetAllEmployeesLoginInfo()
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $query = $objetoAccesoDato->RetornarConsulta("SELECT email,loginDttm FROM usuarios WHERE tipo = 'empleado'");
        return $query->execute() ? $query->fetchAll(PDO::FETCH_CLASS, 'stdClass') : false;
    }
}
?>