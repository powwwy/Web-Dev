<?php
// Connect to the DB server
$server = 'localhost';
$user = 'root';
$password = '';
$database = 'andromax';

$conn = mysqli_connect($server, $user, $password, $database);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
else{
    echo "";
}

?>
