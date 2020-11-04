<?php
define('DB_SERVER', 'ec2-54-197-238-238.compute-1.amazonaws.com');
define('DB_USERNAME', 'vgacgbrrezqfub');
define('DB_PASSWORD', 'e26cab3ab83c3f4a7d77a33d7f4737925e9fcf3500c2fd12c1db9fb95d283d0a');
define('DB_NAME', 'dd8dpehj4ocbk5');

/* Attempt to connect to PostgreSQL database */
$link = pg_connect("host=".DB_SERVER." dbname=". DB_NAME ." user=" . DB_USERNAME . " password=" .DB_PASSWORD. "")
		or die('Could not connect1: ' . pg_last_error());
?>
