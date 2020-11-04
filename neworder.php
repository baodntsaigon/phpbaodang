<?php
  session_start();
  if (!isset($_SESSION['usname']) || $_SESSION['usname'] == ''){
    header('Location: login.php');
  }
  require_once("config.php");
  require_once("addorder.php");
 ?>

 <?php
   if (isset($_POST['btn-save'])){
     $cuid = '';
     $customer = get_customerlist($_POST['cuname'], 'cuname', $link);
     foreach ($customer as $c){
       $cuid = $c['cuid'];
       break;
     }

     if ($cuid != ''){
         $result = add_order($_SESSION['agid'], $cuid, $_POST['orderdate'], $link);
         if ($result == TRUE){
           $orid = get_max_orderid($_SESSION['agid'], $link);
           $result = add_orderdetails($orid, $link);
           if ($result == TRUE){
             echo '<div class="top-bar successful">Successful added new order</div>';
           }
           else {
             echo '<div class="top-bar error">Failed to add products into new order</div>';
           }
         }
         else {
           echo '<div class="top-bar error">Failed to add new order</div>';
         }
     }
     else {
       echo '<div class="top-bar error">The customer does not exist</div>';
     }
   }
 ?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>New Order - ATN</title>
    <link rel="stylesheet" href="css/styles.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script>
      function cartAction(act, itemid){
        var queryString = "";
        if (act != "") {
          switch (act) {
            case "add":
              queryString = 'action='+act+'&proid='+itemid+'&quantity='+$("quantity-"+itemid).val();
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
          <a href="products.php" class="navigation-link">Product</a>
          <a href="orders.php" class="navigation-link">Existing Order</a>
          <a href="#" class="navigation-link current-page">New Order</a>
        </div>
      </div>
    </nav>
    <main>
      <div class="n-order-container">
        <form class="" action="neworder.php" method="post">
          <div class="n-order-info">
            <table class="n-order-info-tbl">
              <tbody>
                <tr>
                  <td class="n-order-tbl-lb">Order ID</td>
                  <td class="n-order-tbl-dt"> <input type="text" name="" value="" disabled> </td>
                  <td class="n-order-tbl-lb">Order Date</td>
                  <td class="n-order-tbl-dt">  <input type="text" name="orderdate" value="<?php echo date('Y-m-d'); ?>" readonly> </td>
                </tr>
                <tr>
                  <td class="n-order-tbl-lb">Agency ID</td>
                  <td class="n-order-tbl-dt"> <input type="text" name="agid" value="<?php echo $_SESSION['agid']; ?>" readonly> </td>
                  <td class="n-order-tbl-lb">Customer name</td>
                  <td class="n-order-tbl-dt"> <input type="text" name="cuname" value=""> </td>
                </tr>
              </tbody>
            </table>
          </div>
          <div id="n-order-product"></div>
          <script>
            $(document).ready(function () {
              cartAction('','');
            })
          </script>
          <div class="n-order-button">
            <input type="submit" name="btn-save" value="Save">
            <input type="button" name="" value="Cancel" onclick="cartAction('empty', 0)">
          </div>
        </form>
      </div>
    </main>
  </body>
</html>
