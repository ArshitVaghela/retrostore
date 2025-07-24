<?php
session_start();
$conn = new mysqli("localhost", "root", "", "retro_store");
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
$featured = $conn->query("SELECT * FROM products LIMIT 4");
$cart_count = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Retro Store</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <style>
    :root {
      --primary: #11b5cb;
      --secondary: #2574fc7b;
      --dark: #222;
      --light: #f0f2f5;
    }
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }
    body, html {
      font-family: 'Segoe UI', sans-serif;
      background: var(--light);
      color: #333;
    }
    .w3-sidebar {
      width: 230px;
      background-color: var(--dark);
      color: #fff;
      box-shadow: 2px 0 12px rgba(0, 0, 0, 0.4);
    }
    .w3-sidebar a {
      padding: 16px;
      text-decoration: none;
      display: block;
      color: #fff;
      transition: 0.4s;
    }
    .w3-sidebar a:hover {
      background: var(--primary);
      transform: translateX(8px);
    }
    .main {
      margin-left: 230px;
      padding: 25px;
    }
    header {
      background: linear-gradient(135deg, var(--primary), var(--secondary));
      color: #fff;
      padding: 60px 20px;
      text-align: center;
      border-radius: 18px;
      animation: fadeZoomIn 1.2s ease;
    }
    header h1 {
      font-size: 46px;
      margin-bottom: 8px;
    }
    .categories {
      display: flex;
      flex-wrap: wrap;
      gap: 20px;
      margin: 40px 0;
    }
    .categories div {
      flex: 1 1 calc(30% - 20px);
      background: #fff;
      padding: 30px;
      text-align: center;
      border-radius: 16px;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
      cursor: pointer;
      transition: 0.4s;
      opacity: 0;
      animation: slideUp 0.8s ease forwards;
    }
    .categories div:nth-child(1) { animation-delay: 0.2s; }
    .categories div:nth-child(2) { animation-delay: 0.4s; }
    .categories div:nth-child(3) { animation-delay: 0.6s; }
    .categories div:hover {
      transform: translateY(-12px) scale(1.04);
      background: linear-gradient(135deg, var(--primary), var(--secondary));
      color: #fff;
    }
    .categories div i {
      font-size: 48px;
      margin-bottom: 14px;
    }
    .featured-products h2 {
      font-size: 34px;
      margin-bottom: 25px;
      text-align: center;
    }
    .product-grid {
      display: flex;
      flex-wrap: wrap;
      gap: 20px;
    }
    .product {
      flex: 1 1 calc(25% - 20px);
      background: #fff;
      border-radius: 16px;
      text-align: center;
      padding: 20px;
      box-shadow: 0 10px 24px rgba(0, 0, 0, 0.08);
      transition: 0.4s;
      animation: fadeZoomIn 1.2s ease;
    }
    .product:hover {
      transform: scale(1.06) rotate(0.5deg);
      box-shadow: 0 14px 32px rgba(0, 0, 0, 0.18);
    }
    .product img {
      width: 100%;
      height: 180px;
      object-fit: cover;
      border-radius: 10px;
      margin-bottom: 14px;
    }
    .product button {
      padding: 10px 20px;
      border: none;
      background: linear-gradient(135deg, var(--primary), var(--secondary));
      border-radius: 8px;
      color: #fff;
      font-weight: bold;
      cursor: pointer;
      transition: 0.3s;
    }
    .product button:hover {
      background: linear-gradient(135deg, #d35400, #e67e22);
      animation: pulse 0.8s infinite alternate;
    }
    .info-box {
      padding: 25px;
      background: #fff;
      border-radius: 16px;
      margin-top: 40px;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
    }
    .info-box h4 {
      margin-top: 0;
      border-bottom: 1px solid #ccc;
      padding-bottom: 6px;
    }
    .info-box p a {
      color: var(--primary);
      text-decoration: none;
    }
    .info-box p a:hover {
      text-decoration: underline;
    }
    footer {
      margin-top: 40px;
      background: var(--dark);
      color: #ccc;
      text-align: center;
      padding: 20px;
      border-radius: 12px;
    }

    @keyframes fadeZoomIn {
      0% { opacity: 0; transform: scale(0.95) translateY(30px); }
      100% { opacity: 1; transform: scale(1) translateY(0); }
    }
    @keyframes slideUp {
      0% { opacity: 0; transform: translateY(60px); }
      100% { opacity: 1; transform: translateY(0); }
    }
    @keyframes pulse {
      0% { transform: scale(1); }
      100% { transform: scale(1.08); }
    }

    @media (max-width: 768px) {
      .w3-sidebar {
        width: 100%;
        position: fixed;
        height: auto;
        display: flex;
        flex-wrap: wrap;
        justify-content: space-around;
        padding: 10px;
        top: 0;
        z-index: 999;
      }
      .w3-sidebar a {
        padding: 12px;
        font-size: 14px;
        display: inline-block;
      }
      .main {
        margin: 160px 0 0 0;
        padding: 15px;
      }
      .categories div, .product {
        flex: 1 1 100%;
      }
      .product img {
        height: 220px;
      }
    }
  </style>
</head>
<body>

<!-- Sidebar -->
<div class="w3-sidebar w3-bar-block">
  <h2 class="w3-center" style="padding: 20px 0; font-weight: bold;">Retro Store</h2>
  <a href="index.php"><i class="fa fa-home"></i> Home</a>
  <a href="shirts.php"><i class="fas fa-tshirt"></i> Shirts</a>
  <a href="jeans.php"><i class="fas fa-tag"></i> Jeans</a>
  <a href="shoes.php"><i class="fas fa-shoe-prints"></i> Shoes</a>
  <a href="about.php"><i class="fa fa-info-circle"></i> About Us</a>
  <a href="contact.php"><i class="fa fa-envelope"></i> Contact Us</a>
  <a href="subscribe.php"><i class="fa fa-bell"></i> Subscribe</a>
</div>

<!-- Main Content -->
<div class="main">
  <header>
    <h1>Welcome to Retro Store</h1>
    <div style="margin-top: 15px;">
      <a href="view_cart.php" class="w3-button w3-blue w3-round">
        <i class="fa fa-shopping-cart"></i> View Cart (<?= $cart_count ?>)
      </a>
    </div>
    <p>Your one-stop shop for quality fashion essentials!</p>
  </header>

  <div class="categories">
    <div><i class="fas fa-tshirt"></i><h3>Shirts</h3><p>Casual & formal.</p></div>
    <div><i class="fas fa-tag"></i><h3>Jeans</h3><p>Trendy comfort wear.</p></div>
    <div><i class="fas fa-shoe-prints"></i><h3>Shoes</h3><p>Style your walk.</p></div>
  </div>

  <div class="featured-products">
    <h2>Featured Products</h2>
    <div class="product-grid">
      <?php while($row = $featured->fetch_assoc()): ?>
        <div class="product">
          <img src="<?= htmlspecialchars($row['image']) ?>" alt="<?= htmlspecialchars($row['name']) ?>">
          <h4><?= htmlspecialchars($row['name']) ?></h4>
          <p>₹<?= htmlspecialchars($row['price']) ?></p>
          <form method="POST" action="add_to_cart.php">
            <input type="hidden" name="id" value="<?= $row['id'] ?>">
            <input type="hidden" name="name" value="<?= htmlspecialchars($row['name']) ?>">
            <input type="hidden" name="price" value="<?= $row['price'] ?>">
            <input type="hidden" name="image" value="<?= htmlspecialchars($row['image']) ?>">
            <button type="submit"><i class="fa fa-shopping-cart"></i> Add to Cart</button>      
          </form>
        </div>
      <?php endwhile; ?>
    </div>
  </div>

  <div class="info-box">
    <p><strong><a href="about.php">About Us</a></strong></p>
    <div class="w3-margin-top">
      <h4>Contact Us</h4>
      <p><a href="contact.php">Contact Page</a></p>
      <p><i class="fa fa-phone"></i> +91 98765 43210</p>
      <p><i class="fa fa-envelope"></i> support@retrostore.com</p>
      <p><i class="fa fa-map-marker-alt"></i> Bhavnagar, Gujarat</p>
    </div>
    <p class="w3-margin-top">
      <a href="subscribe.php" class="w3-button w3-black w3-round">Subscribe</a>
    </p>
  </div>

  <footer>&copy; 2025 Retro Store. All Rights Reserved.</footer>
</div>
</body>
</html>
