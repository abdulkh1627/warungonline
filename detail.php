<?php
// include database connection file
include_once("koneksi.php");

// check if "id_produk" parameter is present in URL
if (!isset($_GET['id_produk'])) {
    header('Location: index.php');
    exit();
}

// get the value of "id_produk" parameter from URL
$id_produk = $_GET['id_produk'];

// retrieve product details from database
$result = mysqli_query($conn, "SELECT * FROM produk WHERE id_produk = $id_produk");
$num_rows = mysqli_num_rows($result);

// check if there is a product with the given "id_produk"
if ($num_rows == 0) {
    header('Location: index.php');
    exit();
}

// fetch product details as an associative array
$product = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Product Details - <?php echo $product['nama_produk']; ?></title>
</head>
<body>
    <h1><?php echo $product['nama_produk']; ?></h1>
    <p><?php echo $product['deskripsi']; ?></p>
    <p>Rp.<?php echo $product['harga']; ?></p>
    <a href="index.php">Back to Products</a>
    <a href="cart.php?id_produk=<?php echo $product['id_produk']; ?>">Add to Cart</a>
</body>
</html>
