<!doctype html>
<html lang="en">
<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="assets/css/quickwithdrawl.css">
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Audiowide|Sofia|Trirong">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Dosis:wght@400;600;800&display=swap" rel="stylesheet">
  <title>Quick Withdrawal Process</title>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
    crossorigin="anonymous"></script>
  <form action="verifyuser.php" method="post" id="withdrawalForm">
    <div class="first">
      <ul class="breadcrumbs">
        <li><a href="index.php">Home</a></li>
        <li><a href="selectcard.php">Select Card</a></li>
        <li><a href="menu.php">Menu</a></li>
        <li class="active"><a href="#">Set Pin</a></li>
      </ul>
      <h1 align="center">Enter your credentials correctly</h1>
      <div class="row mb-3">
        <label for="inputEmail3" class="col-sm-2 col-form-label">Registered <br>mobile number:-</label>
        <div class="col-sm-10">
          <input type="number" class="form-control" id="mobile_number" name="mobile_number" required>
        </div>
      </div>
      <div class="row mb-3">
        <label for="inputPassword3" class="col-sm-2 col-form-label">Last four digits of<br> adhaar card number:-</label>
        <div class="col-sm-10">
          <input type="number" class="form-control" id="adhaar" name="adhaar" required>
        </div>
      </div>
     

      <div class="ser">
        <button type="submit" class="button">Proceed&#8250;&#8250;</button>
        <button type="button" class="button" onclick="cancelpinset()">Cancel</button>
      </div>

    </div>
  </form>
  <script>
    // Voice synthesis functions as before
    let voices;
  let selectedVoice;
  function loadVoices() {
    voices = window.speechSynthesis.getVoices();
    selectedVoice = voices.find(voice => voice.lang === 'en-GB');
  }
  loadVoices();
  if (speechSynthesis.onvoiceschanged !== undefined) {
    speechSynthesis.onvoiceschanged = loadVoices;
  }
  function speakMessage(message) {
    const utterance = new SpeechSynthesisUtterance(message);
    utterance.voice = selectedVoice || voices[0];
    window.speechSynthesis.speak(utterance);
  }
  function validateCredentials() {
    var mobileNumber = document.getElementById('mobile_number').value;
    var adhaar = document.getElementById('adhaar').value;
  
   
    if (mobileNumber.length !== 10) {
      var message = "Please enter the correct registered mobile number.";
      speakMessage(message);
      alert(message);
      return false;
    }
    if (adhaar.length !== 4) {
      var message = "Please enter last four digits of yout adhaar card number.";
      speakMessage(message);
      alert(message);
      return false;
    }
    return true;
  }
    document.getElementById('withdrawalForm').addEventListener('submit', function (e) {
      e.preventDefault();
      if (validateCredentials()) {
        var formData = new FormData(this);
        $.ajax({
          type: 'POST',
          url: 'verifyuser.php',
          data: formData,
          processData: false,
          contentType: false,
          success: function(response) {
            if (response.valid) {
                var otp=response.otp;
              alert("Your OTP is: " +response.otp);
              var otpDigits=response.otp.toString().split('');
              var spokenOTP = 'Your OTP is: ' + otpDigits.join(' ');
              // Redirect to OTP entry page
              var msg = new SpeechSynthesisUtterance();
    msg.text = spokenOTP;
    msg.rate = 0.8;
    window.speechSynthesis.speak(msg);
   
              window.location.href = 'enterotp.php'; // Update with the path to your OTP entry page
            } else {
              alert(response.message);
              speakMessage(response.message);
            }
          },
          error: function() {
            alert('An error occurred while processing your request.');
          }
        });
      }
    });

    function cancelpinset() {
      window.location.href="menu.php";
    }
  </script>
</body>
</html>
