<?php
session_start();

//  1 & 2: Handle username submission and visit time
if (isset($_POST['submit_username'])) {
    $_SESSION['username'] = $_POST['username'];
    $_SESSION['visit_time'] = time();
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
}

function getTimeSinceVisit() {
    if (isset($_SESSION['visit_time'])) {
        return time() - $_SESSION['visit_time'];
    }
    return 0;
}

//  3: Handle email submission
if (isset($_POST['submit_email'])) {
    $_SESSION['email'] = $_POST['email'];
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
}

//  4: Shopping cart functionality
$products = [
    1 => ['name' => 'Product 1', 'price' => 10.99],
    2 => ['name' => 'Product 2', 'price' => 19.99],
    3 => ['name' => 'Product 3', 'price' => 5.99],
    4 => ['name' => 'Product 4', 'price' => 15.99]
];

// Initialize cart
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Add to cart
if (isset($_POST['add_to_cart']) && isset($_POST['product_id'])) {
    $product_id = $_POST['product_id'];
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]++;
    } else {
        $_SESSION['cart'][$product_id] = 1;
    }
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
}

// Remove from cart (new logic - remove one item at a time)
if (isset($_POST['remove_from_cart']) && isset($_POST['product_id'])) {
    $product_id = $_POST['product_id'];
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]--;
        if ($_SESSION['cart'][$product_id] <= 0) {
            unset($_SESSION['cart'][$product_id]);
        }
    }
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
}

// Clear cart
if (isset($_POST['clear_cart'])) {
    $_SESSION['cart'] = [];
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
}

// Calculate cart total
function getCartTotal() {
    global $products;
    $total = 0;
    foreach ($_SESSION['cart'] as $id => $quantity) {
        $total += $products[$id]['price'] * $quantity;
    }
    return $total;
}
