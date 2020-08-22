<?php

require_once "components/header.php";

// if the user is not logged in then show the message and die
if (!$loggedin) {
  include_once("components/loggedOutMessage.php");
  die();
}
// if no user was specify, show error message
if (!isset($_GET['user'])) {
  echo "no user selected";
  die();
}
// get the logged in user
$loggedInUser = $_SESSION['user'];

// GET REQUESTS ------------------------------------------------------------------------------
// get the profile of the user selected

$user = $_GET['user'];
$result = queryMysql("SELECT * FROM profiles WHERE user='$user'");

// POST AND DELETE REQUESTS ------------------------------------------------------------------------------
// handle about text change
if (isset($_POST['text'])) {
  // get the about message text
  $text = clearString($_POST['text']);
  $text = preg_replace('/\s\s+/', ' ', $text);

  // if a row for the user already exists, then the user should be updated
  if ($result->num_rows) {
    // get the profile picture of the user
    $row  = $result->fetch_array(MYSQLI_ASSOC);
    $image = $row['image'];
    // update the profile of the user
    queryMysql("UPDATE profiles SET text='$text' where user='$user'");
  }
  // if the user does not have a profile yet
  else {
    // set image to empty
    $image = '';
    // insert the user profile into the profiles table
    queryMysql("INSERT INTO profiles VALUES('$user', '$text', '')");
  }
}
// handle profile picture change
elseif (isset($_POST['image'])) {
  // get the image
  $image = $_POST['image'];
  $image = preg_replace('/\s\s+/', ' ', $image);

  // if a row for the user already exists, then the user should be updated
  if ($result->num_rows) {
    // get the about text of the user
    $row  = $result->fetch_array(MYSQLI_ASSOC);
    $text = stripslashes($row['text']);
    // update the profile of the user
    queryMysql("UPDATE profiles SET image='$image' where user='$user'");
    // update the user image at the members table
    queryMysql("UPDATE members SET image='$image' where user='$user'");
  }
  // if the user does not have a profile yet
  else {
    // set text to empty
    $text = '';
    // insert the user profile into the profiles table
    queryMysql("INSERT INTO profiles VALUES('$user', '', '$image')");
    // update the user image at the members table
    queryMysql("UPDATE members SET image='$image' where user='$user'");
  }
}
// if this is a simple get and the user already has a profile
elseif ($result->num_rows) {
  $row  = $result->fetch_array(MYSQLI_ASSOC);
  $text = stripslashes($row['text']);
  $image = $row['image'];
}
// and the user has no profile yet, then set text and image to empty
else {
  $text = "";
  $image = "";
}

// show profile image, if it exists
if ($image != "") {
  $profilePic = "<img src='$image' class='profilePic'>";;
} else {
  $profilePic = "<img class='profilePic' alt='' src='/images/noPicture.svg'/>";
}

// if the user is not the current user, then there should  not be a submit button, the inputs should be disabled and there should be a send message button
$submitButton = "";
$disableInput = 'disabled';
$sendMessageButton = "<button class='profileButton sendMessageButton' onclick=\"location.href ='messages.php?view=$user'\">Send a Message</button>";

// if the user is the current user, then the save button should be displayed, the input should  be enabled and there should not be a send message button
if ($loggedInUser == $user) {
  $submitButton = "<button class='profileButton' type='submit'>Save</button>";
  $disableInput  = "";
  $sendMessageButton = "";
}


// the profile page should display the picture and the about text with the buttons corresponding to the current user viewing the page
echo "<div class='profileNameContainer'>
        <label class='profileName'>$user</label>
      </div>
      <div class='profileContainer'>
        <form class='profilePicContainer' method='post' action='profile.php?user=$user'>
          $profilePic
          <input $disableInput type='text' name='image' id='image' value='$image' />
          $submitButton
        </form>
        <form class='profileAboutForm' data-ajax='false' method='post' action='profile.php?user=$user' enctype='multipart/form-data'>
          <textarea $disableInput type='text' name='text' id='text' class='aboutInput' >
            " . stripslashes($text) . "
          </textarea>            
          $submitButton
        </form>
      </div>
      <div class='profileNameContainer'>
        $sendMessageButton
      </div>";
