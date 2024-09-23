<?php

class usePDO {
    private $servername = "localhost";
    private $username = "root";
    private $password = "";
    private $dbname = "meubanco";
    private static $instance = null;
    public $conn;

    private function __construct() {
        $this->connection();
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new usePDO();
        }
        return self::$instance;
    }

    private function connection() {
        try {
            
            $this->conn = new PDO("mysql:host=$this->servername;dbname=$this->dbname", $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
          
            if (strpos($e->getMessage(), "Unknown database '$this->dbname'") !== false) {
                $this->conn = new PDO("mysql:host=$this->servername", $this->username, $this->password);
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $this->createDB();
    
                $this->conn = new PDO("mysql:host=$this->servername;dbname=$this->dbname", $this->username, $this->password);
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } else {
                die("Connection failed: " . $e->getMessage());
            }
        }
    }

    private function createDB() {
        try {
            
            $sql = "CREATE DATABASE IF NOT EXISTS $this->dbname";
            $this->conn->exec($sql);
            $this->conn->exec("USE $this->dbname");
        } catch (PDOException $e) {
            die("Erro ao criar o banco de dados: " . $e->getMessage());
        }
    }

    // Método CRUD
    public function createTables() {
        try {
            $this->conn->exec("CREATE TABLE IF NOT EXISTS users (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(100),
                email VARCHAR(100) UNIQUE,
                password  VARCHAR(255)
            )");

            $this->conn->exec("CREATE TABLE IF NOT EXISTS supplier_registration (
                id INT AUTO_INCREMENT PRIMARY KEY,
                company_name VARCHAR(255),
                cnpj VARCHAR(18) UNIQUE,
                full_name VARCHAR(100),
                email VARCHAR(100) UNIQUE,
                phone VARCHAR(20),
                address VARCHAR(100)
            )");

            $this->conn->exec("CREATE TABLE IF NOT EXISTS product_registration (
                id INT AUTO_INCREMENT PRIMARY KEY,
                bookName VARCHAR(255),
                price DECIMAL(10, 2),
                quantity INT,
                full_name VARCHAR(100),
                description TEXT
            )");
            
        } catch (PDOException $e) {
            echo "Erro ao criar tabelas: " . $e->getMessage();
        }
    }

    //MÉTODOS FORNECEDORES
    public function readSupplier($id) {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM supplier_registration WHERE id = :id");
            $stmt->execute(['id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Erro ao ler fornecedor: " . $e->getMessage();
        }
    }

    public function updateSupplier($id, $company_name, $cnpj, $full_name, $email, $phone, $description) {
        try {
            $stmt = $this->conn->prepare("UPDATE supplier_registration SET company_name = :company_name, cnpj = :cnpj, full_name = :full_name, phone = :phone, email = :email WHERE id = :id");
            $stmt->execute([
                'id' => $id,
                'company_name' => $company_name,
                'cnpj' => $cnpj,
                'full_name' => $full_name,
                'email' => $email,
                'phone' => $phone,
                'description' => $description
            ]);
            echo "Fornecedor atualizado com sucesso!";
        } catch (PDOException $e) {
            echo "Erro ao atualizar fornecedor: " . $e->getMessage();
        }
    }

    public function deleteSupplier($id) {
        try {
            $stmt = $this->conn->prepare("DELETE FROM supplier_registration WHERE id = :id");
            $stmt->execute(['id' => $id]);
            echo "Fornecedor deletado com sucesso!";
        } catch (PDOException $e) {
            echo "Erro ao deletar fornecedor: " . $e->getMessage();
        }
    }

    public function insertSupplier($company_name, $cnpj, $full_name, $email, $phone, $address) {
        try {
            $stmt = $this->conn->prepare("INSERT INTO supplier_registration (company_name, cnpj, full_name, email, phone, address) 
                                            VALUES (:company_name, :cnpj, :full_name, :email, :phone, :address)");
            $stmt->bindParam(':company_name', $company_name);
            $stmt->bindParam(':cnpj', $cnpj);
            $stmt->bindParam(':full_name', $full_name);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':phone', $phone);
            $stmt->bindParam(':address', $address);

            if ($stmt->execute()) {
                echo "Fornecedor cadastrado com sucesso!";
            } else {
                echo "Erro ao cadastrar fornecedor.";
            }
        } catch (PDOException $e) {
            echo "Erro ao inserir fornecedor: " . $e->getMessage();
        }
    }

    //MÉTODOS PRODUTOS
    public function readProduct($id) {
        try {
            $stmt = array();
            $stmt = $this->conn->prepare("SELECT * FROM product_registration WHERE id = :id");
            $stmt->execute(['id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Erro ao ler produto: " . $e->getMessage();
        }
    }

    public function updateProduct($id, $bookName, $full_name, $price, $quantity, $description) {
        try {
            $stmt = $this->conn->prepare("UPDATE product_registration SET bookName = :bookName, full_name = :full_name, price = :price, quantity = :quantity, description = :description WHERE id = :id");
            $stmt->execute([
                'id' => $id,
                'bookName' => $bookName,
                'full_name' => $full_name,
                'price' => $price,
                'quantity' => $quantity,
                'description' => $description
            ]);
            echo "Produto atualizado com sucesso!";
        } catch (PDOException $e) {
            echo "Erro ao atualizar produto: " . $e->getMessage();
        }
    }

    public function deleteProduct($id) {
        try {
            $stmt = $this->conn->prepare("DELETE FROM product_registration WHERE id = :id");
            $stmt->execute(['id' => $id]);
            echo "Produto deletado com sucesso!";
        } catch (PDOException $e) {
            echo "Erro ao deletar produto: " . $e->getMessage();
        }
    }

    public function insertProduct($bookName, $price, $quantity, $full_name, $description) {
        try {
            $stmt = $this->conn->prepare("INSERT INTO product_registration (bookName, price, quantity, full_name, description) VALUES (:bookName, :price, :quantity, :full_name, :description)");
            $stmt->bindParam(':bookName', $bookName);
            $stmt->bindParam(':price', $price);
            $stmt->bindParam(':quantity', $quantity);
            $stmt->bindParam(':full_name', $full_name);
            $stmt->bindParam(':description', $description);
            
            $stmt->execute();
        } catch (PDOException $e) {
            echo "Erro ao inserir produto: " . $e->getMessage();
        }
    }

    public function getConnection() {
       
        $this->createTables(); 
        return $this->conn;
    }
}
?>