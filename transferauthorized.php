
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

$response = ['valid' => false, 'message' => ''];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cardNumber = $_POST['card_number'];
    $pinNumber = $_POST['pin_number'];
    $accountType = $_POST['acc_type'];

    // Assuming card_number and pin_number are stored as integers in the database
    $stmt = $conn->prepare("SELECT * FROM user_details WHERE card_number = ? AND pin_number = ? AND acc_type = ?");
    $stmt->bind_param("iis", $cardNumber, $pinNumber, $accountType);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $userData = $result->fetch_assoc();
        $_SESSION['authenticated'] = true;
        $_SESSION['card_number'] = $userData['card_number']; // or whatever the column name is

        $response['valid'] = true;
        $response['message'] = "Credentials valid.";
    } else {
        $response['message'] = "Invalid credentials.";
    }

    $stmt->close();
    $conn->close();
}

echo json_encode($response);
?>
