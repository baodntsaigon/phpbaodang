<?php
  require_once('config.php');
  if (isset($_POST['login'])) {
    trim($_POST['usname']);
    if ($_POST['usname'] == '' || $_POST['uspassword'] == '') {
      echo '<div class="top-bar error">Username or Password is invalid.</div>';
    }
    else {
      $pg = "SELECT * FROM users WHERE usname = '" . $_POST['usname'] . "' AND uspassword = '" . $_POST['uspassword'] . "';";
      $result = pg_query($link, $pg);
      if (pg_num_rows($result) == 1) {
        $row = pg_fetch_array($result);
        session_start();
        $_SESSION['usname'] = $row['usname'];
        $_SESSION['uslevel'] = $row['uslevel'];
        $_SESSION['agid'] = $row['agid'];
        header('Location: index.php');
      }
      else {
        echo '<div class="top-bar error">Username or Password is invalid.</div>';
      }
    }
  }
 ?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="css/styles.css">
    <title>Login - ATN</title>
  </head>
  <body>
    <div class="login-container">
      <div class="login-title">
        LOGIN
      </div>
      <form class="login-form" action="login.php" method="post">
        <label for="usname">Username</label> <br>
        <input type="text" name="usname" value="" required> <br>
        <label for="uspassword">Password</label> <br>
        <input type="password" name="uspassword" value="" required> <br>
        <input type="submit" name="login" value="Login"> <br>
      </form>
      <div class="signup-ref">
        <a href="signup.php">Sign up</a>
      </div>
    </div>
  </body>
</html>
