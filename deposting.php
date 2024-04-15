<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$database = "atm_process";


$conn = new mysqli($servername, $username, $password, $database);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$response = ['success' => false, 'message' => ''];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $deposite = $_POST['deposite'] ?? '';
    $cardNumber = $_SESSION['card_number'] ?? ''; 

    if (empty($deposite)) {
        $response['message'] = 'Deposit amount is required.';
    } elseif (empty($cardNumber)) {
        $response['message'] = 'Card number is not available.';
    } else {
  
        if ($stmt = $conn->prepare("UPDATE user_details SET balance = balance + ? WHERE card_number = ?")) {
            $stmt->bind_param("is", $deposite, $cardNumber);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                $response = ['success' => true, 'message' => 'Deposited successfully.'];
            } else {
                $response['message'] = 'No record found for the provided card number.';
            }

            $stmt->close();
        } else {
            $response['message'] = "Error preparing statement: " . $conn->error;
        }
    }
    echo json_encode($response);
    exit;
}

$conn->close();
?>
