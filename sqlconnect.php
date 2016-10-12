<?php
$servername = "localhost";
$username = "root";
$password = "";

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
echo "Connected successfully";

$result = $conn->query("SELECT * FROM EMailboxSystem.MEMBER");
if ($result->num_rows > 0) {
    // output data of each row
    echo $result->fetch_assoc()['ACCID'];
} else {
    echo "0 results";
}
$conn->close();

session_start();
$_SESSION['name']="aaaaa";
echo $_SESSION['name'];


echo json_encode(array('valid'=> true));

?>

