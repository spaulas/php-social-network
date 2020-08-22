<?php
require_once 'components/header.php';

if (isset($_SESSION['user'])) {
  // destroy the seddion
  destroySession();

  // show info message to the user and button to redirect to the main page 
  echo "<div class='formContainer'>
        <h2 class='resultMessage'>
          You have been logged out.
        </h2>
        <button class='backHomeButton backHomeButtonResultMessage' onclick=\"document.location.href='/'\">
          Home
        </button>
      </div>
    </div></body></html>";
} else {
  // if the user was not logged in, then show info message and button to go back to the main page
  echo "<div class='formContainer'>
          <h2 class='resultMessage'>
            You cannot log out because you are not logged in.
          </h2>
          <button class='backHomeButton backHomeButtonResultMessage' onclick=\"document.location.href='/'\">
            Home
          </button>
        </div>
      </div></body></html>";
}
?>
</div>
</body>

</html>