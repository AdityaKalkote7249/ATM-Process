function openmenuservices() {
    // Add a click event listener to the button
      // Navigate to a new HTML page
      window.location.href = 'menu.php';
    }

    function  goback()  {
      // Add a click event listener to the button
        // Navigate to a new HTML page
        window.location.href = 'selectcard.php';
      }
      function cancel(){
        // Perform AJAX request to destroy the session using PHP
        var xhr = new XMLHttpRequest();
        xhr.open('GET', 'assets/php/destroy_session.php', true);
        xhr.send();
    
        // Redirect to the login page after destroying the session
        window.location.href = 'index.php';
    }