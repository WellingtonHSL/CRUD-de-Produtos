<?php
require_once 'PDO.php';
require_once 'user.php';
session_start();

class UserRegister extends User {
    private $db;

    public function __construct($name, $email, $password) {
        $this->db = usePDO::getInstance();
        $this->db->getConnection();
    }

    public function createAccount() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $name = isset($_POST['name']) ? trim($_POST['name']) : '';
            $email = isset($_POST['email']) ? trim($_POST['email']) : '';
            $password = isset($_POST['password']) ? trim($_POST['password']) : '';

            if (!empty($name) && !empty($email) && !empty($password)) {

                $user = new User($name, $email, $password);
                $user->encryptPassword();

                if ($this->db) {
                    try {

                        $sql = "SELECT * FROM users WHERE email = :email";
                        $stmt = $this->db->conn->prepare($sql);
                        $stmt->bindParam(':email', $email);
                        $stmt->execute();

                        if ($stmt->rowCount() > 0) {
                            
                            $_SESSION['error_message'] = "Email já cadastrado.";
                            header('Location: ../create_account.html');
                            exit();
                        }

                        
                        $sql = "INSERT INTO users (name, email, password) VALUES (:name, :email, :password)";
                        $stmt = $this->db->conn->prepare($sql);
                        $stmt->bindParam(':name', $user->getName());
                        $stmt->bindParam(':email', $user->getEmail());
                        $stmt->bindParam(':password', $user->getPassword());

                        if ($stmt->execute()) {
                            
                            $_SESSION['success_message'] = "Conta criada com sucesso!";
                            header('Location: ../login.html');
                            exit();
                        } else {
                            echo "Erro ao criar conta.";
                        }
                    } catch (PDOException $e) {
                        echo "Erro ao executar a consulta: " . $e->getMessage();
                    }
                } else {
                    echo "Erro de conexão com o banco de dados.";
                }
            } else {
                echo "Por favor, preencha todos os campos.";
            }
        } else {
            echo "Método inválido.";
        }
    }
}

$register = new UserRegister('', '', '');
$register->createAccount();
?>