<?php
session_start();
header('Content-Type: application/json');

// Only proceed if there is an existing session
if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
    echo json_encode(['success' => false, 'message' => 'No active session found.']);
    exit;
}

// Generate a new OTP
$otp = rand(100000, 999999);
$_SESSION['otp'] = $otp;
$_SESSION['otp_expiry'] = time() + 300; // OTP expires in 5 minutes

// Here, you should add your logic to send the OTP (via SMS, email, etc.)
echo json_encode(['success' => true, 'message' => 'OTP has been resent.', 'otp' => $otp]);


?>