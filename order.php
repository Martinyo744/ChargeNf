<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "EV_Charging";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("❌ Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'] ?? '';
    $level = $_POST['level'] ?? '';
    $address = $_POST['address'] ?? '';

    
    $stmt = $conn->prepare("SELECT UserID FROM Userr WHERE EmailAddress = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($userId);
    $stmt->fetch();
    $stmt->close();

    if (!$userId) {
        echo "<script>alert('❌ User not found. Please register first.'); window.history.back();</script>";
        exit();
    }

   
    $stmt = $conn->prepare("INSERT INTO ChargingRequests (Email, ChargingLevel, Addresslink) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $userId, $level, $address);

    if ($stmt->execute()) {
        echo "<script>alert('✅ Truck requested successfully!'); window.location.href='home.html';</script>";
    } else {
        echo "<script>alert('❌ Error placing order: " . $stmt->error . "'); window.history.back();</script>";
    }

    $stmt->close();
    $conn->close();
}
?>
