<?php

$servername = "localhost";
$username = "root";
$password = ""; 
$dbname = "EV_Charging";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("<script>alert('❌ Connection failed: " . $conn->connect_error . "');</script>");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $firstName = trim($_POST['FirstName'] ?? '');
    $lastName = trim($_POST['LastName'] ?? '');
    $phone = trim($_POST['PhoneName'] ?? '');
    $email = trim($_POST['EmailAddress'] ?? '');
    $password = $_POST['Password'] ?? '';
    $gender = $_POST['gender'] ?? '';

    // Check for empty required fields
    if (empty($firstName) || empty($lastName) || empty($phone) || empty($email) || empty($password)) {
        echo "<script>alert('⚠️ Please fill all required fields.'); window.history.back();</script>";
        exit();
    }

    // Validate phone number (digits only)
    if (!preg_match('/^\d+$/', $phone)) {
        echo "<script>alert('📵 Phone number must contain only digits.'); window.history.back();</script>";
        exit();
    }

    // Check if first name already exists
    $firstCheck = $conn->prepare("SELECT * FROM Userr WHERE FirstName = ?");
    $firstCheck->bind_param("s", $firstName);
    $firstCheck->execute();
    $firstResult = $firstCheck->get_result();

    if ($firstResult->num_rows > 0) {
        echo "<script>alert('⚠️ First name already exists.'); window.history.back();</script>";
        $firstCheck->close();
        $conn->close();
        exit();
    }
    $firstCheck->close();

    // Check if last name already exists
    $lastCheck = $conn->prepare("SELECT * FROM Userr WHERE LastName = ?");
    $lastCheck->bind_param("s", $lastName);
    $lastCheck->execute();
    $lastResult = $lastCheck->get_result();

    if ($lastResult->num_rows > 0) {
        echo "<script>alert('⚠️ Last name already exists.'); window.history.back();</script>";
        $lastCheck->close();
        $conn->close();
        exit();
    }
    $lastCheck->close();



    // Check if phone number already exists
    $phoneCheck = $conn->prepare("SELECT * FROM Userr WHERE PhoneNumber = ?");
    $phoneCheck->bind_param("s", $phone);
    $phoneCheck->execute();
    $phoneResult = $phoneCheck->get_result();

    if ($phoneResult->num_rows > 0) {
        echo "<script>alert('📞 Phone number is already in use.'); window.history.back();</script>";
        $phoneCheck->close();
        $conn->close();
        exit();
    }
    $phoneCheck->close();

      // Check if email already exists
    $emailCheck = $conn->prepare("SELECT * FROM Userr WHERE EmailAddress = ?");
    $emailCheck->bind_param("s", $email);
    $emailCheck->execute();
    $emailResult = $emailCheck->get_result();

    if ($emailResult->num_rows > 0) {
        echo "<script>alert('📧 Email is already registered.'); window.history.back();</script>";
        $emailCheck->close();
        $conn->close();
        exit();
    }
    $emailCheck->close();

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert new user
    $sql = "INSERT INTO Userr (FirstName, LastName, PhoneNumber, EmailAddress, Password, Gender)
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssss", $firstName, $lastName, $phone, $email, $hashedPassword, $gender);

    if ($stmt->execute()) {
        echo "<script>alert('✅ Registration successful!'); window.location.href = 'index.html';</script>";
    } else {
        echo "<script>alert('❌ Failed to register: " . $stmt->error . "'); window.history.back();</script>";
    }

    $stmt->close();
    $conn->close();
}
?>
