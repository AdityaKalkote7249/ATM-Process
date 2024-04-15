<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="assets\css\pinset.css">
    <!-- Bootstrap CSS -->

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Audiowide|Sofia|Trirong">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Dosis:wght@400;600;800&display=swap" rel="stylesheet">
    <title>Quick Withdrawal Process</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>

<body>
    <form method="post" id="setPinForm">
        <div class="set">
            <div class="row">
                <label for="card"> Enter your card number:- </label>
                <input type="number" id="card_number" name="card_number" required>
            </div>
            <div class="row">
                <label for="pin"> Enter your PIN:-</label>
                <input type="number" id="pin_number" name="pin_number" class="field" required>
            </div>

            <button type="submit">Set</button>
            <button type="submit" onclick="cancel()">Cancel</button>
        </div>

    </form>

    <script>
        document.getElementById('setPinForm').addEventListener('submit', function (e) {
            e.preventDefault();

            if (!validateCredentials()) {
                return;
            }

            var cardNumber = document.getElementById('card_number').value;
            var pinNumber = document.getElementById('pin_number').value;

            $.ajax({
                type: 'POST',
                url: 'pinset1.php', // Your PHP script URL
                data: { card_number: cardNumber, pin_number: pinNumber },
                success: function (response) {
                    var data = JSON.parse(response);
                    alert(data.message);
                    speak(data.message, function () {
                        if (data.success) {
                            window.location.href = 'menu.php';
                        }
                    });
                }
            });
        });

        function validateCredentials() {
            var cardNumber = document.getElementById('card_number').value;
            var pinNumber = document.getElementById('pin_number').value;

            if (pinNumber.length !== 4) {
                var message = "Pin is invalid. It should be 4 digits.";
                speakMessage(message);
                alert(message);
                return false;
            }

            return true;
        }

        function speak(message, callback) {
            var utterance = new SpeechSynthesisUtterance(message);
            utterance.onend = function (event) {
                if (callback) callback();
            };
            window.speechSynthesis.speak(utterance);
        }

        function speakMessage(message) {
            speak(message);
        }
        function cancel(){
            window.location.href="menu.php";
        }
    </script>
</body>

</html>