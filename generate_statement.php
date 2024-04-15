<?php
require 'dompdf/autoload.inc.php';

use Dompdf\Dompdf;

$servername = "localhost";
$username = "root";
$password = "";
$database = "atm_process";


$conn = new mysqli($servername, $username, $password, $database);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$query = "SELECT card_number, acc_type, amount, time_stamp FROM transaction_record";
$result = $conn->query($query);

$html = '<style>table { width: 100%; border-collapse: collapse; } th, td { border: 1px solid black; padding: 8px; text-align: left; }</style>';
$html .= '<table><tr><th>Time Stamp</th><th>Card Number</th><th>Account Type</th><th>Amount</th></tr>';

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $html .= "<tr>
                    <td>" . $row['time_stamp'] . "</td>
                    <td>" . $row['card_number'] . "</td>
                    <td>" . $row['acc_type'] . "</td>
                    <td>" . $row['amount'] . "</td>
                  </tr>";
    }
} else {
    $html .= '<tr><td colspan="4">No data found.</td></tr>';
}

$html .= '</table>';

$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("statement.pdf", array("Attachment" => true));

$conn->close();
?>
