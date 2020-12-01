<?php
namespace App\Models;
use Config\AccesoDatos;
use PDO;
use App\Models\FechasTabla;

class Comanda extends FechasTabla
{
    public $comandaCode;
    public $legajo;
    public $mesaCode;
    public $horaInicio;
    public $horaFin;

    public static function Constructor($comandaCode, $legajo, $mesaCode, $horaInicio, $horaFin)
    {
        $obj = new Comanda();
        $obj->comandaCode = $comandaCode;
        $obj->legajo = $legajo;
        $obj->mesaCode = $mesaCode;
        $obj->horaInicio = $horaInicio;
        $obj->horaFin = $horaFin;
        return $obj;
    }

    //==================== CLASS ============================
    public static function GetInstance($obj)
    {
        return self::Constructor($obj->comandaCode, $obj->legajo, $obj->mesaCode, $obj->horaInicio, $obj->horaFin);
    }
    public static function ListStdToComanda($lista)
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
            if($value->comandaCode != null && $id != null && $value->comandaCode == $id)
                return $value;
        }
        return false;
    }

    public static function IsInList($lista, $id)
    {
        return (!is_null($lista) && count($lista) > 0) ? ((self::FindById($lista, $id) === false) ? false : true) : false;
    }

    public static function FindComandasByMesa($lista, $mesaCode)
    {
        $comandas = array();
        $isEmpty = true;
        foreach ($lista as $value)
        {
            if($value->mesaCode != null && $mesaCode != null && $value->mesaCode == $mesaCode)
            {
                array_push($comandas, $value);
                $isEmpty = false;
            }
        }
        if(!$isEmpty)
            return $comandas;
        return false;
    }

    public static function IsInListByMesa($lista, $mesaCode)
    {
        return (!is_null($lista) && count($lista) > 0) ? ((self::FindComandasByMesa($lista, $mesaCode) === false) ? false : true) : false;
    }

    public static function Add($lista, $obj)
    {
        if(!self::IsInList($lista, $obj->comandaCode))
        {
            array_push($lista, $obj);
            return $lista;
        }
        return false;
    }

    public static function Remove($lista, $obj)
    {
        $obj = self::GetInstance($obj);
        if(self::IsInList($lista, $obj->comandaCode))
        {
            foreach ($lista as $key => $value)
            {
                if($value->comandaCode != null && $obj->comandaCode != null && $value->comandaCode === $obj->comandaCode)
                {
                    unset($lista[$key]);
                    return $lista;
                }
            }
        }
        return false;
    }
    
    public static function RandomCode($n)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'; 
        $randomString = ''; 
    
        for ($i = 0; $i < $n; $i++) { 
            $index = rand(0, strlen($characters) - 1); 
            $randomString .= $characters[$index]; 
        } 
    
        return $randomString;
    }

    //==================== DAO ============================
    public static function GetAll()
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $query = $objetoAccesoDato->RetornarConsulta('SELECT * FROM comandas');
        return $query->execute() ? $query->fetchAll(PDO::FETCH_CLASS, 'App\Models\Comanda') : false;
    }

    public function Insert()
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $query = $objetoAccesoDato->RetornarConsulta("INSERT INTO comandas (comandaCode, legajo, mesaCode, horaInicio, horaFin, createDttm, updateDttm) VALUES (:comandaCode,:legajo,:mesaCode,:horaInicio,:horaFin,:createDttm,:updateDttm)");
        $query->bindValue(':comandaCode',$this->comandaCode, PDO::PARAM_STR);
        $query->bindValue(':legajo', $this->legajo, PDO::PARAM_INT);
        $query->bindValue(':mesaCode', $this->mesaCode, PDO::PARAM_STR);
        $query->bindValue(':horaInicio', $this->horaInicio, PDO::PARAM_STR);
        $query->bindValue(':horaFin', $this->horaFin, PDO::PARAM_STR);
        $query->bindValue(':createDttm', date('Y-m-d H:i:s'), PDO::PARAM_STR);
        $query->bindValue(':updateDttm', date('Y-m-d H:i:s'), PDO::PARAM_STR);
        $query->execute();
        return $objetoAccesoDato->RetornarUltimoIdInsertado();
    }

    public function Update()
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $query = $objetoAccesoDato->RetornarConsulta("UPDATE comandas SET legajo = :legajo, mesaCode = :mesaCode, horaInicio = :horaInicio, horaFin = :horaFin, updateDttm = :updateDttm WHERE comandaCode = :comandaCode");
        $query->bindValue(':comandaCode',$this->comandaCode, PDO::PARAM_STR);
        $query->bindValue(':legajo', $this->legajo, PDO::PARAM_INT);
        $query->bindValue(':mesaCode', $this->mesaCode, PDO::PARAM_STR);
        $query->bindValue(':horaInicio', $this->horaInicio, PDO::PARAM_STR);
        $query->bindValue(':horaFin', $this->horaFin, PDO::PARAM_STR);
        $query->bindValue(':updateDttm', date('Y-m-d H:i:s'), PDO::PARAM_STR);
        $query->execute();
        return $objetoAccesoDato->RetornarUltimoIdInsertado();
    }

    public function Delete()
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $query = $objetoAccesoDato->RetornarConsulta("DELETE FROM comandas WHERE comandaCode = :comandaCode");
        $query->bindValue(':comandaCode',$this->comandaCode, PDO::PARAM_STR);
        $query->execute();
        return $query->rowCount();
    }
    
    public static function GetMinutosEstimadosDePedidoActivo($comandaCode, $mesaCode)
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $query = $objetoAccesoDato->RetornarConsulta("SELECT p.pedidoId, a.minutosDePreparacion".
                                                    " FROM comandas AS c".
                                                    " JOIN pedidos AS p ON p.comandaCode = c.comandaCode".
                                                    " JOIN alimentos AS a ON a.alimentoId = p.alimentoId".
                                                    " WHERE c.comandaCode = :comandaCode".
                                                    " AND c.mesaCode = :mesaCode".
                                                    " AND p.horaFin = ''");
        $query->bindValue(':comandaCode',$comandaCode, PDO::PARAM_STR);
        $query->bindValue(':mesaCode', $mesaCode, PDO::PARAM_STR);
        return $query->execute() ? $query->fetchAll(PDO::FETCH_CLASS, 'stdClass') : false;
    }
}
?>