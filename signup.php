<?php
  require_once('config.php');
  if (isset($_POST['signup'])){
    trim($_POST['usname']);
    trim($_POST['agaddress']);
    trim($_POST['agphone']);
    if ($_POST['usname'] == '' || $_POST['agaddress'] == '' || $_POST['agphone'] == ''){
      echo '<div class="top-bar error">Registered information is invalid</div>';
    }
    else {
      $pg = "SELECT * FROM users INNER JOIN agency ON users.agid = agency.agid";
      $pg .= " WHERE users.usname = '" . $_POST['usname'] . "' OR agency.agadress = '" . $_POST['agaddress'] . "' OR agency.agphone = '" . $_POST['agphone'] ."';";
      $result = pg_query($link, $pg);
      if (pg_num_rows($result) > 0) {
         echo '<div class="top-bar error">Signup failed. Username or address or phone is invalid.</div>';
      }
      else{
        $pg = "INSERT INTO agency(agaddress, agphone) VALUES";
        $pg .= "('" . $_POST['agaddress'] . "','" . $_POST['agphone'] . "');";
        $result = pg_query($link, $pg);
        if ($result == TRUE){
          $pg = "SELECT agency.agid FROM agency";
          $pg .= " WHERE agency.agaddress='" . $_POST['agaddress'] . "' AND agency.agphone='" . $_POST['agphone'] . "';";
          $result = pg_query($link, $pg);
          if (pg_num_rows($result) == 1) {
            $row = pg_fetch_array($result);
            $agid = $row['agid'];

            $pg = "INSERT INTO users(usname, uspassword, uslevel, agid) VALUES";
            $pg .= "('" . $_POST['usname'] . "','" . $_POST['uspassword'] . "',0," . $agid . ");";
            $result = pg_query($link, $pg);
            if ($result == TRUE){
              session_start();
              $_SESSION['usname'] = $_POST['usname'];
              $_SESSION['uslevel'] = $_POST['uslevel'];
              $_SESSION['agid'] = $_POST['agid'];
              header('Location: index.php');
            }
            else {
               echo '<div class="top-bar error">Something wrong. Failed to signup.</div>';
            }
          }
          else {
             echo '<div class="top-bar error">Something wrong. Failed to signup.</div>';
          }
        }
        else {
           echo '<div class="top-bar error">Something wrong. Failed to signup.</div>';
        }
      }
    }
  }
 ?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="css/styles.css">
    <title></title>
  </head>
  <body>
    <div class="signup-container">
      <div class="signup-title">
        SIGN UP
      </div>
      <form class="signup-form" action="signup.php" method="post">
        <label for="usname">Username</label> <br>
        <input type="text" name="usname" value="" required> <br>
        <label for="uspassword">Password</label> <br>
        <input type="password" name="uspassword" value="" required> <br>
        <label for="agaddress">Address</label> <br>
        <input type="text" name="agaddress" value="" required> <br>
        <label for="agphone">Phone</label> <br>
        <input type="text" name="agphone" value="" required> <br>
        <input type="submit" name="signup" value="Signup"> <br>
      </form>
      <div class="login-ref">
        <a href="login.php">Log in</a>
      </div>
    </div>
  </body>
</html>
