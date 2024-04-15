<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Audiowide|Sofia|Trirong">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Dosis:wght@400;600;800&display=swap" rel="stylesheet">
<title>Choose Card</title>
<link rel="stylesheet" href="assets/css/choosecard.css">
</head>
<body>
    <div class="content">
    <ul class="breadcrumbs">
                <li><a href="index.php">Home</a></li>
                <li><a href="selectcard.php">Card type</a></li>
                <li class="active"><a href="#">Select card</a></li>
            </ul>
        <div class="button-group">
            <button type="button" onclick="openmenuservices()">VISA ATM Card</button>
            <button type="button" onclick="openmenuservices()">Mastercards ATM Card</button>
        </div>
        <div class="button-group">
            <button type="button" onclick="openmenuservices()">RuPay ATM Card</button>
            <button type="button" onclick="openmenuservices()">Contactless Card</button>
        </div>
        <div class="button-group">
        <button type="button" onclick="goback()" class="cancel">Back</button>
        <button type="button" onclick="cancel()" class="cancel">Exit</button>
       
        </div>
       
    </div>
</body>
<script src="assets/javascript/choosecard.js"></script>
</html>
