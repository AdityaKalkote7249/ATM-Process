<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "atm_process";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$response = ['success' => false, 'message' => ''];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cardNumber = $_POST['card_number'] ?? '';
    $pinNumber = $_POST['pin_number'] ?? '';

    // Basic validation
    if (empty($cardNumber) || empty($pinNumber)) {
        $response['message'] = 'Card number and PIN are required.';
    } else {
        // Check if the user already has a PIN
        $stmt = $conn->prepare("SELECT pin_number FROM user_details WHERE card_number = ?");
        $stmt->bind_param("s", $cardNumber);
        $stmt->execute();
        $result = $stmt->get_result();
        $userExists = $result->num_rows > 0;
        $user = $userExists ? $result->fetch_assoc() : null;

        if ($userExists && !empty($user['pin_number'])) {
            // User already has a PIN
            $response['message'] = "You are updating your existing PIN.";
        }

        // Prepare SQL statement to update the PIN
        if ($updateStmt = $conn->prepare("UPDATE user_details SET pin_number = ? WHERE card_number = ?")) {
            $updateStmt->bind_param("si", $pinNumber, $cardNumber);
            $updateStmt->execute();

            if ($updateStmt->affected_rows > 0) {
                $response = ['success' => true, 'message' => 'PIN updated successfully.'];
            } else {
                $response['message'] .= ' No record found for the provided card number or no changes made.';
            }

            $updateStmt->close();
        } else {
            $response['message'] = "Error preparing statement: " . $conn->error;
        }
    }
    echo json_encode($response);
    exit;
}

$conn->close();
?>
