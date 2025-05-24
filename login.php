<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "EV_Charging";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo "<script>alert('Invalid request'); window.location.href='index.html';</script>";
    exit();
}

if (!isset($_POST['EmailAddress']) || !isset($_POST['Password'])) {
    echo "<script>alert('Missing form data'); window.location.href='index.html';</script>";
    exit();
}

$email = $_POST['EmailAddress'];
$passwordInput = $_POST['Password'];

$sql = "SELECT * FROM Userr WHERE EmailAddress = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();
    
    if (password_verify($passwordInput, $user['Password'])) {
        $_SESSION['UserName'] = $user['Name'];
        
     
        echo "<script>alert('✅Login successful!'); window.location.href='home.html';</script>";
        exit();
    } else {
      
        echo "<script>alert('❌Incorrect password'); window.location.href='index.html';</script>";
        exit();
    }
} else {
 
    echo "<script>alert('❌ No user found with that email'); window.location.href='index.html';</script>";
    exit();
}

$stmt->close();
$conn->close();
?>
