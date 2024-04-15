<!doctype html>
<html lang="en">
<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="assets\css\enterotp.css">
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
    <form action="verifyotp.php" method="post" id="otpForm">
        <div class="otp">
            
        <label for="enterotp"> Enter your OTP:-</label>
       
        <input type="number" id="otp" name="otp" required>
        <div class="buttons">
        <button type="submit">Verify OTP</button>
      
      <button type="button" id="resendOtp" onclick="resendotp()">Resend OTP</button>
        </div>
        <p id="timer"><i class="material-icons">&#xe425;</i>Timer:-00</p>
        
    <audio id="beepSound" src="assets/audio/beep.mp3"></audio>

        </div>
        
</form>
    <script>
        var timerElement = document.getElementById('timer');
        var beepSound = document.getElementById('beepSound');
        var timeLeft = 12; 
        var otpExpired = false;
        var interval;
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

        function startTimer() {
            interval = setInterval(function() {
                if (timeLeft <= 0) {
                    clearInterval(interval);
                    beepSound.pause();
                    beepSound.currentTime = 0;
                    timerElement.innerHTML = "Your OTP has expired.";
                    otpExpired = true;
                    var message = "Your OTP has expired, request for another OTP click on resend.";
                    speakMessage(message);
                    alert(message);
                } else {
                    timerElement.innerHTML = timeLeft + " seconds remaining";
                    beepSound.play();
                    timeLeft--;
                }
            }, 1000);
        }

        function getOTP() {
            
            return ""; 
        }

        var otp = getOTP();
        var msg = new SpeechSynthesisUtterance(otp);
        window.speechSynthesis.speak(msg);

        msg.onend = function(event) {
            startTimer();
        };

        document.getElementById('otpForm').addEventListener('submit', function(e) {
            e.preventDefault();
            if (otpExpired) {
                alert("OTP has expired. Please request a new OTP.");
                return;
            }

            var enteredOtp = document.getElementById('otp').value;

            $.ajax({
                type: 'POST',
                url: 'verifyotp.php',
                data: { otp: enteredOtp },
                success: function(response) {
                    if (response.valid) {
                        window.location.href = 'pinset.php';
                    } else {
                        var message = "OTP is invalid, enter correct OTP.";
                        speakMessage(message);
                        alert(message);
                    }
                },
                error: function() {
                    alert('An error occurred while verifying the OTP.');
                }
            });
        });
        function resendotp() {
            window.location.href = 'setpin.php';
        }
       
    </script>
</body>
</html>
