<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "EV_Charging";

// Create DB connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("❌ Connection failed: " . $conn->connect_error);
}

// Only handle POST requests
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $message = trim($_POST['message'] ?? '');

    // Simple validation
    if (empty($name) || empty($email) || empty($message)) {
        echo "<script>alert('❌ All fields are required.'); window.history.back();</script>";
        exit();
    }

    // Check if email exists in Userr table
    $stmt = $conn->prepare("SELECT UserID FROM Userr WHERE EmailAddress = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($userId);
    $stmt->fetch();
    $stmt->close();

    if (!$userId) {
        echo "<script>alert('❌ Email not found. Please log in or register first.'); window.history.back();</script>";
        exit();
    }

   
    $stmt = $conn->prepare("INSERT INTO ContactMessages (UserID, Name, Email, Message) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $userId, $name, $email, $message);

    if ($stmt->execute()) {
        echo "<script>alert('✅ Message sent successfully!'); window.location.href='contact.html';</script>";
    } else {
        echo "<script>alert('❌ Failed to send message: " . $stmt->error . "'); window.history.back();</script>";
    }

    $stmt->close();
    $conn->close();
}
?>
