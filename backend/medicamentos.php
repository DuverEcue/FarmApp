<?php
  require_once 'config/conexion.php';

  class Medicamento{
    private $db;

    public function __construct(){
      $this->db = Conexion::connect();
    }

    public function getMedicamento($limit=6){
      $result = false;
      $sql = "Select id_medicamento, nombre_medicamento, precio_medicamento, 
             nombre_imagen From medicamentos, imagenes
             Where id_medicamento = id_medicamento_imagen
             Group By id_medicamento Order By Rand() Limit $limit";
      $datos = $this->db->query($sql);
      if($datos && $datos->num_rows > 0)
        $result = $datos;
      return $result;
    }

  }
?>