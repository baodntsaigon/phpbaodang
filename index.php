<?php
  session_start();
  if (isset($_POST['logout'])){
    session_unset();
    session_destroy();
  }
  if (!isset($_SESSION['usname']) || $_SESSION['usname'] == ''){
    header('Location: login.php');
  }
  require_once("config.php");
  $month = array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec');
  for ($i = 1; $i <= 12; ++$i){
    $revenue[$i] = '$0.00';
    $order[$i] = 0;
  }
  $thisyear = date('Y');
  $pg = 'select extract(month from orders.orderdate) as month, ';
  $pg .= 'sum(productorder.quantity * product.proprice) as revenue, ';
  $pg .= 'count(distinct orders.orid) as soldorder ';
  $pg .= 'from orders inner join productorder on orders.orid = productorder.orid ';
  $pg .= 'inner join product on productorder.proid = product.proid ';
  $pg .= 'where extract(year from orders.orderdate) = ' . $thisyear . ' ';
  if ($_SESSION['uslevel'] < 1) $pg .= 'and orders.agid = ' . $_SESSION['agid'] . ' ';
  $pg .= 'group by extract(year from orders.orderdate), extract(month from orders.orderdate) ';
  $pg .= 'order by extract(month from orders.orderdate);';
  $result = pg_query($link, $pg);
  if (pg_num_rows($result) > 0) {
    while ($row = pg_fetch_array($result)){
      $revenue[$row['month']] = $row['revenue'];
      $order[$row['month']] = $row['soldorder'];
    }
  }
 ?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="css/styles.css">
    <title>Dashboard - ATN</title>
  </head>
  <body>
    <nav class="navbar">
      <div class="nav-container">
        <div class="navbar-menu">
          <a href="#" class="navigation-link navigation-link-first current-page">Dashboard</a>
          <a href="customers.php" class="navigation-link">Customer</a>
          <a href="products.php" class="navigation-link">Product</a>
          <a href="orders.php" class="navigation-link">Existing Order</a>
          <a href="neworder.php" class="navigation-link">New Order</a>
        </div>
      </div>
    </nav>
    <main>
      <div class="dashboard-container">
        <div class="dashboard-item revenue-table">
          <div class="item-title">
            REVENUE
          </div>
          <table class='dashboard-table'>
            <tbody>
              <?php for ($i = 1; $i <= 12; ++$i){ ?>
                <tr <?php if ($i == date('m')) echo 'style="background: #faa28c"'; ?>>
                  <td><?php echo $month[$i-1]; ?></td>
                  <td><?php echo $revenue[$i]; ?></td>
                </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
        <div class="dashboard-item order-table">
          <div class="item-title">
            ORDER
          </div>
          <table class='dashboard-table'>
            <tbody>
          <?php for ($i = 1; $i <= 12; ++$i){ ?>
            <tr <?php if ($i == date('m')) echo 'style="background: #faa28c"'; ?>>
              <td><?php echo $month[$i-1]; ?></td>
              <td><?php echo $order[$i]; ?></td>
            </tr>
          <?php } ?>
        </tbody>
      </table>
        </div>
        <div class="dashboard-item info-table">
          <?php
          $pg = 'SELECT agency.agaddress, agency.agphone FROM agency WHERE agency.agid = ' . $_SESSION['agid'] . ';';
          $result = pg_query($link, $pg);
          if (pg_num_rows($result) == 1){
            $row = pg_fetch_array($result);
          ?>
            <table class="tb-info">
              <tbody>
                <tr>
                  <td colspan = "2"><?php echo $_SESSION['usname'] ?></td>
                </tr>
                <tr style="text-align:left">
                  <td>Address</td>
                  <td><?php echo $row['agaddress'] ?></td>
                </tr>
                <tr style="text-align:left">
                  <td>Phone</td>
                  <td><?php echo $row['agphone']?></td>
                </tr>
                <tr>
                  <td colspan="2"> <form class="" action="index.php" method="post">
                    <input type="submit" class="btn-logout" name="logout" value="Logout">
                  </form> </td>
                </tr>
            </table>
        <?php
           }?>
        </div>
      </div>
    </main>
  </body>
</html>