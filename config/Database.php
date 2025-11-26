<?php
class Database {
    private $host = 'db4free.net';
    private $db_name = 'staynova';
    private $username = 'staynova';
    private $password = 'staynova2025';
    public $conn;

    public function getConnection() {
        $this->conn = null;
        try {
            $dsn = "mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=utf8";
            $this->conn = new PDO($dsn, $this->username, $this->password);
            
            // Configuracion de PDO para que lance excepciones en caso de error
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            // Evitar que PDO simule las consultas preparadas
            $this->conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            
        } catch(PDOException $exception) {
            echo "Error de conexión: " . $exception->getMessage();
            exit; // Detener todo si no nos podemos conectar
        }
        return $this->conn;
    }
}
?>