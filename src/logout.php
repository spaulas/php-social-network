<?php
require_once 'components/header.php';

if (isset($_SESSION['user'])) {
  destroySession();

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
  echo "<div class='formContainer'>
          <h2 class='resultMessage'>
            You cannot log out because you are not logged in.
          </h2>
          <button class='backHomeButton backHomeButtonResultMessage' onclick=\"document.location.href='/home.php'\">
            Home
          </button>
        </div>
      </div></body></html>";
}
?>
</div>
</body>

</html>