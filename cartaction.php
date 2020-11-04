<?php
session_start();
require_once('config.php');
if (isset($_GET['action'])){
  switch ($_GET['action']) {
    case 'add':
      if (isset($_GET['proid']) && $_GET['proid'] != ''){
        $isFound = FALSE;
        if (isset($_SESSION['cart'])){
          for ($i = 0; $i < count($_SESSION['cart']); ++$i){
            $item = $_SESSION['cart'][$i];
            if ($item['proid'] == $_GET['proid']){
              $_SESSION['cart'][$i]['quantity'] += $_GET['quantity'];
              $isFound = TRUE;
              break;
            }
          }
        }
        if (!isset($_SESSION['cart']) || !$isFound) {
          $pg = "select product.proid, product.proname, product.proprice ";
          $pg .= "from product where product.proid = '" . $_GET['proid'] . "';";
          $result = pg_query($link, $pg);
          if (pg_num_rows($result) == 1){
            $row = pg_fetch_array($result);
            $num_row = count($_SESSION['cart']);
            $item = array(
              'proid' => $_GET['proid'],
              'proname' => $row['proname'],
              'proprice' => $row['proprice'],
              'quantity' => $_GET['quantity']
            );
            $_SESSION['cart'][$num_row] = $item;
          }
          else {
              echo "The item does not exist";
          }
        }
      }
      break;
    case 'remove':
      if (isset($_GET['proid']) && $_GET['proid'] != ''){
        if (!empty($_SESSION['cart'])){
          foreach ($_SESSION['cart'] as $i => $item){
            if ($item['proid'] == $_GET['proid']){
              unset($_SESSION['cart'][$i]);
              break;
            }
          }
        }
      }
      break;
    case 'empty':
      unset($_SESSION['cart']);
      break;
    default:
      // code...
      break;
  }
}

if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0){
  $total = 0;
?>
<table class="n-order-pro-tbl">
  <thead>
    <th>Name</th>
    <th>Price</th>
    <th>Quantity</th>
    <th>Subtotal</th>
    <th>Action</th>
  </thead>
  <tbody>
    <?php
      foreach ($_SESSION['cart'] as $key => $value){
        $item = $_SESSION['cart'][$key];
        $subtotal = preg_replace("/([^0-9\\.])/i", "", $item['proprice']) * $item['quantity'];
        $total += $subtotal;
    ?>
        <tr>
          <td><?php echo $item['proname']; ?></td>
          <td><?php echo $item['proprice']; ?></td>
          <td><?php echo $item['quantity']; ?></td>
          <td><?php echo $subtotal; ?></td>
          <td> <a href="#" onclick="cartAction('remove', <?php echo $item['proid'] ?>)">Delete</a> </td>
        </tr>
     <?php
      }
      ?>
  </tbody>
</table>
<div class="n-order-total">
  <h3>Total: <?php echo $total; ?></h3>
</div>
<?php } ?>
