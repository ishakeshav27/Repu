<?php
/**Basic connection of mysql to the page accessing the table
   We can also use <? include db.php ?>  **/
$server = 'localhost';
$user = 'root';
$password = '';
$db = 'test';

$conn = mysqli_connect($server, $user, $password, $db); // or die("Unable to connect")

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully";


?>