<?php
session_start();


if (!isset($_SESSION['authenticated']) || !$_SESSION['authenticated']) {
    header('Location: userauthorizedbalance.php'); 
    exit();
}

$css = 
"<style>
body {
    background: rgba(209, 78, 35, 0.747);
    margin: 0;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh; 
}

.balance {
    width: 400px;
    height: 200px;
    background-color: white;
    color: black;
    display: flex;
    flex-direction: column;
    justify-content: center; 
    align-items: center; 
    padding: 20px;
    border-radius: 10px;
}

.balance h1 {
    font-size: 20px;
    font-family: 'Dosis', sans-serif;
    font-weight: 500;
    margin: 10px 0; 
}

.amount {
    font-size: 40px; 
    margin: 0; 
}
button {
    margin: 5px 0;
      padding: 5px;
      width:80px;
      height: 30px;
      color: white;
      background-color: rgb(49, 48, 48);
      border-radius: 10px;
      border: none;
      font-size: 20px;
      font-family: 'Dosis',sans-serif;
      font-weight: 500;
      cursor: pointer;
      transition: transform 0.2s ease; 
      margin: 0 5px;
   }
   
   button:hover {
   background-color:black;
   cursor: pointer;
   }
   
   button:active {
   transform: scale(0.95); 
   }
</style>";
$servername = "localhost";
$username = "root";
$password = "";
$database = "atm_process";
$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$cardNumber = $_SESSION['card_number'];
$sql = $conn->prepare("SELECT balance FROM user_details WHERE card_number = ?");
$sql->bind_param("i", $cardNumber);

$sql->execute();
$result = $sql->get_result();
echo '<!DOCTYPE html><html><head>';
echo $css;
echo '</head><body>';
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $balance = $row["balance"];
  // ... [rest of your PHP code] ...

echo "<div class='balance'><h1>Available Balance:-</h1><div class='amount' id='balanceAmount'>\u{20B9}" . $balance  ."<div class='button'><button type='button' onclick='redirect()'>Ok/Exit</button>"."</div></div></div>";

echo "<script>
        var msg = new SpeechSynthesisUtterance();
        msg.text = 'Your available balance is ' + " . json_encode($balance) . ";
        window.speechSynthesis.speak(msg);

        function redirect() {
            var xhr = new XMLHttpRequest();
            xhr.open('GET', 'assets/php/destroy_session.php', true);
            xhr.onload = function () {
                if (xhr.status === 200) {
                    window.location.href = 'index.php';
                }
            };
            xhr.send();
        }
      </script>";  // Close the script tag properly

// ... [rest of your PHP code] ...
    }
$sql->close();
$conn->close();
?>