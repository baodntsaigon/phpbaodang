<?php
  session_start();
  if (!isset($_SESSION['usname']) || $_SESSION['usname'] == ''){
    header('Location: login.php');
  }
  require_once("config.php");
  $pg = "select orders.orid, customer.cuname, orders.orderdate ";
  $pg .= "from orders inner join customer on orders.cuid = customer.cuid ";
  if ($_SESSION['uslevel'] < 1) $pg .= " WHERE orders.agid = " . $_SESSION['agid'] . " ";
  $pg .= "group by orders.orid, customer.cuname ORDER BY orders.orderdate DESC, orders.orid DESC;";
  $result = pg_query($link, $pg);
 ?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="css/styles.css">
    <title>Order - ATN</title>
  </head>
  <body>
    <nav class="navbar">
      <div class="nav-container">
        <div class="navbar-menu">
          <a href="index.php" class="navigation-link navigation-link-first">Dashboard</a>
          <a href="customers.php" class="navigation-link">Customer</a>
          <a href="products.php" class="navigation-link">Product</a>
          <a href="#" class="navigation-link current-page">Existing Order</a>
          <a href="neworder.php" class="navigation-link">New Order</a>
        </div>
      </div>
    </nav>
    <main>
      <?php if (pg_num_rows($result) > 0): ?>
        <div class="page-content">
          <table class="order-tbl">
            <thead>
              <th class="order-id">Order ID</th>
              <th class="order-cus">Customer Name</th>
              <th class="order-date">Date</th>
              <th class="order-detail">Detail</th>
            </thead>
            <tbody>
              <?php while ($row=pg_fetch_array($result)) { ?>
                  <tr>
                    <td style="text-align: center;"><?php echo $row['orid']; ?></td>
                    <td><?php echo $row['cuname']; ?></td>
                    <td style="text-align: center;"><?php echo $row['orderdate']; ?></td>
                    <td style="text-align: center;"> <a href="order.php?id=<?php echo $row['orid']; ?>">Show</a> </td>
                  </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
      <?php else: ?>
        There is no order.
      <?php endif; ?>
    </main>
  </body>
</html>
