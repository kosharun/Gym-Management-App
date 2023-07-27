<?php 

require_once 'config.php';

$username = $_POST['username'];
$password = $_POST['password'];

$hashed_password = password_hash($password, PASSWORD_DEFAULT);

$sql = "INSERT INTO admins (username, password) values ( ?, ?)";
 
$run = $conn->prepare($sql);
$run->bind_param("ss", $username, $hashed_password);
$run->execute();

header("location: index.php");
exit();
?>

