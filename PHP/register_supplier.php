<?php 
require_once 'PDO.php';

session_start();

class RegisterSupplier {
    function addSupplier() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $company_name = isset($_POST['company_name']) ? trim($_POST['company_name']) : '';
            $cnpj = isset($_POST['cnpj']) ? trim($_POST['cnpj']) : '';
            $full_name = isset($_POST['full_name']) ? trim($_POST['full_name']) : '';
            $email = isset($_POST['email']) ? trim($_POST['email']) : '';
            $phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
            $address = isset($_POST['address']) ? trim($_POST['address']) : '';

            if (!empty($company_name) && !empty($cnpj) && !empty($full_name) && !empty($email) && !empty($phone) && !empty($address)) {
                $db = usePDO::getInstance();
                $connection = $db->getConnection();

                if ($connection) {
                    try {
                        $sql = "SELECT * FROM supplier_registration WHERE company_name = :company_name";
                        $stmt = $connection->prepare($sql);
                        $stmt->bindParam(':company_name', $company_name);
                        $stmt->execute();

                        if ($stmt->rowCount() > 0) {
                            $_SESSION['error_message'] = "Fornecedor já cadastrado.";
                            header('Location: supplier_registration.php');
                            exit();
                        }

                        $db->insertSupplier($company_name, $cnpj, $full_name, $email, $phone, $address);

                        $_SESSION['success_message'] = "Fornecedor cadastrado com sucesso!";
                        header('Location: supplier_registration.php');
                        exit();
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

$registerSupplier = new RegisterSupplier();
$registerSupplier->addSupplier();
?>