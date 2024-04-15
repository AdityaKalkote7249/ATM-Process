<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu</title>
    <link rel="stylesheet" href="assets\css\menu.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Audiowide|Sofia|Trirong">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Dosis:wght@400;600;800&display=swap" rel="stylesheet">
</head>
<body>
    <div class="main">
       
        <div class="content">
            <ul class="breadcrumbs">
                <li><a href="index.php">Home</a></li>
                <li><a href="selectcard.php">Select Card</a></li>
                <li class="active"><a href="#">Menu</a></li>    
              </ul>
              <div class="services">
                <button type="submit" onclick="openQuickWithdrawal()" class="sb">Quick Withdrawl</button>
                <button type="submit" onclick="openBalanceEnquiry()" class="sb">Balance Enquiry</button>
              </div>
              <div class="services">
                <button type="submit" onclick="openSetPin()" class="sb">Set PIN</button>
                <button type="submit" onclick="openMiniStatement()" class="sb">Mini Statement</button>
              </div>
              <div class="services">
                <button type="submit" onclick="openCashDeposite()" class="sb">Cash Deposite</button>
                <button type="submit" onclick="openFundTransfer()" class="sb">Fund Transfer</button>
              </div>
              <div class="funbuttons">
              <button type="submit" onclick="cancelAndbacktopage()">Back</button>
                <button type="submit" onclick="cancelAndRedirect()" >Exit</button>
            </div>
        </div>
    </div>
</body>
<script src="assets\javascript\menu.js"></script>
</html>