<?php 
require_once 'PDO.php';

session_start();

class RegisterProducts {
    public function addProducts() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $bookName = isset($_POST['bookName']) ? trim($_POST['bookName']) : '';
            $price = isset($_POST['price']) ? trim($_POST['price']) : '';
            $quantity = isset($_POST['quantity']) ? trim($_POST['quantity']) : '';
            $full_name = isset($_POST['full_name']) ? trim($_POST['full_name']) : '';
            $description = isset($_POST['description']) ? trim($_POST['description']) : '';

            if (!empty($bookName) && !empty($price) && !empty($quantity) && !empty($full_name) && !empty($description)) {
                $db = usePDO::getInstance();
                $connection = $db->getConnection();

                if ($connection) {
                    try {
                        $sql = "SELECT * FROM product_registration WHERE bookName = :bookName";
                        $stmt = $connection->prepare($sql);
                        $stmt->bindParam(':bookName', $bookName);
                        $stmt->execute();

                        if ($stmt->rowCount() > 0) {
                            $_SESSION['error_message'] = "Produto já cadastrado.";
                            header('Location: product_registration.php');
                            exit();
                        }

                        $db->insertProduct($bookName, $price, $quantity, $full_name, $description);

                        $_SESSION['success_message'] = "Produto cadastrado com sucesso!";
                        header('Location: control_products.php');
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

$register = new RegisterProducts();
$register->addProducts();
?>