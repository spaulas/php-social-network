<?php

require_once "components/header.php";
require_once 'functions.php';


echo <<<_END
  <script>
    function checkUser(user)
    {
      if (user.value == '')
      {
        $('#used').html('&nbsp;')
        return
      }

      $.post
      (
        'checkuser.php',
        { user : user.value },
        function(data)
        {
          $('#used').html(data)
        }
      )
    }
  </script>  
_END;


$error = $user = $pass = "";
if (isset($_SESSION['user'])) destroySession();

if (isset($_POST['user'])) {
  $user = clearString($_POST['user']);
  $pass = clearString($_POST['pass']);

  if ($user == "" || $pass == "")
    $error = 'Not all fields were entered<br><br>';
  else {
    $result = queryMysql("SELECT * FROM members WHERE user='$user'");

    if ($result->num_rows)
      $error = 'That username already exists<br><br>';
    else {
      queryMysql("INSERT INTO members VALUES('$user', '$pass')");
      die("<div class='formContainer'>
            <h2 class='resultMessage'>
              Account created
            </h2>
            <h4 class='resultMessage'>
              Please Log in.
            </h4>
            <button class='backHomeButton backHomeButtonResultMessage' onclick=\"document.location.href='/'\">
              Home
            </button>
          </div>
        </div></body></html>");
    }
  }
}

echo <<<_FORM
<div class='formContainer'>
  <form method='post' action='signUp.php'>
    <div data-role='fieldcontain'>
      <label></label>
      Please enter your details to sign up
    </div>
    <div data-role='fieldcontain'>
      <label>Username</label>
      <input class='loginInput' type='text' maxlength='16' name='user' value='$user'>
    </div>
    <div data-role='fieldcontain'>
      <label>Password</label>
      <input class='loginInput' type='password' maxlength='16' name='pass' value='$pass' autocomplete="on">
    </div>
    <div data-role='fieldcontain'>
        <label></label>
        <span class='error'>$error</span>
    </div>
    <div data-role='fieldcontain' >
      <label></label>
      <input data-transition='slide' type='submit' value='Sign Up'>
    </div>
  </form>
  <button class='backHomeButton' onclick="document.location.href='/'">
    Home
  </button>
</div>
_FORM;
