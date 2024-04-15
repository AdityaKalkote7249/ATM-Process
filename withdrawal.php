<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="assets/css/quickwithdrawl.css">
    <!-- Bootstrap CSS and Google Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Audiowide|Sofia|Trirong">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Dosis:wght@400;600;800&display=swap" rel="stylesheet">
    <title>Transaction page</title>
</head>

<body>
    <form action="process_withdrawl.php" method="post" onsubmit="return handleWithdrawal()">
        <div class="transaction">
           
            <div class="heading">
                <h1>Enter your amount</h1>
            </div>
            <div class="input-group">
                <label for="inputAmount" class="form-trans">Enter your amount:-</label>
                <input type="number" id="inputAmount" class="trans" name="amount" required>
            </div>
            <div class="printer">
                Receipt:-<input type="checkbox" name="print" value="1">
            </div>
            <div class="button-group">
                <button type="submit" class="trans-button">Withdraw&raquo;</button>
                <button type="button" class="trans-button" onclick="movetoback()">&laquo;Back</button>
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
        if (speechSynthesis.onvoiceschanged !== undefined) {
            speechSynthesis.onvoiceschanged = loadVoices;
        } else {
            loadVoices();
        }
        function speakMessage(message, callback) {
            var msg = new SpeechSynthesisUtterance(message);
            if (selectedVoice) {
                msg.voice = selectedVoice;
            } else {
                msg.lang = 'en-GB';
            }
            msg.onend = function (event) {
                if (callback) {
                    callback();
                }
            };
            window.speechSynthesis.speak(msg);
        }
        function handleWithdrawal() {
    var printReceipt = document.querySelector('input[name="print"]').checked;
    var amount = document.getElementById('inputAmount').value;
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "process_withdrawl.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            var response = JSON.parse(this.responseText);
            if (response.success) {
                if (printReceipt) {
                    downloadReceipt(response);
                }
                var successMessage = "Transaction successful!";
                alert(successMessage); // Ensure this alert is working
                speakMessage(successMessage, function() {
                    window.location.href = 'success_transaction.html';
                });
            } else {
                var errorMessage = "Error: " + response.message;
                alert(errorMessage); // Alert for error
                speakMessage(errorMessage); // Speak error message
            }
        }
    };
    xhr.send("amount=" + amount);
}

function speakMessage(message, callback) {
    var synth = window.speechSynthesis;
    var utterance = new SpeechSynthesisUtterance(message);
    utterance.onend = function() {
        if (callback) {
            callback();
        }
    };
    synth.speak(utterance);
}

        function downloadReceipt(response) {
            var receiptWindow = window.open('generate_pdf.php?userData=' + encodeURIComponent(JSON.stringify(response)), '_blank');
            if (receiptWindow) {
                receiptWindow.focus();
            } else {
                alert("Unable to open receipt. Please allow pop-ups for this site.");
            }
        }
        function movetoback(){
            window.location.href="quickwithdrawl.php";
        }
    </script>
</body>

</html>