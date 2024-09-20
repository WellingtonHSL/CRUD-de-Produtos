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
            $db = usePDO::getInstance()->getConnection();
            $sql = "SELECT password FROM users WHERE email = :email"; // Verifique o nome da coluna
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':email', $email);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                $hashedPassword = $result['password'];

                if (password_verify($password, $hashedPassword)) {
                    $_SESSION['email'] = $email;
                    header('Location: homepage.php');
                    exit();
                } else {
                    $_SESSION['error_message'] = "Senha incorreta.";
                    header('Location: ../login.html');
                    exit();
                }
            } else {
                $_SESSION['error_message'] = "Não existe cadastro com este email.";
                header('Location: ../login.html');
                exit();
            }
        } catch (PDOException $e) {
            return "Erro de banco de dados: " . $e->getMessage();
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $login = new UserLogin();
    $error = $login->authenticate($email, $password);

    if ($error) {
        $_SESSION['error_message'] = $error;
        header('Location: login.html');
        exit();
    }
}
?>