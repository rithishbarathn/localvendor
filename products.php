<?php
include("connection/connect.php");
error_reporting(0);
session_start();

// Handle Add to Cart
if (isset($_POST['add_to_cart'])) {
    $product_id = intval($_POST['product_id']);
    $quantity = intval($_POST['quantity']);

    // Fetch product details
    $product_query = mysqli_query($db, "SELECT * FROM product WHERE product_id='$product_id'");
    $product = mysqli_fetch_assoc($product_query);

    if ($product) {
        $item = array(
            'product_id' => $product['product_id'],
            'item_name' => $product['item_name'],
            'price' => $product['price'],
            'quantity' => $quantity
        );
        $_SESSION['cart'][$product_id] = $item;
        header("Location: products.php?vendor_id=" . $_GET['vendor_id']);
        exit();
    }
}

// Fetch vendor
if (!isset($_GET['vendor_id'])) {
    die('Vendor not specified.');
}
$vendor_id = intval($_GET['vendor_id']);
$vendor_query = mysqli_query($db, "SELECT * FROM vendors WHERE vendor_id='$vendor_id'");
$vendor = mysqli_fetch_assoc($vendor_query);

if (!$vendor) {
    die('Vendor not found.');
}

// Fetch products
$product_query = mysqli_query($db, "SELECT * FROM product WHERE vendor_id='$vendor_id'");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($vendor['vendor_name']); ?> Products</title>

    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">

    <style>
        .product-card {
            background: #fff;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 10px;
            box-shadow: 0px 5px 20px rgba(0, 0, 0, 0.1);
            text-align: center;
            transition: all 0.3s ease;
        }
        .product-card:hover {
            transform: translateY(-5px);
        }
        .product-card h5 {
            color: #5c4ac7;
            margin-bottom: 10px;
        }
        .product-card p {
            margin-bottom: 0;
        }
        .back-btn {
            margin-bottom: 20px;
            display: inline-block;
            background-color: #5c4ac7;
            color: white;
            padding: 8px 15px;
            border-radius: 8px;
            text-decoration: none;
        }
        .cart-summary {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<?php include "include/header.php"; ?>

<div class="container mt-5 mb-5">
    <a href="vendors.php" class="back-btn">← Back to Vendors</a>

    <h2 class="text-center mb-4"><?php echo htmlspecialchars($vendor['vendor_name']); ?>'s Products</h2>

    <div class="row">
        <!-- Cart Summary -->
        <div class="col-md-4">
            <div class="cart-summary">
                <h4>Your Cart</h4>
                <?php
                $total = 0;
                if (!empty($_SESSION['cart'])) {
                    foreach ($_SESSION['cart'] as $item) {
                        echo '<p><strong>' . htmlspecialchars($item['item_name']) . '</strong> x ' . $item['quantity'] . ' = ₹' . ($item['price'] * $item['quantity']) . '</p>';
                        $total += $item['price'] * $item['quantity'];
                    }
                    echo '<hr><p><strong>Total: ₹' . $total . '</strong></p>';
                    echo '<a href="checkout.php" class="btn btn-success btn-sm">Proceed to Checkout</a>';
                } else {
                    echo '<p>Cart is empty!</p>';
                }
                ?>
            </div>
        </div>

        <!-- Products -->
        <div class="col-md-8">
            <div class="row">
                <?php
                if (mysqli_num_rows($product_query) > 0) {
                    while ($product = mysqli_fetch_assoc($product_query)) {
                        echo '
                        <div class="col-md-6">
                            <div class="product-card">
                                <h5>' . htmlspecialchars($product['item_name']) . '</h5>
                                <p><strong>Price:</strong> ₹' . $product['price'] . '</p>
                                <form method="POST" action="">
                                    <input type="hidden" name="product_id" value="' . $product['product_id'] . '">
                                    <div class="form-group">
                                        <input type="number" name="quantity" value="1" min="1" class="form-control" style="width: 60px; margin: auto;">
                                    </div>
                                    <button type="submit" name="add_to_cart" class="btn btn-primary btn-sm">Add to Cart</button>
                                </form>
                            </div>
                        </div>
                        ';
                    }
                } else {
                    echo '<div class="col-12 text-center"><p>No products available for this vendor.</p></div>';
                }
                ?>
            </div>
        </div>
    </div>
</div>

<?php include "include/footer.php"; ?>

<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>

</body>
</html>
