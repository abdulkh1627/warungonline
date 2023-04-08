<?php
session_start();
include('koneksi.php');

// ambil data produk dari database
$query = mysqli_query($conn, "SELECT * FROM produk");

if(isset($_POST['submit'])) {
    $product_id = $_POST['product_id'];
    $product_qty = $_POST['product_qty'];
    $product_price = $_POST['product_price'];
    
    // tambahkan produk ke dalam session cart
    $_SESSION['cart'][$product_id] = array(
        'product_id' => $product_id,
        'product_qty' => $product_qty,
        'product_price' => $product_price
    );
    
    // tampilkan pesan sukses
    echo '<div class="alert alert-success">Product added to cart</div>';
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Bootstrap demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous" />
    <link rel="stylesheet" type="text/css" href="styles.css" />
    <script src="https://kit.fontawesome.com/fe828ad468.js" crossorigin="anonymous"></script>
  </head>
  <body>
    <nav class="navbar navbar-dark bg-dark fixed-top">
      <div class="container-fluid">
        <a class="navbar-brand" href="#">Warun R3P</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasDarkNavbar" aria-controls="offcanvasDarkNavbar" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="offcanvas offcanvas-end text-bg-dark" tabindex="-1" id="offcanvasDarkNavbar" aria-labelledby="offcanvasDarkNavbarLabel">
          <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="offcanvasDarkNavbarLabel">Dark offcanvas</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
          </div>
          <div class="offcanvas-body">
            <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
              <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="index.php">Home</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#">Link</a>
              </li>
              <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"> Dropdown </a>
                <ul class="dropdown-menu dropdown-menu-dark">
                  <li><a class="dropdown-item" href="#">Action</a></li>
                  <li><a class="dropdown-item" href="#">Another action</a></li>
                  <li>
                    <hr class="dropdown-divider" />
                  </li>
                  <li><a class="dropdown-item" href="#">Something else here</a></li>
                </ul>
              </li>
            </ul>
            <form class="d-flex mt-3" role="search">
              <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search" />
              <button class="btn btn-success" type="submit">Search</button>
            </form>
          </div>
        </div>
      </div>
    </nav>
    <div class="container-sm">
      <div class="row g-0 bg-body-secondary position-responsive mt-4">
        <div class="col-md-6 mb-md-0 p-md-4">
          <img src="img/web1.png" class="w-100 rounded-start-pill" alt="..." />
        </div>
        <div class="col-md-6 p-4 ps-md-0">
          <h3 class="mt-0 text-succes">Menu Khusus Ramadhan 1444 H</h3>
          <p>Kami menyediakan menu khusus ramadhan dengan berbagai macam kuliner diindonesia terutama yang berupa takjil berbuka puasa .</p>
          <a href="#" class="stretched-link">Go somewhere</a>
        </div>
      </div>
    </div>
    <div class="container mt-3">
      <div class="row gy-3">
        <?php while($produk = mysqli_fetch_assoc($query)) { ?>
        <div class="col-md-3">
          <div class="card mb-2 bg-light">
            <img class="card-img-top" src="<?php echo $produk['gambar']; ?>" alt="Card image cap" />
            <div class="card-body">
              <h5 class="card-title"><?php echo $produk['nama_produk']; ?></h5>
              <p class="card-text"><?php echo $produk['deskripsi']; ?></p>
              <p class="card-text">Rp.<?php echo number_format($produk['harga'],0,",","."); ?></p>
            </div>
            <div class="card-footer">
              <form method="post" action="cart.php">
                <input type="hidden" name="product_id" value="<?php echo $produk['id_produk']; ?>" />
                <input type="hidden" name="product_name" value="<?php echo $produk['nama_produk']; ?>" />
                <div class="form-group">
                  <label for="qty">Quantity</label>
                  <input type="number" class="form-control bg-dark text-light" id="qty" name="product_qty" value="1" min="1" max="10" required />
                </div>
                <div class="form-group">
                  <label for="price">Price</label>
                  <input type="text" class="form-control" id="price" name="product_price" value="<?php echo $produk['harga']; ?>" readonly />
                </div>
                <div class="mt-3">
                  <button type="submit" name="submit" class="btn btn-primary"><i class="fas fa-shopping-cart"></i>Add to Cart</button>
                </div>
              </form>
            </div>
          </div>
        </div>
        <?php } ?>
      </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
  </body>
</html>