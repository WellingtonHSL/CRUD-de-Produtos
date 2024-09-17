<?php
require_once 'PDO.php';
require_once 'User.php';

session_start();

class UserLogin {
    public function authenticate($email, $password) {
        if (empty($email) || empty($password)) {
            return "Por favor, preencha todos os campos.";
        }

        try {
            $db = usePDO::getInstance();
            $sql = "SELECT senha FROM users WHERE email = :email";
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':email', $email);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                $hashedPassword = $result['senha'];

                if (password_verify($password, $hashedPassword)) {
                    // Se a senha estiver correta, redireciona para a homepage
                    $_SESSION['user_email'] = $email;
                    header('Location: homepage.php');
                    exit();
                } else {
                    // Senha incorreta, redireciona para a página de login com mensagem de erro
                    $_SESSION['error_message'] = "Senha incorreta.";
                    header('Location: ../login.html');
                    exit();
                }
            } else {
                // Email não existe no banco de dados
                $_SESSION['error_message'] = "Não existe cadastro com este email.";
                header('Location: ../login.html');
                exit();
            }
        } catch (PDOException $e) {
            return "Erro de banco de dados: " . $e->getMessage();
        }
    }
}

// Processamento do formulário
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $login = new UserLogin();
    $error = $login->authenticate($email, $password);

    if ($error) {
        // Exibe a mensagem de erro se houver
        $_SESSION['error_message'] = $error;
        header('Location: login.html');
        exit();
    }
}
?>