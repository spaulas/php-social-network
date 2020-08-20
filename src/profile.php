<?php

require_once "components/header.php";


if (!$loggedin) die("</div></body></html>");
if (!isset($_GET['user'])) {
  echo "no user selected";
  die();
}

$loggedInUser = $_SESSION['user'];

$user = $_GET['user'];
$result = queryMysql("SELECT * FROM profiles WHERE user='$user'");

// handle a text change
if (isset($_POST['text'])) {
  $text = clearString($_POST['text']);
  $text = preg_replace('/\s\s+/', ' ', $text);

  // if a row for the user already exists, update it
  if ($result->num_rows) {
    // get the image of the row
    $row  = $result->fetch_array(MYSQLI_ASSOC);
    $image = $row['image'];
    queryMysql("UPDATE profiles SET text='$text' where user='$user'");
  }
  // if no row exists, then create it with no image
  else {
    // set image to empty
    $image = '';
    queryMysql("INSERT INTO profiles VALUES('$user', '$text', '')");
  }
} elseif (isset($_POST['image'])) {
  $image = $_POST['image'];
  $image = preg_replace('/\s\s+/', ' ', $image);

  // if a row for the user already exists, update it
  if ($result->num_rows) {
    // get the text of the row
    $row  = $result->fetch_array(MYSQLI_ASSOC);
    $text = stripslashes($row['text']);
    queryMysql("UPDATE profiles SET image='$image' where user='$user'");
  }
  // if no row exists, then create it with no text
  else {
    // set text to empty
    $text = '';
    queryMysql("INSERT INTO profiles VALUES('$user', '', '$image')");
  }
}
// if this is a simple get
elseif ($result->num_rows) {
  $row  = $result->fetch_array(MYSQLI_ASSOC);
  $text = stripslashes($row['text']);
  $image = $row['image'];
} else {
  $text = "";
  $image = "";
}

// show profile image, if it exists
if ($image != "") {
  $profilePic = "<img src='$image' class='profilePic'>";;
} else {
  $profilePic = "<img class='profilePic' alt='' src='/images/noPicture.svg'/>";
}

$submitButton = $loggedInUser == $user ? "<button class='profileButton' type='submit'>Save Picture</button>" : "";
$disableInput = $loggedInUser == $user ? '' : 'disabled';

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
  </div>";
