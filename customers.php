<?php
  session_start();
  if (!isset($_SESSION['usname']) || $_SESSION['usname'] == ''){
    header('Location: login.php');
  }
  require_once("config.php");
  $pg = 'SELECT * FROM customer;';
  $result = pg_query($link, $pg);
 ?>
 <!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="css/styles.css">
    <title>Customer - ATN</title>
  </head>
  <body>
    <nav class="navbar">
      <div class="nav-container">
        <div class="navbar-menu">
          <a href="index.php" class="navigation-link navigation-link-first">Dashboard</a>
          <a href="#" class="navigation-link current-page">Customer</a>
          <a href="products.php" class="navigation-link">Product</a>
          <a href="orders.php" class="navigation-link">Existing Order</a>
          <a href="neworder.php" class="navigation-link">New Order</a>
        </div>
      </div>
    </nav>
    <main>
      <?php if (pg_num_rows($result) > 0): ?>
        <div class="page-content">
          <table class="customer-table">
            <thead>
              <th class="customer-name">Name</th>
              <th class="customer-phone">Phone</th>
            </thead>
            <tbody>
              <?php while ($row = pg_fetch_array($result)) { ?>
                <tr>
                  <td><?php echo $row['cuname']; ?></td>
                  <td><?php echo $row['cuphone']; ?></td>
                </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
      <?php else: ?>
        There is no customer.
      <?php endif; ?>
    </main>
  </body>
</html>
