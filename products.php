<?php
  session_start();
  if (!isset($_SESSION['usname']) || $_SESSION['usname'] == ''){
    header('Location: login.php');
  }
  require_once("config.php");
  $pg = "SELECT product.proid, product.proname, product.proprice ";
  $pg .= "FROM product ORDER BY product.proid ASC;";
  $result = pg_query($link, $pg);
 ?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Product - ATN</title>
    <link rel="stylesheet" href="css/styles.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script>
      function cartAction(act, itemid){
        var queryString = "";
        if (act != "") {
          switch (act) {
            case "add":
              queryString = 'action='+act+'&proid='+itemid+'&quantity='+$("#quantity-"+itemid).val();
              break;
            case "remove":
              queryString = 'action='+act+'&proid='+itemid;
              break;
            case "empty":
              queryString = 'action='+act;
              break;
            default:
          }
        }
        jQuery.ajax({
          url: "cartaction.php",
          data: queryString,
          type: "GET",
          success: function(data){
            $("#n-order-product").html(data);
          },
          error:function(){}
        });
      }
    </script>
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
      <div class="product-container">
        <div class="product-title">
          Products
        </div>
        <div class="product-list">
          <?php if (pg_num_rows($result) > 0): ?>
            <?php while ($row = pg_fetch_array($result)) { ?>
              <div class="product-item">
                  <form>
                    <a href="product.php?id=<?php echo $row['proid']; ?>"><div class="product-img">
                      <img src="images/product.png" alt="product-<?php echo $row['proid']; ?>" style="width:300px; height: 300px">
                    </div>
                    <div class="product-info">
                      Id: <?php echo $row['proid']; ?> <br>
                      Name: <?php echo $row['proname']; ?> <br>
                      Price: <?php echo $row['proprice']; ?> <br>
                    </div></a>
                    <div class="product-add">
                      <input type="number" id="quantity-<?php echo $row['proid']; ?>" min="1" max="9999" value="">
                      <input type="button" value="ADD" onclick="cartAction('add', <?php echo '\''.$row['proid'].'\''; ?>)">
                    </div>
                  </form>
              </div>
            <?php } ?>
          <?php else: ?>
            There is no product.
          <?php endif; ?>
        </div>
      </div>
    </main>
  </body>
</html>
