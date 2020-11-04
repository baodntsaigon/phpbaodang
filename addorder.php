<?php
  if (!function_exists('get_customerid')){
    function get_customerlist($search_string, $field, $link){
      $pg = 'SELECT customer.cuid FROM customer';
      switch ($field) {
        case 'cuphone':
          $pg .= ' WHERE customer.cuphone=\'' . $search_string . '\';';
          break;
        case 'cuname':
          $pg .= ' WHERE customer.cuname=\'' . $search_string . '\';';
          break;
        default:
          $pg .= ';';
          break;
      }
      $result = pg_query($link, $pg);
      $num_rows = pg_num_rows($result);
      if (pg_num_rows($result) > 0){
        while ($row = pg_fetch_array($result)){
          $customer = array(
            'cuid' => $row['cuid']
          );
          $customerlist[] = $customer;
        }
      }
      else {
        $customer = array(
          'cuid' => ''
        );
        $customerlist[] = $customer;
      }
      return $customerlist;
    }
  }

  if (!function_exists('add_order')){
      function add_order($agid, $cuid, $orderdate, $link){
        if ((isset($_SESSION['cart'])) || (count($_SESSION['cart']) > 0)) {
          $pg = 'INSERT INTO orders(agid, cuid, orderdate) VALUES';
          $pg .= '(' . $agid . ',\'';
          $pg .= $cuid . '\',';
          $pg .= '\'' . $orderdate . '\');';
          $result = pg_query($link, $pg);

        }
        else {
          return FALSE;
        }
        return $result;
      }
  }

  if (!function_exists('get_max_orderid')){
    function get_max_orderid($agid, $link){
      $agid = ($agid ==''? '' : $agid);
      $pg = 'SELECT MAX(orders.orid) AS max FROM orders';
      if ($agid != ''){
        $pg .= ' WHERE orders.agid = ' . $agid . ';';
      }
      else {
        $pg .= ';';
      }
      $result = pg_query($link, $pg);
      if (pg_num_rows($result) == 1){
        $row = pg_fetch_array($result);
        $orid = $row['max'];
      }
      return $orid;
    }
  }

  if (!function_exists('add_orderdetails')){
    function add_orderdetails($orid, $link){
      $pg = "INSERT INTO productorder(orid, proid, quantity) VALUES";
      foreach ($_SESSION['cart'] as $key => $item){
         $pg .= '(' . $orid . ',';
         $pg .= $item['proid'] . ',';
         $pg .= $item['quantity'] . ')';
         if ($item == end($_SESSION['cart'])) {
            $pg .= ';';
         }
         else {
           $pg .= ',';
         }
      }
      $result = pg_query($link, $pg);
      if ($result == TRUE) { unset($_SESSION['cart']); }
      return $result;
    }
  }
?>
