<?php
session_start();
require 'dompdf/autoload.inc.php';

// Database connection and transaction logic here
// ...
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'atm_process';

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$response = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Your existing transaction logic here...
    if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
        $response['status'] = 'error';
        $response['message'] = 'User not authenticated';
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }

    $senderCardNumber = $_SESSION['card_number'];
    $recipientAcc = $_POST['acc'];
    $amount = floatval($_POST['amount']);
    $accType = 'savings'; // Example account type, replace with actual value

    // Check sender's current balance
    $balanceCheckQuery = "SELECT balance FROM user_details WHERE card_number = '$senderCardNumber'";
    $balanceResult = $conn->query($balanceCheckQuery);
    if ($balanceResult->num_rows > 0) {
        $row = $balanceResult->fetch_assoc();
        if ($row['balance'] < $amount) {
            $response['status'] = 'error';
            $response['message'] = 'Insufficient balance';
            header('Content-Type: application/json');
            echo json_encode($response);
            exit;
        }
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Account not found';
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }

    $conn->begin_transaction();

    try {
        $updateSenderQuery = "UPDATE user_details SET balance = balance - $amount WHERE card_number = '$senderCardNumber'";
        $conn->query($updateSenderQuery);
        if ($conn->affected_rows == 0) {
            throw new Exception("Failed to deduct amount from sender's account");
        }

        $updateRecipientQuery = "UPDATE transfer_record SET balance = balance + $amount WHERE acc_number = '$recipientAcc'";
        $conn->query($updateRecipientQuery);
        if ($conn->affected_rows == 0) {
            throw new Exception("Account number is not correct or its not exist");
        }

        $timestamp = date('Y-m-d H:i:s');
        $insertRecordQuery = "INSERT INTO transaction_record (card_number, acc_type,  acc_number, mode,timestamp) VALUES ('$senderCardNumber', '$recipientAcc', $amount, 'transfer', '$accType', '$timestamp')";
        $conn->query($insertRecordQuery);
        if ($conn->affected_rows == 0) {
            throw new Exception("Failed to record the transaction");
        }

        $conn->commit();
        $response['status'] = 'success';
        $response['message'] = 'Transfer successful';
    
    } catch (Exception $e) {
        $conn->rollback();
        $response['status'] = 'error';
        $response['message'] = ' ' . $e->getMessage();
    }
}
    // After successful transaction:
 // After successful transaction:
if ($response['status'] === 'success' && isset($_POST['printReceipt']) && $_POST['printReceipt'] == 'on') {
    $receiptData = [
        'senderCardNumber' => $senderCardNumber,
        'recipientAcc' => $recipientAcc,
        'amount' => $amount,
        
    ];
    $encodedReceiptData = urlencode(json_encode($receiptData));
    $response['receiptUrl'] = "transfer_receipt.php?userData=$encodedReceiptData";
}

header('Content-Type: application/json');
echo json_encode($response);

    
   
$conn->close();
?>

