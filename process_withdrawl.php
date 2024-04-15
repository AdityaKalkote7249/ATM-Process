<?php
session_start();
header('Content-Type: application/json');
require 'dompdf/autoload.inc.php';

use Dompdf\Dompdf;
use Dompdf\Options;

function calculateDenominations($amount, $user) {
    $denominations = [500, 200, 100];
    $result = [];

    foreach ($denominations as $value) {
        if ($amount >= $value) {
            $maxAvailableNotes = $user[$value . '_currency'];
            $requiredNotes = intdiv($amount, $value);

            for ($i = min($requiredNotes, $maxAvailableNotes); $i > 0; $i--) {
                $tempAmount = $amount - ($i * $value);
                $isPossible = true;
                
                foreach ($denominations as $checkValue) {
                    if ($checkValue < $value) {
                        $required = intdiv($tempAmount, $checkValue);
                        $available = $user[$checkValue . '_currency'];
                        if ($required > $available) {
                            $isPossible = false;
                            break;
                        }
                    }
                }

                if ($isPossible) {
                    $result[$value] = $i;
                    $amount = $tempAmount;
                    break;
                }
            }
            if ($amount == 0) break;
        }
    }

    return $amount > 0 ? null : $result;
}

function sendJsonResponse($data) {
    echo json_encode($data);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['user'])) {
    $conn = new mysqli("localhost", "root", "", "atm_process");

    if ($conn->connect_error) {
        sendJsonResponse(["error" => true, "message" => "Database connection failed: " . $conn->connect_error]);
    }

    $amount = isset($_POST['amount']) ? (int)$_POST['amount'] : 0;
    $user = $_SESSION['user'];

    if ($amount <= 0) {
        sendJsonResponse(["error" => true, "message" => "Invalid amount. Amount must be greater than 0."]);
    }

    if ($user['balance'] < $amount) {
        sendJsonResponse(["error" => true, "message" => "Insufficient funds."]);
    }

    $denominationsUsed = calculateDenominations($amount, $user);
    if ($denominationsUsed === null) {
        sendJsonResponse(["error" => true, "message" => "Insufficient denominations available."]);
    }

    
    $newBalance = $user['balance'] - $amount;
    foreach ($denominationsUsed as $denomination => $count) {
        $user[$denomination . '_currency'] -= $count;
    }

 

$conn->begin_transaction();

$stmt = $conn->prepare("UPDATE user_details SET balance = ?, 100_currency = ?, 200_currency = ?, 500_currency = ? WHERE card_number = ?");
if ($stmt === false) {
    $conn->rollback();
    sendJsonResponse(["error" => true, "message" => "Failed to prepare database query for updating user details."]);
}

$stmt->bind_param("iiiis", $newBalance, $user['100_currency'], $user['200_currency'], $user['500_currency'], $user['card_number']);
if (!$stmt->execute()) {
    $conn->rollback();
    sendJsonResponse(["error" => true, "message" => "Database error: " . $stmt->error]);
}
$stmt->close();

// Insert transaction record
$stmt = $conn->prepare("INSERT INTO transaction_record (card_number, amount, acc_type) VALUES (?, ?, ?)");
if ($stmt === false) {
    $conn->rollback();
    sendJsonResponse(["error" => true, "message" => "Failed to prepare database query for inserting transaction."]);
}

$accType = $_SESSION['user']['acc_type'];
$stmt->bind_param("ids", $user['card_number'], $amount, $accType);

if (!$stmt->execute()) {
    $conn->rollback();
    sendJsonResponse(["error" => true, "message" => "Failed to record transaction"]);
}

$stmt->close();

// Commit transaction
if (!$conn->commit()) {
    sendJsonResponse(["error" => true, "message" => "Transaction failed: " . $conn->error]);
}

// Update session
$_SESSION['user']['balance'] = $newBalance;
$_SESSION['DateTime'] = date("Y-m-d H:i:s");

// Send response
$response = [
    "success" => true,
    "message" => "Transaction successful.",
    "new_balance" => $newBalance,
    "acc_type" => $_SESSION['user']['acc_type'],
    "card_number" => substr($_SESSION['user']['card_number'], -4),
    "DateTime" => $_SESSION['DateTime']
];

sendJsonResponse($response);

// ... Rest of your code ...


    $conn->close();
    // Generate the PDF (if needed) and prepare the response
    if (isset($_POST['printReceipt']) && $_POST['printReceipt'] == 'true') {
        $options = new Options();
        $options->set('isRemoteEnabled', TRUE);
        $dompdf = new Dompdf($options);
        
        // Generate PDF content
        $html = 'Your HTML content for the receipt here';
        $dompdf->loadHtml($html);

        // Render the PDF
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        // Output the generated PDF (Direct download)
        $dompdf->stream("receipt.pdf", array("Attachment" => true));
    }

    // ... Rest of your PHP code ...

} else {
    // Unauthorized access
    sendJsonResponse(["error" => true, "message" => "Unauthorized access."]);
}
?>