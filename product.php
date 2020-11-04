<?php
  session_start();
  if (!isset($_SESSION['usname']) || $_SESSION['usname'] == ''){
    header ('Location: login.php');
  }
  require_once('config.php');
  $pg = 'select * from product where product.proid = \'' . $_GET['id'] . '\';';
  $result = pg_query($link, $pg);
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Product - ATN</title>
    <link rel="stylesheet" href="css/styles.css">
  </head>
  <body>
    <nav class="navbar">
      <div class="nav-container">
        <div class="navbar-menu">
          <a href="index.php" class="navigation-link navigation-link-first">Dashboard</a>
          <a href="customers.php" class="navigation-link">Customer</a>
          <a href="#" class="navigation-link current-page">Product</a>
          <a href="orders.php" class="navigation-link">Existing Order</a>
          <a href="neworder.php" class="navigation-link">New Order</a>
        </div>
      </div>
    </nav>
    <main>
      <?php if (pg_num_rows($result) == 1) {
        $row = pg_fetch_array($result);?>
        <div class="s-product-container">
          <div class="s-product-img">
            <img src="images/product.png" alt="product-<?php echo $_GET['id']; ?>" style="width:300px; height: 300px">
          </div>
          <div class="s-product-info">
            <table>
              <tbody>
                <tr>
                  <td class="s-product-lb">Product ID</td>
                  <td><?php echo $row['proid']; ?></td>
                </tr>
                <tr>
                  <td class="s-product-lb">Name</td>
                  <td><?php echo $row['proname']; ?></td>
                </tr>
                <tr>
                  <td class="s-product-lb">Price</td>
                  <td><?php echo $row['proprice']; ?></td>
                </tr>
                <tr>
                  <td class="s-product-lb">Desciption</td>
                  <td><?php echo $row['category'] ?></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      <?php } else { ?>
        The item does not exist. <a href="products.php">Go back?</a>
      <?php } ?>
    </main>
  </body>
</html>
