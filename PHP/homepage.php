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
$stmt =  $connection->query($query);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../CSS/homepage.css" media="screen" />

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
        <form action="shopping_cart_process.php" method="post">
            <?php
                foreach($products as $product) {
                    echo '<fieldset>';
                        echo '<h3>' . htmlspecialchars($product['bookName']) . '</h3>';
                        echo '<p>' . htmlspecialchars($product['description']) . '</p>';
                        echo '<div class="product-item">';
                            echo '<h4>R$ ' . htmlspecialchars($product['price']) . '</h4>';
                            echo '<label><input type="checkbox" name="products[]" value="' . htmlspecialchars($product['id']) . '"></label>';
                        echo '</div>';
                    echo '</fieldset>';
                }   
            ?>

            <input type="submit" value="Adicionar no Carrinho">
        </form>
    </main>
</body>
</html>