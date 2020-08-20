<?php

require_once "components/header.php";
require_once 'functions.php';

$error = $user = $pass = "";

if (isset($_POST['user'])) {
  $user = clearString($_POST['user']);
  $pass = clearString($_POST['pass']);

  if ($user == "" || $pass == "")
    $error = 'Not all fields were entered';
  else {
    $result = queryMySQL("SELECT user,pass FROM members
        WHERE user='$user' AND pass='$pass'");

    if ($result->num_rows == 0) {
      $error = "Invalid login attempt";
    } else {
      $_SESSION['user'] = $user;
      $_SESSION['pass'] = $pass;

      die("<div class='formContainer'>
            <h2 class='resultMessage'>
              You are now logged in.
            </h2>
            <button class='backHomeButton backHomeButtonResultMessage' onclick=\"document.location.href='/home.php'\">
              Home
            </button>
          </div>
        </div></body></html>");
    }
  }
}

echo <<<_FORM
<div class='formContainer'>
  <form method='post' action='login.php'>
    <div data-role='fieldcontain'>
      <label></label>
      Please enter your details to log in
    </div>
    <div data-role='fieldcontain'>
      <label>Username</label>
      <input class='loginInput' type='text' maxlength='16' name='user' value='$user'>
    </div>
    <div data-role='fieldcontain'>
      <label>Password</label>
      <input class='loginInput' type='password' maxlength='16' name='pass' value='$pass'>
    </div>
    <div data-role='fieldcontain'>
      <label></label>
      <span class='error'>$error</span>
    </div>
    <div data-role='fieldcontain' >
      <label></label>
      <input data-transition='slide' type='submit' value='Login'>
    </div>
  </form>
  <button class='backHomeButton' onclick="document.location.href='/'">
    Home
  </button>
</div>
_FORM;
