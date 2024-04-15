<?php
require 'dompdf/autoload.inc.php';

use Dompdf\Dompdf;
use Dompdf\Options;
$servername = "localhost";
$username = "root";
$password = "";
$database = "atm_process";


$conn = new mysqli($servername, $username, $password, $database);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$css = "
<style>
body {
    background-color: rgba(209,78,35,0.747);
}
table {
    width: 60%;
    margin: auto;
    background-color: white;
    border-radius:10px;
}
th, td {
    border: 1px solid rgb(182, 176, 168);
    border-radius:5px;
    padding: 8px;
    text-align: left;
}
h1 { 
    font-family: 'Dosis', sans-serif;
    font-size: 60px;
    font-weight: 500;
    color: white;
}
p {
    font-family: 'Dosis', sans-serif;
    font-size: 40px;
    font-weight: 500;
    color: white;
}
.button-container {
    display: flex;
    justify-content: center;
    margin-top: 20px;
}
button {
    background-color: #4b1d3f;
    color: white;
    font-size: 20px;
    font-weight: 500;
    font-family: 'Dosis', sans-serif;
    border-radius: 5px;
    border: 1px solid white;
    cursor: pointer;
    width: 200px;
    height: 30px;
    margin: 5px;
}
/* Print specific styles */
@media print {
    body * {
        visibility: hidden;
    }
    table, table * {
        visibility: visible;
    }
    table {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
    }
    
}
</style>";


echo $css;


$query = "SELECT card_number, acc_type, amount, time_stamp FROM transaction_record";
$result = $conn->query($query);

if ($result->num_rows > 0) {
 
    echo "<table>
            <tr>
            <th>Time Stamp</th>
                <th>Card Number</th>
                <th>Account Type</th>
                <th>Amount</th>
               
            </tr>";

   
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
        <td>" . htmlspecialchars($row['time_stamp']) . "</td>
                <td>" . htmlspecialchars($row['card_number']) . "</td>
                <td>" . htmlspecialchars($row['acc_type']) . "</td>
                <td>" . htmlspecialchars($row['amount']) . "</td>
              </tr>";
    }


    echo "</table>";
} else {
    echo "No data found.";
}


echo"<div class='button-container'>
 <button onclick=\"window.open('generate_statement.php', '_blank')\">Print statement</button>

 <button onclick=\"window.open('menu.php', '_blank')\">Exit/Ok</button>

</div>";





echo "<script>
function otherAction() {
    alert('Other action performed!');
}
</script>";


$conn->close();
?>
