<?php
require_once 'PDO.php';
require_once 'shopping_cart_process.php';

//RETORNAR O NOME LOGADO
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

//RETORNA OS PRODUTOS ADICIONADOS NO CARRINHO
$db = usePDO::getInstance();
$cart = new ShoppingCart($db);
$cart_products = $cart->getProducts();
$total_price = $cart->calculateTotal();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['products'])) {
    $selected_products = $_POST['products'];

    foreach ($selected_products as $product_id) {
        $cart->addProduct($product_id);
    }

    header('Location: shopping_cart.php');
    exit();
}

if (empty($cart_products)) {
    header('Location: empty_shopping_cart.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../CSS/shopping_cart.css" media="screen" />

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
        <div class="left_container">
        <?php
            foreach ($cart_products as $product_id) {
                $product = $cart->readProduct($product_id);
                if ($product) {
                    echo '<fieldset>';
                        echo '<h3>' . htmlspecialchars($product['bookName']) . '</h3>';
                        echo '<p>' . htmlspecialchars($product['description']) . '</p>';
                        echo '<h4>R$ ' . htmlspecialchars($product['price']) . '</h4>';    
                    echo '</fieldset>';
                }
            }
        ?>
        </div>

        <div class="right_container">
        <form action="checkout.php" method="post">
            <h2>Resumo do Pedido</h2>
            <fieldset>
                <?php
                    foreach ($cart_products as $product_id) {
                        $product = $cart->readProduct($product_id);
                        if ($product) {   
                                echo '<h4>R$ ' . htmlspecialchars($product['price']) . '</h4>';
                        }
                    }
                ?>
                <hr class="separator">
                <h3>Total: R$ <?php echo number_format($total_price, 2, ',', '.'); ?></h3>
            </fieldset>
            <input type="submit" value="Finalizar Compra">
        </form>
        </div>
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