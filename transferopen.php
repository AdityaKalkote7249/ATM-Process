<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="assets/css/transfer.css">
  
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Audiowide|Sofia|Trirong">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Dosis:wght@400;600;800&display=swap" rel="stylesheet">
  <title>Transfer</title>
</head>

<body>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
    crossorigin="anonymous"></script>
    <form action="transfer.php" method="post" id="transferForm">
  <div class="transfer">
   
      <div class="row">
        <label for="account">Enter account number to transfer to:-</label>
        <input type="number" name="acc" id="acc">
      </div>
      <div class="row">
        <label for="amount">Enter amount to transfer:-</label>
        <input type="number" name="amount" id="amount">
      </div>
      <div class="row">
        <label>Receipt:-</label>
        <input type="checkbox" name="printReceipt" id="printReceipt" class="checkbox">
      </div>
      <div class="buttons">
        <button type="submit">Transfer</button>
        <button type="button" onclick="cancel()">Cancel</button>
      </div>
      </div>
    </form>


   
 


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
      utterance.voice = selectedVoice || voices[1];
      window.speechSynthesis.speak(utterance);
    }
    function validateCredentials() {
      var accNumber = document.getElementById('acc').value;
      if (accNumber.length !== 12) {
        var message = "Please enter the tweleve digit account number.";
        speakMessage(message);
        alert(message);
        return false;
      }
      var amount = document.getElementById('amount').value;
      if (amount > 10000) {
        var message = "You can transfer 10000 at a time.";
        speakMessage(message);
        alert(message);
        return false;
      }
      
      if (amount <= 0) {
            var negativeAmountMessage = "Please transfer correct amount.";
            speakMessage(negativeAmountMessage);
            alert(negativeAmountMessage);
            return false;
        }
      return true;
    }
    document.getElementById('transferForm').addEventListener('submit', function (e) {
      e.preventDefault();
      if (validateCredentials()) {
        var formData = new FormData(this);
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'transfer.php', true);
        xhr.onreadystatechange = function () {
          if (xhr.readyState == 4 && xhr.status == 200) {
            var response = JSON.parse(xhr.responseText);
            if (response.status === 'success') {
              alert(response.message);
              speakMessage(response.message);
              if (document.getElementById('printReceipt').checked && response.receiptUrl) {
                window.location.href = response.receiptUrl;
                setTimeout(function () {
                  window.location.href = 'transferopen.php';
                }, 2000);
              } else {
                window.location.href = 'transferopen.php';
              }
            } else {
              alert(response.message);
              speakMessage(response.message);
              window.location.href = 'transferopen.php';
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
    function cancel() {
      window.location.href = "menu.php";
    }
  </script>
</body>

</html>