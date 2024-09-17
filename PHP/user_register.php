<?php
require_once 'PDO.php';
require_once 'user.php';

session_start();

class UserRegister extends User {

    public function createAccount() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $name = isset($_POST['name']) ? trim($_POST['name']) : '';
            $email = isset($_POST['email']) ? trim($_POST['email']) : '';
            $password = isset($_POST['password']) ? trim($_POST['password']) : '';

            if (!empty($name) && !empty($email) && !empty($password)) {
                $user = new User($name, $email, $password);
                $user->encryptPassword(); 
                $db = usePDO::getInstance();

                if ($db) {
                    try {
                        // Verifica se o email já está cadastrado
                        $sql = "SELECT * FROM users WHERE email = :email";
                        $stmt = $db->prepare($sql);
                        $stmt->bindParam(':email', $email);
                        $stmt->execute();

                        if ($stmt->rowCount() > 0) {
                            // Email já cadastrado
                            $_SESSION['error_message'] = "Email já cadastrado.";
                            header('Location: ../create_account.html');
                            exit();
                        }

                        // Se o email não existe, cria a nova conta
                        $sql = "INSERT INTO users (nome, email, senha) VALUES (:nome, :email, :senha)";
                        $stmt = $db->prepare($sql);
                        $stmt->bindParam(':nome', $user->getName());
                        $stmt->bindParam(':email', $user->getEmail());
                        $stmt->bindParam(':senha', $user->getPassword());

                        if ($stmt->execute()) {
                            // Conta criada com sucesso, redireciona para a página de login
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