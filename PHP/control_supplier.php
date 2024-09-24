<?php
require_once 'PDO.php';
session_start();

$name = '';

if (isset($_SESSION['email'])) {
    $user_email = $_SESSION['email'];
    
    try {
        $db = usePDO::getInstance()->getConnection();
        
        $sql = "SELECT name FROM users WHERE email = :email";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':email', $user_email);
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $name = htmlspecialchars($result['name']);
        }
    } catch (PDOException $e) {
        error_log('Erro ao buscar nome: ' . $e->getMessage());
    }
}

$db = usePDO::getInstance();
$connection = $db->getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_POST['action'] === 'update') {
        $id = $_POST['supplier_id'];
        $company_name = $_POST['company_name'];
        $cnpj = $_POST['cnpj'];
        $full_name = $_POST['full_name'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];

        try {
            $db = usePDO::getInstance();
            $db->updateSupplier($id, $company_name, $cnpj, $full_name, $email, $phone);
        } catch (PDOException $e) {
            echo "Erro ao atualizar fornecedor: " . $e->getMessage();
        }
    }
    
    elseif ($_POST['action'] === 'delete') {
        $id = $_POST['supplier_id'];

        try {
            $db = usePDO::getInstance();
            $db->deleteSupplier($id);
        } catch (PDOException $e) {
            echo "Erro ao excluir fornecedor: " . $e->getMessage();
        }
    }
}

$query = "SELECT * FROM supplier_registration";
$stmt =  $connection->query($query);
$suppliers = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../CSS/control_supplier.css" media="screen" />

    <title>UNIVERSO LITERÁRIO</title>
</head>
<body>
    <header>
        <nav class="nav_header">
            <h1><a href="homepage.php" class="nav_title">UNIVERSO <b>LITERÁRIO</b></a></h1>
            <ul class="nav_header_a">
                <li class="welcome">Olá, <?php echo htmlspecialchars($name); ?>!</li>
                <li><a href="logout.php">
                    <img class="logout_icon" src="../IMG/logout_icon.png" alt="logout icon">
                    </a></li>
                <li><a href="shopping_cart.php">
                        <img  class="shopping_cart" src="../IMG/cart_icon.png" alt="shopping cart">
                    </a></li>
            </ul>
        </nav>
        <nav class="nav_subheader">
            <ul class="nav_subheader_a">
                <li><a href="product_registration.php">Registrar Produto</a></li>
                <li><a href="supplier_registration.php">Registrar Fornecedor</a></li>
                <li><a href="control_supplier.php">Controle de Fornecedores</a></li>
                <li><a href="control_products.php">Controle de Produtos</a></li>
            </ul>
        </nav>
    </header>

    <main>
    <?php
        foreach ($suppliers as $supplier) {
            if (isset($_POST['action']) && $_POST['action'] === 'edit' && $_POST['supplier_id'] == $supplier['id']) {
            
                echo '<fieldset>';
                    echo '<form method="POST">';
                            echo '<input type="hidden" name="supplier_id" value="' . htmlspecialchars($supplier['id']) . '">';

                        echo '<div class="supplier-item">';
                            echo '<label>Nome da Empresa: <input type="text" name="company_name" value="' . htmlspecialchars($supplier['company_name']) . '"></label>';
                            echo '<label>CNPJ: <input type="number" name="cnpj" value="' . htmlspecialchars($supplier['cnpj']) . '"></label>';
                        echo '</div>';

                            echo '<label>Nome Completo: <input type="text" name="full_name" value="' . htmlspecialchars($supplier['full_name']) . '"></label>';

                        echo '<div class="supplier-item">';
                            echo '<label>Email: <input type="email" name="email" value="' . htmlspecialchars($supplier['email']) . '"></label>';
                            echo '<label>Telefone: <input type="tel" name="phone" value="' . htmlspecialchars($supplier['phone']) . '"></label>';
                        echo '</div>';

                        echo '<label>Endereço: <input type="text" name="address" value="' . htmlspecialchars($supplier['address']) . '"></label><br>';

                        echo '<div class="container-update">';
                            echo '<button type="submit" name="action" value="update" class="update-btn">Salvar</button>';
                        echo '</div>';
                    echo '</form>';
                echo '</fieldset>';
            } else {
                echo '<fieldset>';
                    echo '<div class="supplier-item">';
                        echo '<p>Razão Social: <span style="color: #000000; font-weight: 600;">' . htmlspecialchars($supplier['company_name']) . '</spam></p>';
                        echo '<p>CNPJ: <span style="color: #000000; font-weight: 600;">' . htmlspecialchars($supplier['cnpj']) . '</spam></p>';
                    echo '</div>';

                    echo '<p>Fornecedor: <span style="color: #000000; font-weight: 600;">' . htmlspecialchars($supplier['full_name']) . '</spam></p>';

                    echo '<div class="supplier-item">';
                        echo '<p>E-mail: <span style="color: #000000; font-weight: 600;">' . htmlspecialchars($supplier['email']) . '</spam></p>';
                        echo '<p>Telefone: <span style="color: #000000; font-weight: 600;">' . htmlspecialchars($supplier['phone']) . '</spam></p>';
                    echo '</div>';

                    echo '<p>Endereço: <span style="color: #000000; font-weight: 600;">' . htmlspecialchars($supplier['address']) . '</spam></p>';

                    echo '<form method="POST">';
                        echo '<input type="hidden" name="supplier_id" value="' . htmlspecialchars($supplier['id']) . '">';

                        echo '<div class="supplier-bt">';
                            echo '<button type="submit" name="action" value="edit" class="edit-btn">Atualizar</button>';
                            echo '<button type="submit" name="action" value="delete" class="delete-btn">Excluir</button>';
                        echo '</div>';
                    echo '</form>';
                echo '</fieldset>';
            }
        }
        ?>              
    </main>

    <footer>
        <nav class="nav_footer"> 
            <p>©2024 UNIVERSO LITERÁRIO</p>
           
        <ul>
            <div class="nav_footer_div"><p>Contato</p></div> 
            <li><img class="linkedin_icon" src="../IMG/linkedIn_icon.png" alt="LinkedIn Icon"><a href="https://www.linkedin.com/in/welllington-henrique-silva-lima/">Wellington Henrique</a></li>
            <li><img class="linkedin_icon" src="../IMG/linkedIn_icon.png" alt="LinkedIn Icon"><a href="https://www.linkedin.com/in/glauber-shoity-nakai-529549273/">Glauber Shoity</a></li>
            <li><img class="linkedin_icon" src="../IMG/linkedIn_icon.png" alt="LinkedIn Icon"><a href="https://www.linkedin.com/in/kamilla-barros-silva-85537819a/">Kamilla Silva</a></li>
        </ul>
    </nav>
    </footer>
</body>
</html>