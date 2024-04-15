
  // Get the button element
  function openmenuservices() {
    // Add a click event listener to the button
      // Navigate to a new HTML page
      window.location.href = 'choosecard.php';
    }
   
    
  // Get the button element
  function cancelAndbacktopage() {
    // Add a click event listener to the button
      // Navigate to a new HTML page
      window.location.href = 'index.php';
    }
    function exitAndRedirect() {
      // Perform AJAX request to destroy the session using PHP
      var xhr = new XMLHttpRequest();
      xhr.open('GET', 'assets/php/destroy_session.php', true);
      xhr.send();
  
      // Redirect to the login page after destroying the session
      window.location.href = 'index.php';
  }