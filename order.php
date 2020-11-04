<?php
  session_start();
  if (!isset($_SESSION['usname']) || $_SESSION['usname'] == ''){
    header('Location: login.php');
  }
  if (!isset($_GET['id']) || $_GET['id'] == ''){
    header('Location: orders.php');
  }
  require_once('config.php');
  $pg = "select orders.orderdate, customer.cuname ";
  $pg .= "from orders inner join customer on orders.cuid = customer.cuid ";
  $pg .= "where orders.orid = " . $_GET['id'] . " ";
  if ($_SESSION['uslevel'] < 1) $pg .= "and orders.agid = " . $_SESSION['agid'];
  $pg .= ";";
  $result = pg_query($link, $pg);
 ?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Order - ATN</title>
    <link rel="stylesheet" href="css/styles.css">
  </head>
  <body>
    <nav class="navbar">
      <div class="nav-container">
        <div class="navbar-menu">
          <a href="index.php" class="navigation-link navigation-link-first">Dashboard</a>
          <a href="customers.php" class="navigation-link">Customer</a>
          <a href="products.php" class="navigation-link">Product</a>
          <a href="orders.php" class="navigation-link current-page">Existing Order</a>
          <a href="neworder.php" class="navigation-link">New Order</a>
        </div>
      </div>
    </nav>
    <main>
      <?php if (pg_num_rows($result) == 1): ?>
        <div class="s-order-container">
          <div class="s-order-info">
            <?php $row = pg_fetch_array($result); ?>
            <table class="s-order-info-tbl">
              <tbody>
                <tr>
                  <td class="s-order-tbl-lb">Order ID</td>
                  <td class="s-order-tbl-dt"><?php echo $_GET['id'] ?></td>
                  <td class="s-order-tbl-lb">Order Date</td>
                  <td class="s-order-tbl-dt"><?php echo $row['orderdate']; ?></td>
                </tr>
                <tr>
                  <td class="s-order-tbl-lb">Agency ID</td>
                  <td class="s-order-tbl-dt"><?php echo $_SESSION['agid']; ?></td>
                  <td class="s-order-tbl-lb">Customer name</td>
                  <td class="s-order-tbl-dt"><?php echo $row['cuname']; ?></td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="s-order-prolist">
            <?php
              $pg = "select product.proname, productorder.quantity, product.proprice, sum(productorder.quantity*product.proprice) as subtotal ";
              $pg .= "from product inner join productorder on product.proid = productorder.proid ";
              $pg .= "where productorder.orid = " . $_GET['id'] . " ";
              $pg .= "group by product.proid, product.proname, productorder.quantity ";
              $pg .= "order by product.proid;";
              $result = pg_query($link, $pg);
              if (pg_num_rows($result) > 0){
            ?>
              <table class="s-order-pro-tbl">
                <thead>
                  <th>Name</th>
                  <th>Quantity</th>
                  <th>Price</th>
                  <th>Subtotal</th>
                </thead>
                <tbody>
                  <?php while ($row = pg_fetch_array($result)) { ?>
                    <td><?php echo $row['proname']; ?></td>
                    <td style="text-align: center"><?php echo $row['quantity']; ?></td>
                    <td style="text-align: center"><?php echo $row['proprice']; ?></td>
                    <td style="text-align: center"><?php echo $row['subtotal'] ?></td>
                  <?php } ?>
                </tbody>
              </table>
            <?php } else { ?>
              There is no product.
            <?php
              }
            ?>
          </div>
          <div class="s-order-total">
            <h3>Total:
            <?php
              $pg = "select sum(productorder.quantity*product.proprice) as total ";
              $pg .= "from product inner join productorder on product.proid = productorder.proid ";
              $pg .= "where productorder.orid = " . $_GET['id'] . " ";
              $pg .= "group by productorder.orid;";
              $result = pg_query($link, $pg);
              if (pg_num_rows($result) == 1){
                $row = pg_fetch_array($result);
                echo $row['total'] . '</h3>';  } else { ?>
              n/a;
            <?php
              }
             ?>
          </div>
        </div>
      <?php else: ?>
        The order does not exist. <a href="orders.php">Go back?</a>
      <?php endif; ?>
    </main>
  </body>
</html>
