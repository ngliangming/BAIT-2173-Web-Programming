<?php

define('DBHOST', 'localhost');
define('DBUSER', 'root');
define('DBPASS', '');
define('DB_NAME', 'assignment');

$dbc = @mysqli_connect(DBHOST, DBUSER, DBPASS, DB_NAME)
        OR die('Could not connect to database' . mysqli_connect_error());

if ($dbc->connect_error) {
    die('Connect failed :' . $dbc->connect_error);
} else {
    
}
mysqli_set_charset($dbc, 'utf8');
?>

