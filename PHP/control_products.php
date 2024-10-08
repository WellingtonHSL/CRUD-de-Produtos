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

$query = "SELECT * FROM product_registration";
$stmt = $connection->query($query);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Excluindo produto
if (isset($_GET['delete_id'])) {
    $product_id = intval($_GET['delete_id']);
    $db->deleteProduct($product_id);
    header("Location: control_products.php");
    exit;
}

// Atualiza produto
if (isset($_GET['update_id']) && $_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_update = intval($_GET['update_id']);
    $bookName = trim($_POST['bookName']);
    $price = trim($_POST['price']);
    $quantity = trim($_POST['quantity']);
    $full_name = trim($_POST['full_name']);
    $description = trim($_POST['description']);

    $db->updateProduct($id_update, $bookName, $full_name, $price, $quantity, $description);
    header("Location: control_products.php");
    exit;
}

// Carrega os dados do produto antes de atualizar
if (isset($_GET['update_id'])) {
    $id_update = intval($_GET['update_id']);
    $product = $db->readProduct($id_update); 
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../CSS/control_products.css" media="screen" />
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
                    <img class="shopping_cart" src="../IMG/cart_icon.png" alt="shopping cart">
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
        <?php foreach ($products as $product): 
            if (isset($_GET['update_id']) && intval($_GET['update_id']) === $product['id']){
                $updateId = isset($id_update) ? $id_update : $product['id'];
                echo '<fieldset>';
                echo '<form method="post" action="control_products.php?update_id=' . $updateId . '">';
                    echo '<div class="product-item">';      
                        echo '<label for="bookName">Nome do Livro</label>';
                        echo '<input type="text" id="bookName" name="bookName" value="' . (isset($product['bookName']) ? htmlspecialchars($product['bookName']) : '') . '" placeholder="Nome do Livro" required>';
                    echo '</div>';
                    echo '<div class="product-item">';        
                        echo '<label for="price">Preço</label>';
                        echo '<input type="number" id="price" name="price" value="' . (isset($product['price']) ? htmlspecialchars($product['price']) : '') . '" placeholder="Preço" required>';
                    echo '</div>';
                    echo '<div class="product-item">';
                        echo '<label for="quantity">Quantidade</label>';
                        echo '<input type="number" id="quantity" name="quantity" value="' . (isset($product['quantity']) ? htmlspecialchars($product['quantity']) : '') . '" placeholder="Quantidade" required>';
                    echo '</div>';
                    echo '<div class="product-item">';    
                        echo '<label for="full_name">Fornecedor</label>';
                        echo '<input type="text" id="full_name" name="full_name" value="' . (isset($product['full_name']) ? htmlspecialchars($product['full_name']) : '') . '" placeholder="Fornecedor" required>';
                    echo '</div>';
                        echo '<div class="product-item">';        
                        echo '<label for="description">Descrição</label>';
                        echo '<input type="text" id="description" name="description" value="' . (isset($product['description']) ? htmlspecialchars($product['description']) : '') . '" placeholder="Descrição" required>';
                        
                        echo '<div class="container-update">';
                            echo '<input type="submit" value="Salvar" class="update-btn">';
                        echo '</div>';
                    echo '</form>';
                echo '</fieldset>';
                
            }else{
                echo '<fieldset>';
                    echo '<h3>' . htmlspecialchars($product['bookName']) . '</h3>';
                    echo '<p>' . htmlspecialchars($product['full_name']) . '</p>';
                    echo '<p>' . htmlspecialchars($product['description']) . '</p>';
                    echo '<label>R$ ' . htmlspecialchars(number_format($product['price'], 2, ',', '.')) . '</label>';
                    
                    echo '<div class="product-bt">';
                        echo '<a href="control_products.php?update_id=' . $product['id'] . '">Editar</a>';
                        echo '<a href="control_products.php?delete_id=' . $product['id'] . '">Excluir</a>';
                    echo '</div>';
                echo '</fieldset>';
            } 
        endforeach; ?>
    </main>
</body>
</html>
