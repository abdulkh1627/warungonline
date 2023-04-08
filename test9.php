<?php
namespace Midtrans;

require_once dirname(__FILE__) . '/vendor/midtrans/midtrans-php/Midtrans.php';

//Set Your server key
Config::$serverKey = "SB-Mid-server-9LmXJysHCxnks_dcE0zxV73P";

// Uncomment for production environment
// Config::$isProduction = true;

// Enable sanitization
Config::$isSanitized = true;

// Enable 3D-Secure
Config::$is3ds = true;

// Uncomment for append and override notification URL
// Config::$appendNotifUrl = "https://example.com";
// Config::$overrideNotifUrl = "https://example.com";

include "koneksi.php";

$order_id = $_GET['order_id'];

// Query untuk menampilkan data pesanan berdasarkan order_id yang dikirim
$query = "SELECT * FROM orders WHERE order_id='".$order_id."'";
$sql = mysqli_query($conn, $query);  // Eksekusi/Jalankan query dari variabel $query
$data = mysqli_fetch_array($sql);

$name = $data['customer_name'];
$phone = $data['customer_phone'];
$email = $data['customer_email'];
$total = $data['total'];

// Query untuk menampilkan data produk yang ada di dalam pesanan
$query_items = "SELECT * FROM order_items WHERE order_id='".$order_id."'";
$sql_items = mysqli_query($conn, $query_items);
$items = array();
while ($item = mysqli_fetch_assoc($sql_items)) {
    $items[] = $item;
}

// Required
$transaction_details = array(
    'order_id' => $order_id,
    'gross_amount' => $total, // no decimal allowed for creditcard
);

// Optional
$item_details = array();
foreach ($items as $item) {
    $item_detail = array(
        'id' => $item['id'],
        'price' => $item['product_price'],
        'quantity' => $item['product_qty'],
        'name' => $item['product_name']
    );
    $item_details[] = $item_detail;
}

// Optional
$customer_details = array(
    'first_name' => "$name",
    'last_name' => "",
    'email' => "$email",
    'phone' => "$phone",
);

// Fill transaction details
$transaction = array(
    'transaction_details' => $transaction_details,
    'customer_details' => $customer_details,
    'item_details' => $item_details,
);

try {
    // Get Snap Payment Page URL
    $paymentUrl = Snap::createTransaction($transaction)->redirect_url;
    // Redirect to Snap Payment Page
    header('Location: ' . $paymentUrl);
} catch (\Exception $e) {
    echo $e->getMessage();
}
?>
