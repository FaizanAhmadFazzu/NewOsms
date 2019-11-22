<?php
/* Database Credentials. Assuming you are running MySQL server with default setting (user "root" with no password) */

define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'newosms');

// Attempt to connect
$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Checking Connection
if($conn->connect_error){
    die("Connection Failed" . $conn->connect_error);
}

//  echo "Connected Seccessfully";

?>
