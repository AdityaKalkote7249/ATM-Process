<?php
require 'dompdf/autoload.inc.php';

use Dompdf\Dompdf;
use Dompdf\Options;

if (isset($_GET['userData'])) {
    $encodedUserData = $_GET['userData'];
    $userData = json_decode(urldecode($encodedUserData), true);

 
    $currentTime = date("Y-m-d H:i:s");
    $userData['DateTime'] = $currentTime;


    if (isset($userData['acc_number']) && strlen($userData['acc_number']) > 4) {
        $userData['acc_number'] = str_repeat('*', strlen($userData['acc_number']) - 4) . substr($userData['acc_number'], -4);
    }

 
    $css = "
    <style>
    table {
        border-collapse: collapse;
        width: 60%; /* Adjust width as per your requirement */
        margin: auto; /* Center the table */
        background-color: white; /* Optional: for better readability */
    }
  
    table, th, td {
        border: 1px solid black;
    }
    th, td {
        padding: 8px;
        text-align: left;
    }
    body {
        background-color: rgba(209, 78, 35, 0.747);
    }
    h1{ 
        font-family:'Dosis',sans-serif;
        font-size: 60px;
        font-weight: 500;
        color:white;
    }
    p{
        font-family:'Dosis',sans-serif;
        font-size:40px;
        font-weight:500;
        color:white;
    }
    </style>";

  
    $html = '<html><head>' . $css . '<meta charset="UTF-8">'.
    '<meta name="viewport" content="width=device-width, initial-scale=1.0">'.
    '<title>Receipt</title>'.
    
    '<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Audiowide|Sofia|Trirong">'.
    '<link rel="preconnect" href="https://fonts.googleapis.com">'.
    '<link href="https://fonts.googleapis.com/css2?family=Dosis:wght@400;600;800&display=swap" rel="stylesheet"></head><h1 align="center">ATM</h1>'.
    '<p align="center">We make each moment happy!!!</p><body>';
    $html .= '<table>';
    $html .= '<tr><th>Information</th><th>Status</th></tr>';

    foreach ($userData as $key => $value) {
        $html .= '<tr>';
        $html .= '<td>' . ucwords(str_replace("_", " ", $key)) . '</td>';
        $html .= '<td>' . $value . '</td>';
        $html .= '</tr>';
    }

    $html .= '</table>';
    $html .= '</body></html>';


    $options = new Options();
    $options->set('isHtml5ParserEnabled', true);
    $options->set('isPhpEnabled', true);
    $options->set('isRemoteEnabled', true);
    $options->set('isJavascriptEnabled', true);
    $options->set('isPrintBackground', true);

   
    $dompdf = new Dompdf($options);
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();
    $dompdf->stream("user_information.pdf", array("Attachment" => true));
} else {
    echo "No data to display.";
}
?>
