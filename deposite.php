<!DOCTYPE html>
<html lang="en">
<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="assets\css\deposite.css">
  <!-- Bootstrap CSS -->

  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Audiowide|Sofia|Trirong">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Dosis:wght@400;600;800&display=swap" rel="stylesheet">
  <title>Depoiste</title>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>
<body>
    <form id="depositform" action="deposting.php" method="post">
        <div class="deposite">
            <div class="row">
            <label for="deposite">
        Enter your deposited amount:-
        </label>
        <input type="number" id="deposite" name="deposite">
            </div>
       
        <div class="buttons">
        <button type="submit">Deposite</button>
        <button type="button" onclick="cancelback()">Cancel</button>
        </div>
        </div>
    </form>
    </body>
    <script>
        let voices;
        let selectedVoice;

        window.speechSynthesis.onvoiceschanged = function() {
            voices = window.speechSynthesis.getVoices();
            selectedVoice = voices.find(voice => voice.lang === 'en-GB');
        };

        function speakMessage(message) {
            const utterance = new SpeechSynthesisUtterance(message);
            utterance.voice = selectedVoice || voices[1];
            window.speechSynthesis.speak(utterance);
        }

        function depositForm() {
            var amount = parseInt(document.getElementById('deposite').value, 10);
            if (amount > 10000) {
            var limitMessage = "You can deposit  10,000 at a time.";
            speakMessage(limitMessage);
            alert(limitMessage);
            return false;
        }

            if (!isValidAmount(amount)) {
                var message = "Invalid amount. Please deposite only 100 or 200 or 500 rupees currency.";
                speakMessage(message);
                alert(message);
                return false;
            }

            var deposite = $('#deposite').val();

            $.ajax({
                type: 'POST',
                url: 'deposting.php', 
                data: { deposite: deposite },
                success: function(response) {
                    var data = JSON.parse(response);
                    alert(data.message);
                    speakMessage(data.message);
                    if (data.success) {
                  
                         window.location.href = 'menu.php'; 
                    }
                }
            });

            return false; 
        }

        function isValidAmount(amount) {
            var denominations = [500, 200, 100];
            for (var i = 0; i < denominations.length; i++) {
                while (amount >= denominations[i]) {
                    amount -= denominations[i];
                }
            }
            return amount === 0;
        }

  
        $('#depositform').submit(depositForm);
      
    </script>
<script>
      function cancelback(){
            window.location.href="menu.php";
        }
</script>
</html>
