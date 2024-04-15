<?php
session_start();
header('Content-Type: application/json');

$response = ['valid' => false, 'message' => ''];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $enteredOtp = $_POST['otp'] ?? '';

    if (isset($_SESSION['otp'], $_SESSION['otp_expiry']) && time() < $_SESSION['otp_expiry']) {
        if ($_SESSION['otp'] == $enteredOtp) {
            $response = ['valid' => true, 'message' => "OTP verified successfully."];
            // Clear the OTP from the session after successful verification
            unset($_SESSION['otp'], $_SESSION['otp_expiry']);
        } else {
            $response = ['valid' => false, 'message' => "Invalid OTP entered."];
        }
    } else {
        $response = ['valid' => false, 'message' => "OTP expired or not set."];
    }
} else {
    $response = ['valid' => false, 'message' => "Invalid request method."];
}

echo json_encode($response);
?>
