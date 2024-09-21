<?php
require_once 'PDO.php';

session_start();

class ShoppingCart {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getProducts() {
        return $_SESSION['cart'] ?? [];
    }

    public function calculateTotal() {
        $total = 0;
        $products = $this->getProducts();

        foreach ($products as $product_id) {
            $product = $this->readProduct($product_id);
            if ($product) {
                $total += $product['price'];
            }
        }
        return $total;
    }

    public function addProduct($product_id) {
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        if (!in_array($product_id, $_SESSION['cart'])) {
            $_SESSION['cart'][] = $product_id;
        }
    }

    public function readProduct($id) {
        return $this->db->readProduct($id);
    }
}

$db = usePDO::getInstance();
$cart = new ShoppingCart($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['products'])) {
    $selected_products = $_POST['products'];

    foreach ($selected_products as $product_id) {
        $cart->addProduct($product_id);
    }

    header('Location: shopping_cart.php');
    exit();
}
?>