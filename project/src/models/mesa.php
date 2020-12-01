<?php
namespace App\Models;
use Config\AccesoDatos;
use PDO;
use App\Models\FechasTabla;

class Mesa extends FechasTabla
{
    public $mesaCode;
    public $estado;
    public $foto;

    public static function Constructor($mesaCode, $estado, $foto)
    {
        $obj = new Mesa();
        $obj->mesaCode = $mesaCode;
        $obj->estado = $estado;
        $obj->foto = $foto;
        return $obj;
    }

    //==================== CLASS ============================
    public static function GetInstance($obj)
    {
        return self::Constructor($obj->mesaCode, $obj->estado, $obj->foto);
    }
    public static function ListStdToMesa($lista)
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
            if($value->mesaCode != null && $id != null && $value->mesaCode == $id)
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
        if(!self::IsInList($lista, $obj->mesaCode))
        {
            array_push($lista, $obj);
            return $lista;
        }
        return false;
    }

    public static function Remove($lista, $obj)
    {
        $obj = self::GetInstance($obj);
        if(self::IsInList($lista, $obj->mesaCode))
        {
            foreach ($lista as $key => $value)
            {
                if($value->mesaCode != null && $obj->mesaCode != null && $value->mesaCode === $obj->mesaCode)
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
        $query = $objetoAccesoDato->RetornarConsulta('SELECT * FROM mesas');
        return $query->execute() ? $query->fetchAll(PDO::FETCH_CLASS, 'App\Models\Mesa') : false;
    }

    public function Insert()
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $query = $objetoAccesoDato->RetornarConsulta("INSERT INTO mesas (mesaCode, estado, foto, createDttm, updateDttm) VALUES (:mesaCode,:estado,:foto,:createDttm,:updateDttm)");
        $query->bindValue(':mesaCode',$this->mesaCode, PDO::PARAM_STR);
        $query->bindValue(':estado', $this->estado, PDO::PARAM_STR);
        $query->bindValue(':foto', $this->foto, PDO::PARAM_STR);
        $query->bindValue(':createDttm', date('Y-m-d H:i:s'), PDO::PARAM_STR);
        $query->bindValue(':updateDttm', date('Y-m-d H:i:s'), PDO::PARAM_STR);
        $query->execute();
        return $objetoAccesoDato->RetornarUltimoIdInsertado();
    }

    public function Update()
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $query = $objetoAccesoDato->RetornarConsulta("UPDATE mesas SET estado = :estado, foto = :foto, updateDttm = :updateDttm WHERE mesaCode = :mesaCode");
        $query->bindValue(':mesaCode',$this->mesaCode, PDO::PARAM_STR);
        $query->bindValue(':estado', $this->estado, PDO::PARAM_STR);
        $query->bindValue(':foto', $this->foto, PDO::PARAM_STR);
        $query->bindValue(':updateDttm', date('Y-m-d H:i:s'), PDO::PARAM_STR);
        $query->execute();
        return $objetoAccesoDato->RetornarUltimoIdInsertado();
    }

    public function Delete()
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $query = $objetoAccesoDato->RetornarConsulta("DELETE FROM mesas WHERE mesaCode = :mesaCode");
        $query->bindValue(':mesaCode',$this->mesaCode, PDO::PARAM_STR);
        $query->execute();
        return $query->rowCount();
    }

    public static function GetMesaPorPedido($pedidoId)
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $query = $objetoAccesoDato->RetornarConsulta("SELECT * FROM mesas AS m".
                                                    " JOIN comandas AS c ON c.mesaCode = m.mesaCode".
                                                    " JOIN pedidos AS p ON p.comandaCode = c.comandaCode".
                                                    " WHERE p.pedidoId = :pedidoId".
                                                    " LIMIT 1");
        $query->bindValue(':pedidoId', $pedidoId, PDO::PARAM_INT);
        return $query->execute() ? $query->fetchAll(PDO::FETCH_CLASS, 'App\Models\Mesa')[0] : false;
    }
    
    public static function GetMesaMasUsada()
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $query = $objetoAccesoDato->RetornarConsulta("SELECT COUNT(comandaCode) AS vecesUsada, mesaCode AS mesa FROM comandas".
                                                    " GROUP BY mesa".
                                                    " ORDER BY vecesUsada DESC".
                                                    " LIMIT 1");
        return $query->execute() ? $query->fetchAll(PDO::FETCH_CLASS, 'stdClass')[0] : false;
    }
    
    public static function GetMesaMenosUsada()
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $query = $objetoAccesoDato->RetornarConsulta("SELECT COUNT(comandaCode) AS vecesUsada, mesaCode AS mesa FROM comandas".
                                                    " GROUP BY mesa".
                                                    " ORDER BY vecesUsada ASC".
                                                    " LIMIT 1");
        return $query->execute() ? $query->fetchAll(PDO::FETCH_CLASS, 'stdClass')[0] : false;
    }
     
    public static function GetMesaMasFacturo()
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $query = $objetoAccesoDato->RetornarConsulta("SELECT SUM(p.cantidad * a.precio) AS totalFacturado, c.mesaCode AS mesa FROM comandas AS c".
                                                    " JOIN pedidos AS p ON p.comandaCode = c.comandaCode".
                                                    " JOIN alimentos AS a ON a.alimentoId = p.alimentoId".
                                                    " GROUP BY mesa".
                                                    " ORDER BY totalFacturado DESC".
                                                    " LIMIT 1");
        return $query->execute() ? $query->fetchAll(PDO::FETCH_CLASS, 'stdClass')[0] : false;
    }
    
    public static function GetMesaMenosFacturo()
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $query = $objetoAccesoDato->RetornarConsulta("SELECT SUM(p.cantidad * a.precio) AS totalFacturado, c.mesaCode AS mesa FROM comandas AS c".
                                                    " JOIN pedidos AS p ON p.comandaCode = c.comandaCode".
                                                    " JOIN alimentos AS a ON a.alimentoId = p.alimentoId".
                                                    " GROUP BY mesa".
                                                    " ORDER BY totalFacturado ASC".
                                                    " LIMIT 1");
        return $query->execute() ? $query->fetchAll(PDO::FETCH_CLASS, 'stdClass')[0] : false;
    }
    
    public static function GetMesaMayorFacturaIndividual()
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $query = $objetoAccesoDato->RetornarConsulta("SELECT SUM(p.cantidad * a.precio) AS facturado, p.comandaCode AS comanda, c.mesaCode AS mesa FROM comandas AS c".
                                                    " JOIN pedidos AS p ON p.comandaCode = c.comandaCode".
                                                    " JOIN alimentos AS a ON a.alimentoId = p.alimentoId".
                                                    " GROUP BY comanda, mesa".
                                                    " ORDER BY facturado DESC");
        return $query->execute() ? $query->fetchAll(PDO::FETCH_CLASS, 'stdClass') : false;
    }
    
    public static function GetMesaMenorFacturaIndividual()
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $query = $objetoAccesoDato->RetornarConsulta("SELECT SUM(p.cantidad * a.precio) AS facturado, p.comandaCode AS comanda, c.mesaCode AS mesa FROM comandas AS c".
                                                    " JOIN pedidos AS p ON p.comandaCode = c.comandaCode".
                                                    " JOIN alimentos AS a ON a.alimentoId = p.alimentoId".
                                                    " GROUP BY comanda, mesa".
                                                    " ORDER BY facturado ASC");
        return $query->execute() ? $query->fetchAll(PDO::FETCH_CLASS, 'stdClass') : false;
    }
    
    public static function GetMesaFacturoByDates($mesaCode, $fechaDesde, $fechaHasta)
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $query = $objetoAccesoDato->RetornarConsulta("SELECT SUM(p.cantidad * a.precio) AS totalFacturado, c.mesaCode AS mesa FROM comandas AS c".
                                                    " JOIN pedidos AS p ON p.comandaCode = c.comandaCode".
                                                    " JOIN alimentos AS a ON a.alimentoId = p.alimentoId".
                                                    " WHERE c.mesaCode = :mesaCode".
                                                    " AND c.horaFin BETWEEN :fechaDesde AND :fechaHasta".
                                                    " GROUP BY mesa");
        $query->bindValue(':mesaCode', $mesaCode, PDO::PARAM_STR);
        $query->bindValue(':fechaDesde', $fechaDesde, PDO::PARAM_STR);
        $query->bindValue(':fechaHasta', $fechaHasta, PDO::PARAM_STR);
        return $query->execute() ? $query->fetchAll(PDO::FETCH_CLASS, 'stdClass') : false;
    }
}
?>