<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Audiowide|Sofia|Trirong">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Dosis:wght@400;600;800&display=swap" rel="stylesheet">
    <title>Select card</title>
    <link rel="stylesheet" href="assets\css\selectcard.css">
</head>

<body>
    <div class="main">

        <div class="content">
            <ul class="breadcrumbs">
                <li><a href="index.php">Home</a></li>
                <li class="active"><a href="#">Select Type</a></li>
            </ul>
            <div class="funbuttons">
                <button type="submit" onclick="openmenuservices()" >Domestic</button>
                <button type="submit" onclick="openmenuservices()" >International</button>
            </div>
            <div class="services">
                <button type="submit" onclick="cancelAndbacktopage()" class="sb">Back</button>
                <button type="submit" onclick="exitAndRedirect()" class="sb">Exit</button>
            </div>
        </div>
    </div>
</body>
<script src="assets\javascript\selectcard.js"></script>
</html>