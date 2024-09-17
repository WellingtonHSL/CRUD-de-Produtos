<?php

class usePDO {
    private $servername = "localhost";
    private $username = "root";
    private $password = "";
    private $dbname = "meubanco";
    private static $instance = null;
    private $conn;

    private function __construct() {
        $this->connection();
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance->conn;
    }

    private function connection() {
        try {
            $this->conn = new PDO("mysql:host=$this->servername;dbname=$this->dbname", $this->username, $this->password); 
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            if (strpos($e->getMessage(), "Unknown database '$this->dbname'") !== false) {
                $this->createDB();
                $this->connection(); // Reconnect after database creation
            } else {
                die("Connection failed: " . $e->getMessage());
            }
        }
    }

    private function createDB() {
        try {
            $cnx = new PDO("mysql:host=$this->servername", $this->username, $this->password);
            $cnx->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "CREATE DATABASE IF NOT EXISTS $this->dbname";
            $cnx->exec($sql);
            echo "Banco de dados criado com sucesso!<br>";
        } catch (PDOException $e) {
            die("Erro ao criar o banco de dados: " . $e->getMessage());
        }
    }
}
?>