<?php
namespace App\Interfaces;

interface IBaseABM
{
    public function GetOne($request, $response, array $args);
   	public function GetAll($request, $response, array $args);
   	public function Insert($request, $response, array $args);
   	public function Update($request, $response, array $args);
   	public function Delete($request, $response, array $args);
}
?>