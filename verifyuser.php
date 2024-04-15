<?php
session_start();
header('Content-Type: application/json');

$servername = "localhost";
$username = "root";
$password = "";
$database = "atm_process";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die(json_encode(['valid' => false, 'message' => "Connection failed: " . $conn->connect_error]));
}

$response = ['valid' => false, 'message' => ''];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cardNumber = $_POST['mobile_number'] ?? '';
    $lastFourDigitsOfAadhaar = $_POST['adhaar'] ?? '';

    if ($stmt = $conn->prepare("SELECT * FROM user_details WHERE mobile_number = ? AND RIGHT(adhaar, 4) = ?")) {
        $stmt->bind_param("is", $cardNumber, $lastFourDigitsOfAadhaar);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $userData = $result->fetch_assoc();
            $_SESSION['authenticated'] = true;
            $_SESSION['mobile_number'] = $userData['mobile_number'];
            $_SESSION['adhaar'] = $userData['adhaar']; // Full Aadhaar number

            // Generate OTP
            $otp = rand(100000, 999999);
            $_SESSION['otp'] = $otp;
            $_SESSION['otp_expiry'] = time() + 300; // OTP expires in 5 minutes

            $response = ['valid' => true, 'message' => "Credentials valid.", 'otp' => $otp];
        } else {
            $response = ['valid' => false, 'message' => "Invalid credentials."];
        }
        $stmt->close();
    } else {
        $response = ['valid' => false, 'message' => "Error preparing statement."];
    }
} else {
    $response = ['valid' => false, 'message' => "Invalid request method."];
}

$conn->close();
echo json_encode($response);
?>
