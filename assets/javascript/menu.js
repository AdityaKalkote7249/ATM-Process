function cancelAndRedirect() {
    // Perform AJAX request to destroy the session using PHP
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'assets/php/destroy_session.php', true);
    xhr.send();

    // Redirect to the login page after destroying the session
    window.location.href = 'index.php';
}
function openQuickWithdrawal() {
            window.location.href = 'quickwithdrawl.php';
        }
        function openBalanceEnquiry() {
            window.location.href = 'balancenquiry.php';
        }
       
        function openSetPin() {
            window.location.href = 'setpin.php';
        }
        function openMiniStatement() {
            window.location.href = 'statementauthorized.php';
        }
       
        function openCashDeposite() {
            window.location.href = 'cashdeposite.php';
        }
        function openFundTransfer() {
            window.location.href = 'fundtransfer.php';
        }
// Detect when the user navigates back and destroy the session
window.addEventListener('pageshow', function (event) {
    if (event.persisted) {
        // Page was restored from the cache (user navigated back)
        cancelAndRedirect();
    }
});
function cancelAndbacktopage() {
    // Add a click event listener to the button
      // Navigate to a new HTML page
      window.location.href = 'selectcard.php';
    }