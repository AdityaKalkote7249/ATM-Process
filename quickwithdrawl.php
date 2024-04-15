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
</head>

<body>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
    crossorigin="anonymous"></script>
  <form action="userauthontication.php" method="post" id="withdrawalForm">
    <div class="first">
      <ul class="breadcrumbs">
        <li><a href="index.php">Home</a></li>
        <li><a href="selectcard.php">Select Card</a></li>
        <li><a href="menu.php">Menu</a></li>
        <li class="active"><a href="#">credentials page</a></li>
      </ul>
      <h1 align="center">Enter your credentials correctly</h1>
      <div class="row mb-3">
        <label for="inputEmail3" class="col-sm-2 col-form-label">Entered card number:-</label>
        <div class="col-sm-10">
          <input type="number" class="form-control" id="card_number" name="card_number" required>
        </div>
      </div>
      <div class="row mb-3">
        <label for="inputPassword3" class="col-sm-2 col-form-label">Enter pin:-</label>
        <div class="col-sm-10">
          <input type="number" class="form-control" id="pin_number" name="pin_number" required>
        </div>
      </div>
      <fieldset class="row mb-3">
        <legend class="col-form-label col-sm-2 pt-0">Select account type:-</legend>
        <div class="col-sm-10">
          <div class="form-check">
            <input class="form-check-input" type="radio" name="acc_type" id="Savings" value="Savings">
            <label class="form-check-label" for="Savings">
              Savings Account
            </label>
          </div>
          <div class="form-check">
            <input class="form-check-input" type="radio" name="acc_type" id="Current" value="Current">
            <label class="form-check-label" for="Current">
              Current Account
            </label>
          </div>
        </div>
      </fieldset>

      <div class="ser">
        <button type="submit" class="button">Proceed&#8250;&#8250;</button>
        <button type="button" class="button" onclick="canceltransaction()">Cancel</button>
      </div>

    </div>
  </form>
</body>
<script>
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
    var cardNumber = document.getElementById('card_number').value;
    var pinNumber = document.getElementById('pin_number').value;
    var savings = document.getElementById('Savings');
    var current = document.getElementById('Current');
    if (!savings.checked && !current.checked) {
      var message = "Please select an account type.";
      speakMessage(message);
      alert(message);
      return false;
    }
    if (cardNumber.length !== 4) {
      var message = "Please enter the last four digits of your card number.";
      speakMessage(message);
      alert(message);
      return false;
    }
    if (pinNumber.length !== 4) {
      var message = "Pin is invalid. It should be 4 digits.";
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
      var xhr = new XMLHttpRequest();
      xhr.open('POST', 'userauthontication.php', true);
      xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
          var response = JSON.parse(xhr.responseText);
          if (response.valid) {
            window.location.href = 'withdrawal.php';
          } else {
            alert(response.message);
            speakMessage(response.message);
          }
        }
      };
      xhr.send(formData);
    }
  });
  function canceltransaction() {
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'assets/php/destroy_session.php', true);
    xhr.onload = function () {
      if (xhr.status == 200) {
        window.location.href = 'index.php';
      }
    };
    xhr.send();
  }
</script>
</body>

</html>