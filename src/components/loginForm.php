
<?php

require_once 'functions.php';
echo <<<_FORM
<div class='formContainer'>
  <form method='post' action='login.php'>
    <div data-role='fieldcontain'>
      <label></label>
      <span class='error'>$error</span>
    </div>
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
    <div data-role='fieldcontain' >
      <label></label>
      <input data-transition='slide' type='submit' value='Login'>
    </div>
  </form>
  <button class='backHomeButton' onclick="document.location.href='/home.php'">
    Home
  </button>
</div>
_FORM;
