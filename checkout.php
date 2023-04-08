<?php
session_start();
include('koneksi.php');

// daftar metode pembayaran
$payment_methods = array(
    'cod' => 'Cash on Delivery COD',
    'bank_transfer' => 'Bank Transfer',
    'credit_card' => 'Credit Card',
);

// ongkos kirim
$shipping_cost = 5000;

// jika tombol "Checkout" ditekan
if(isset($_POST['checkout'])) {
    // ambil data dari form
    $name = $_POST['customer_name'];
    $address = $_POST['customer_address'];
    //$phone = $_POST['customer_phone'];
    $email = $_POST['customer_email'];
    $payment_method = $_POST['payment_method'];
   
   
   // definisi fungsi formatPhoneNumber
function formatPhoneNumber($phone) {
    // hilangkan semua karakter selain angka
    $phone = preg_replace("/[^0-9]/", "", $phone);
  
    // tambahkan kode negara +62 pada awal nomor handphone jika tidak ada
    if (substr($phone, 0, 2) == "08") {
      $phone = "+62" . substr($phone, 1);
    }
  
    // tambahkan tanda hubung (-) setelah 3 digit dan 7 digit
    $phone = substr_replace($phone, "-", 3, 0);
    $phone = substr_replace($phone, "-", 7, 0);
    $phone = substr_replace($phone, "-", 11, 0);
  
    return $phone;
  }
  
  // kode untuk memanggil fungsi formatPhoneNumber
  $phone = $_POST['customer_phone'];
  $formatted_phone = formatPhoneNumber($phone);
  

    // validasi data yang diinputkan
    if(empty($name) || empty($address) || empty($phone) || empty($email) || empty($payment_method)) {
        echo '<div class="alert alert-danger">Please fill all required fields.</div>';
    } else{
        // simpan data order ke database
        // simpan data order ke database
// generate nomor pesanan unik
$order_id = "ORD" .  rand(10000, 99999);

$total = 0;
foreach($_SESSION['cart'] as $item) {
    if(isset($item['product_qty']) && isset($item['product_price'])) {
        $total += $item['product_qty'] * $item['product_price'];
    }
}
$total += $shipping_cost;
$order_date = date('Y-m-d H:i:s');
$sql = "INSERT INTO orders (order_id, customer_name, customer_address, customer_phone, customer_email, payment_method, total, order_date) VALUES ('$order_id', '$name', '$address', '$formatted_phone', '$email', '$payment_method', $total, '$order_date')";
mysqli_query($conn, $sql);

        echo '<div class="alert alert-success">Order '. $order_id.'has been created.</div>';

// simpan data order item ke database
foreach($_SESSION['cart'] as $item) {
    $product_id = $item['product_id'];
    $product_name = $item['product_name'];
    $product_qty = $item['product_qty'];
    $product_price = $item['product_price'];
    $subtotal = $product_qty * $product_price;
    $sql = "INSERT INTO order_items (order_id, product_id, product_name, product_qty, product_price, subtotal) VALUES ('$order_id', $product_id, '$product_name', $product_qty, $product_price, $subtotal)";
    mysqli_query($conn, $sql);
    
}

        

    }

   

        // kosongkan session cart
        unset($_SESSION['cart']);

        // tampilkan pesan sukses
        echo '<div class="alert alert-success">Thank you for your order. Your order ID is '.$order_id.'. We will contact you shortly for the payment details.</div>';
    

    }
    
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-aFq/bzH65dt+w6FI2ooMVUpc+21e0SRygnTpmBvdBgSdnuTN7QbdgL+OapgHtvPp" crossorigin="anonymous">
</head>
<body>
<main class="container my-4">
    <div class="row">
        <div class="col-md-6 offset-md-3">
            <div class="card">
                <div class="card-header">
                    <h3>Checkout</h3>
                    </div>
                <div class="card-body">
              <?php
                // Mendapatkan data keranjang belanja
  $cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : array();

  // Jika keranjang belanja kosong, redirect ke halaman produk
  if(empty($cart)) {
    header("Location: test9.php?order_id=$order_id");
   
  }




  ?>
                    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                       
                <div class="card-body">
                    <form method="post">
                        <div class="mb-3">
                            <label for="customer_name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="customer_name" name="customer_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="customer_address" class="form-label">Address</label>
                            <textarea class="form-control" id="customer_address" name="customer_address" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="customer_phone" class="form-label">Phone</label>
                            <input type="text" class="form-control" id="customer_phone" name="customer_phone" required>
                        </div>
                        <div class="mb-3">
                            <label for="customer_email" class="form-label">Email</label>
                            <input type="text" class="form-control" id="customer_email" name="customer_email" required>
                        </div>
                        <div class="mb-3">
                            <label for="payment_method" class="form-label">Payment Method</label>
                            <select class="form-select" id="payment_method" name="payment_method" required>
                                <option value="">Choose Payment Method</option>
                                <?php foreach($payment_methods as $key => $value) : ?>
                                    <option value="<?= $key ?>"><?= $value ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Order Summary</label>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Qty</th>
                                        <th>Price</th>
                                        <th>Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $total = 0;
                                    foreach($_SESSION['cart'] as $item) :
                                        $product_name = $item['product_name'];
                                        $product_qty = $item['product_qty'];
                                        $product_price = $item['product_price'];
                                        $subtotal = $product_qty * $product_price;
                                        $total += $subtotal;
                                    ?>
                                        <tr>
                                            <td><?= $product_name ?></td>
                                            <td><?= $product_qty ?></td>
                                            <td><?= number_format($product_price, 0, ',', '.') ?></td>
                                            <td><?= number_format($subtotal, 0, ',', '.') ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                    <tr>
                                        <td colspan="3" align="right">Shipping Cost</td>
                                        <td><?= number_format($shipping_cost, 0, ',', '.') ?></td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" align="right">Total</td>
                                        <td><?= number_format($total + $shipping_cost, 0, ',', '.') ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <button type="submit" class="btn btn-primary" name="checkout">Bayar</button>
                        <a href="cart.php" class="btn btn-secondary">Back to Cart</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>
</body>
</html>
                    