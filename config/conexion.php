<?php
class Conexion {
    private static $instancia = null;
    private $conexion;
    
    private function __construct() {
        // Configuración para producción (Render)
        if (getenv('RENDER')) {
            // En Render
            $host = getenv('DB_HOST');
            $usuario = getenv('DB_USER');
            $password = getenv('DB_PASSWORD');
            $base_datos = getenv('DB_NAME');
        } else {
            // En desarrollo local
            $host = "localhost";
            $usuario = "root";
            $password = "";
            $base_datos = "farmapp";
        }
        
        $this->conexion = new mysqli($host, $usuario, $password, $base_datos);
        
        if($this->conexion->connect_error) {
            die("Error de conexión: " . $this->conexion->connect_error);
        }
        
        $this->conexion->set_charset("utf8");
    }
    
    public static function connect() {
        if(self::$instancia == null) {
            self::$instancia = new Conexion();
        }
        return self::$instancia->conexion;
    }
}
?>