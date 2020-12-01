<?php
namespace App\Models;
use Config\AccesoDatos;
use PDO;
use App\Models\FechasTabla;

class Pedido extends FechasTabla
{
    public $pedidoId;
    public $legajo;
    public $comandaCode;
    public $alimentoId;
    public $cantidad;
    public $estado;
    public $horaInicio;
    public $horaFin;

    public static function Constructor($pedidoId, $legajo, $comandaCode, $alimentoId, $cantidad, $estado, $horaInicio, $horaFin)
    {
        $obj = new Pedido();
        $obj->pedidoId = $pedidoId;
        $obj->legajo = $legajo;
        $obj->comandaCode = $comandaCode;
        $obj->alimentoId = $alimentoId;
        $obj->cantidad = $cantidad;
        $obj->estado = $estado;
        $obj->horaInicio = $horaInicio;
        $obj->horaFin = $horaFin;
        return $obj;
    }

    //==================== CLASS ============================
    public static function GetInstance($obj)
    {
        return self::Constructor($obj->pedidoId, $obj->legajo, $obj->comandaCode, $obj->alimentoId, $obj->cantidad, $obj->estado, $obj->horaInicio, $obj->horaFin);
    }
    public static function ListStdToPedido($lista)
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
            if($value->pedidoId != null && $id != null && $value->pedidoId == $id)
                return $value;
        }
        return false;
    }

    public static function IsInList($lista, $id)
    {
        return (!is_null($lista) && count($lista) > 0) ? ((self::FindById($lista, $id) === false) ? false : true) : false;
    }

    public static function FindByPedidoCode($lista, $comandaCode)
    {
        foreach ($lista as $value)
        {
            if($value->comandaCode != null && $comandaCode != null && $value->comandaCode == $comandaCode)
                return $value;
        }
        return false;
    }

    public static function IsInListByPedidoCode($lista, $comandaCode)
    {
        return (!is_null($lista) && count($lista) > 0) ? ((self::FindByPedidoCode($lista, $comandaCode) === false) ? false : true) : false;
    }

    public static function FindByAlimentoId($lista, $alimentoId)
    {
        foreach ($lista as $value)
        {
            if($value->alimentoId != null && $alimentoId != null && $value->alimentoId == $alimentoId)
                return $value;
        }
        return false;
    }

    public static function IsInListByAlimentoId($lista, $alimentoId)
    {
        return (!is_null($lista) && count($lista) > 0) ? ((self::FindByAlimentoId($lista, $alimentoId) === false) ? false : true) : false;
    }

    public static function FindByLegajo($lista, $legajo)
    {
        foreach ($lista as $value)
        {
            if($value->legajo != null && $legajo != null && $value->legajo == $legajo)
                return $value;
        }
        return false;
    }

    public static function IsInListByLegajo($lista, $legajo)
    {
        return (!is_null($lista) && count($lista) > 0) ? ((self::FindByLegajo($lista, $legajo) === false) ? false : true) : false;
    }

    public static function Add($lista, $obj)
    {
        if(!self::IsInList($lista, $obj->pedidoId))
        {
            array_push($lista, $obj);
            return $lista;
        }
        return false;
    }

    public static function Remove($lista, $obj)
    {
        $obj = self::GetInstance($obj);
        if(self::IsInList($lista, $obj->pedidoId))
        {
            foreach ($lista as $key => $value)
            {
                if($value->pedidoId != null && $obj->pedidoId != null && $value->pedidoId === $obj->pedidoId)
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
        $query = $objetoAccesoDato->RetornarConsulta('SELECT * FROM pedidos');
        return $query->execute() ? $query->fetchAll(PDO::FETCH_CLASS, 'App\Models\Pedido') : false;
    }

    public function Insert()
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $query = $objetoAccesoDato->RetornarConsulta("INSERT INTO pedidos (legajo, comandaCode, alimentoId, cantidad, estado, horaInicio, horaFin, createDttm, updateDttm) VALUES (:legajo,:comandaCode,:alimentoId,:cantidad,:estado,:horaInicio,:horaFin,:createDttm,:updateDttm)");
        $query->bindValue(':legajo',$this->legajo, PDO::PARAM_INT);
        $query->bindValue(':comandaCode', $this->comandaCode, PDO::PARAM_STR);
        $query->bindValue(':alimentoId', $this->alimentoId, PDO::PARAM_INT);
        $query->bindValue(':cantidad', $this->cantidad, PDO::PARAM_INT);
        $query->bindValue(':estado', $this->estado, PDO::PARAM_STR);
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
        $query = $objetoAccesoDato->RetornarConsulta("UPDATE pedidos SET legajo = :legajo, comandaCode = :comandaCode, alimentoId = :alimentoId, cantidad = :cantidad, estado = :estado, horaInicio = :horaInicio, horaFin = :horaFin, updateDttm = :updateDttm WHERE pedidoId = :pedidoId");
        $query->bindValue(':legajo',$this->legajo, PDO::PARAM_INT);
        $query->bindValue(':pedidoId',$this->pedidoId, PDO::PARAM_INT);
        $query->bindValue(':comandaCode', $this->comandaCode, PDO::PARAM_STR);
        $query->bindValue(':alimentoId', $this->alimentoId, PDO::PARAM_INT);
        $query->bindValue(':cantidad', $this->cantidad, PDO::PARAM_INT);
        $query->bindValue(':estado', $this->estado, PDO::PARAM_STR);
        $query->bindValue(':horaInicio', $this->horaInicio, PDO::PARAM_STR);
        $query->bindValue(':horaFin', $this->horaFin, PDO::PARAM_STR);
        $query->bindValue(':updateDttm', date('Y-m-d H:i:s'), PDO::PARAM_STR);
        $query->execute();
        return $objetoAccesoDato->RetornarUltimoIdInsertado();
    }

    public function Delete()
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $query = $objetoAccesoDato->RetornarConsulta("DELETE FROM pedidos WHERE pedidoId = :pedidoId");
        $query->bindValue(':pedidoId',$this->pedidoId, PDO::PARAM_INT);
        $query->execute();
        return $query->rowCount();
    }

    public static function GetPendientesBartender($legajo)
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $query = $objetoAccesoDato->RetornarConsulta("SELECT p.pedidoId AS NroPedido, c.mesaCode AS CodigoMesa, p.comandaCode AS CodigoComanda, a.descripcion AS Producto, p.cantidad AS Cantidad".
                                                    " FROM pedidos AS p".
                                                    " JOIN comandas AS c ON c.comandaCode = p.comandaCode".
                                                    " JOIN alimentos AS a ON a.alimentoId = p.alimentoId".
                                                    " WHERE p.estado = 'pendiente'".
                                                    " AND a.categoria IN ('trago', 'vino')");
        $query->bindValue(':legajo',$legajo, PDO::PARAM_INT);
        return $query->execute() ? $query->fetchAll(PDO::FETCH_CLASS, 'stdClass') : false;
    }

    public static function GetPendientesCervecero($legajo)
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $query = $objetoAccesoDato->RetornarConsulta("SELECT p.pedidoId AS NroPedido, c.mesaCode AS CodigoMesa, p.comandaCode AS CodigoComanda, a.descripcion AS Producto, p.cantidad AS Cantidad".
                                                    " FROM pedidos AS p".
                                                    " JOIN comandas AS c ON c.comandaCode = p.comandaCode".
                                                    " JOIN alimentos AS a ON a.alimentoId = p.alimentoId".
                                                    " WHERE p.estado = 'pendiente'".
                                                    " AND a.categoria IN ('cerveza')");
        $query->bindValue(':legajo',$legajo, PDO::PARAM_INT);
        return $query->execute() ? $query->fetchAll(PDO::FETCH_CLASS, 'stdClass') : false;
    }

    public static function GetPendientesCocinero($legajo)
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $query = $objetoAccesoDato->RetornarConsulta("SELECT p.pedidoId AS NroPedido, c.mesaCode AS CodigoMesa, p.comandaCode AS CodigoComanda, a.descripcion AS Producto, p.cantidad AS Cantidad".
                                                    " FROM pedidos AS p".
                                                    " JOIN comandas AS c ON c.comandaCode = p.comandaCode".
                                                    " JOIN alimentos AS a ON a.alimentoId = p.alimentoId".
                                                    " WHERE p.estado = 'pendiente'".
                                                    " AND a.categoria IN ('comida', 'postre')");
        $query->bindValue(':legajo',$legajo, PDO::PARAM_INT);
        return $query->execute() ? $query->fetchAll(PDO::FETCH_CLASS, 'stdClass') : false;
    }

    public static function GetPedidosDeMesa($mesaCode)
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $query = $objetoAccesoDato->RetornarConsulta("SELECT p.* FROM pedidos AS p".
                                                    " JOIN comandas AS c ON c.comandaCode = p.comandaCode".
                                                    " JOIN mesas AS m ON m.mesaCode = c.mesaCode".
                                                    " WHERE m.mesaCode = :mesaCode");
        $query->bindValue(':mesaCode', $mesaCode, PDO::PARAM_STR);
        return $query->execute() ? $query->fetchAll(PDO::FETCH_CLASS, 'App\Models\Pedido') : false;
    }

    public static function GetAlimentoMasVendido()
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $query = $objetoAccesoDato->RetornarConsulta("SELECT a.alimentoId, SUM(p.cantidad) AS cantidad".
                                                    " FROM alimentos AS a".
                                                    " JOIN pedidos AS p ON p.alimentoId = a.alimentoId".
                                                    " GROUP BY a.alimentoId".
                                                    " ORDER BY cantidad DESC".
                                                    " LIMIT 1");
        return $query->execute() ? $query->fetchAll(PDO::FETCH_CLASS, 'stdClass')[0] : false;
    }
    
    public static function GetAlimentoMenosVendido()
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $query = $objetoAccesoDato->RetornarConsulta("SELECT a.alimentoId, SUM(p.cantidad) AS cantidad".
                                                    " FROM alimentos AS a".
                                                    " JOIN pedidos AS p ON p.alimentoId = a.alimentoId".
                                                    " GROUP BY a.alimentoId".
                                                    " ORDER BY cantidad ASC".
                                                    " LIMIT 1");
        return $query->execute() ? $query->fetchAll(PDO::FETCH_CLASS, 'stdClass')[0] : false;
    }

    public static function GetPedidosFueraDeTiempo()
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $query = $objetoAccesoDato->RetornarConsulta("SELECT p.pedidoId, p.legajo, p.comandaCode, a.descripcion, p.cantidad".
                                                    " FROM alimentos AS a".
                                                    " JOIN pedidos AS p ON p.alimentoId = a.alimentoId".
                                                    " WHERE estado = 'entregado fuera de tiempo'");
        return $query->execute() ? $query->fetchAll(PDO::FETCH_CLASS, 'stdClass') : false;
    }

    public static function GetPedidosCancelados()
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $query = $objetoAccesoDato->RetornarConsulta("SELECT p.pedidoId, p.legajo, p.comandaCode, a.descripcion, p.cantidad".
                                                    " FROM alimentos AS a".
                                                    " JOIN pedidos AS p ON p.alimentoId = a.alimentoId".
                                                    " WHERE estado = 'cancelado'");
        return $query->execute() ? $query->fetchAll(PDO::FETCH_CLASS, 'stdClass') : false;
    }
}
?>