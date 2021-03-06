<?php
require_once 'components/header.php';

// if the user is not logged in then show the message and die
if (!$loggedin) {
  include_once("components/loggedOutMessage.php");
  die();
}
// GET REQUESTS ------------------------------------------------------------------------------
// get the receiver of the message (if none, then it is for the current user himself)
if (isset($_GET['view'])) {
  $receiver = clearString($_GET['view']);
} else {
  $receiver = $user;
}

// POST AND DELETE REQUESTS ------------------------------------------------------------------------------
// send message
if (isset($_POST['text'])) {
  // get the message text
  $text = clearString($_POST['text']);
  // the message is only posted if it was not empty
  if ($text != "") {
    // get the type of message
    $pm   = substr(clearString($_POST['pm']), 0, 1);
    // get the current time
    $time = time();

    // get the reply key
    if (isset($_GET['reply'])) {
      // if a reply key was sent, than this message is a reply to another
      // the reply basically is the id of the main message
      $reply = $_GET['reply'];
      // the final receiver is the member who wrote the main message
      $finalReceiver = $_GET['replyTo'];
    } else {
      // if this is a main message, then the final receiver is the previously set receiver
      $finalReceiver = $receiver;
      // and the reply id is null
      $reply = 'NULL';
    }
    // insert the message in the messages table
    queryMysql("INSERT INTO messages VALUES (NULL, '$user',
        '$finalReceiver', '$pm', $time, '$text', $reply)");
  }
}
// delete messages
// delete the message selected
if (isset($_GET['erase'])) {
  // get the id of the message to delete
  $erase = clearString($_GET['erase']);
  // check if it is to delete all the messages
  $eraseAll = clearString($_GET['eraseAll']);

  // if delete all is true, then delete all the messages with the answerto id equal to the selected message id
  if ($eraseAll == 1) {
    queryMysql("DELETE FROM messages WHERE id=$erase OR answerto=$erase");
  }
  // if not, then simply delete the message with the selected id
  else {
    queryMysql("DELETE FROM messages WHERE id=$erase");
  }
}

// GET ELEMENTS REQUESTS ------------------------------------------------------------------------------

// get all the user's previous main messages (all the messages he authored and the messages he received)
$query  = "SELECT * FROM messages WHERE ((auth='$user' OR recip='$user') AND (auth='$receiver' OR recip='$receiver') AND answerto IS NULL) ORDER BY time DESC";
$mainMessages = queryMysql($query);
// get the number of results found
$num    = $mainMessages->num_rows;

// info labels
// if the receiver is the user, then simply write 'From user' and specify that all the messages are being displayed
if ($user == $receiver) {
  $messageInfoLabel = "<label class='messageInfoLabel'>From $user</label>";
  $messagesFrom = "<label class='messagesFrom'>All Messages</label>";
}
// if the receiver is another user, then add 'From user to receiver' and specify that only the messages between those two users are being displayed
else {
  $messageInfoLabel = "<label class='messageInfoLabel'>From $user to $receiver</label>";
  $messagesFrom = "<label class='messagesFrom'>Messages between you and $receiver</label>";
}

// PRINT NEW MAIN MESSAGE FORM ------------------------------------------------------------------------------

// print form with text input, radio buttons (private or public) and submit button
echo "<div class='sendMessageContainer'>
    <form class='messageForm' method='post' action='messages.php?view=$receiver'>
      <div class='messageHeader'>
        $messageInfoLabel
        <div class='radioContainer'>
          <fieldset data-role='controlgroup' data-type='horizontal'>
            <input class='radioButtonNone' type='radio' name='pm' id='public' value='0' checked='checked'>
            <label for='public'>Public</label>
            <input class='radioButtonNone' type='radio' name='pm' id='private' value='1'>
            <label for='private'>Private</label>
          </fieldset>
        </div>
      </div>
      <textarea name='text'></textarea>
      <button class='sendMessageButton' type='submit'>Post Message</button>
    </form>
    $messagesFrom
  </div>";

// prints the previous messages (taking into consideration if it is a main message or simply a reply)
function printOldMessage($isMainMessage, $author, $dest, $mType, $time, $message, $id)
{
  global $user;
  global $receiver;

  // if the destination is the author then simply print 'From user'
  if ($dest ==  $author) {
    $oldMessageInfoLabel = "<label class='messageInfoLabel author'>From $author</label>";
  }
  // else print 'From user to destination'
  else {
    $oldMessageInfoLabel = "<label class='messageInfoLabel author'>From $author to $dest</label>";
  }

  // if the type of message is 1, then print ;Private'
  if ($mType == 1) {
    $oldMessageTypeLabel = "<label class='messageInfoLabel messageType'>Private</label>";
  }
  // else print 'Public'
  else {
    $oldMessageTypeLabel = "<label class='messageInfoLabel messageType'>Public</label>";
  }

  // create label for the time the message was sent
  $oldMessageTime = "<label class='messageInfoLabel messageDate'>" . date('M jS \'y g:ia', $time) . "</label>";
  // if the message is a reply, then add a classname to push it a little to the right side
  $answerClass = $isMainMessage ? '' : 'answerDiff';

  // if the message author is the current user
  if ($user == $author) {
    // and if it is a main message, then the button to delete should send the eraseAll as true (to delete the main message and all its replies)
    if ($isMainMessage) {
      $deleteButton = "<img src='images/delete.svg' class='deleteMessage' onclick=\"location.href='messages.php?view=$receiver&erase=$id&eraseAll=1' \">";
    }
    // and if not, then the button should do the delete request with eraseAll set to 0 (to only delete the selected message)
    else {
      $deleteButton = "<img src='images/delete.svg' class='deleteMessage' onclick=\"location.href='messages.php?view=$receiver&erase=$id&eraseAll=0' \">";
    }
  }
  // if the author of the message is not the current user, then the user cannot delete it
  else {
    $deleteButton = "";
  }

  // return a container with a header (author and destination, type and date), the message and a delete button (or not)
  return "<div class=$answerClass>
            <div class='oldMessageHeader'>
              $oldMessageTypeLabel
              $oldMessageInfoLabel
              $oldMessageTime
            </div>
            <div class='oldMessageField' >$message</div>
            $deleteButton
          </div>";
}

// if no messages were found, then show info message
if (!$num) {
  echo "<label class='noMessagesYet'>No messages yet</label>";
} else {
  // if the user has messages, then go through each one
  for ($j = 0; $j < $num; ++$j) {
    $row = $mainMessages->fetch_array(MYSQLI_ASSOC);

    // print the main message
    echo printOldMessage(true, $row['auth'], $row['recip'], $row['pm'], $row['time'], $row['message'], $row['id']);

    // get all the replies to that message
    $query  = "SELECT * FROM messages WHERE answerto='" . $row['id'] . "' ORDER BY time ASC";
    $answerMessages = queryMysql($query);
    // number of replies found
    $nAnswers    = $answerMessages->num_rows;

    // if the main message has replies, then print each one
    if ($nAnswers) {
      // print each reply
      for ($l = 0; $l < $nAnswers; ++$l) {
        $answersRow = $answerMessages->fetch_array(MYSQLI_ASSOC);
        echo printOldMessage(false, $answersRow['auth'], $answersRow['recip'], $row['pm'], $answersRow['time'], $answersRow['message'], $answersRow['id']);
      }
    }

    // add another form to answer to the last message of each group
    echo "<form class='answerDiff replyMessageForm' method='post' action='messages.php?view=$receiver&reply=" . $row['id'] . "&replyTo=" . $row['auth'] . "&pm=" . $row['pm'] . "'>
            <textarea name='text'></textarea>
            <button class='sendMessageButton buttonReply'>Reply</button>
          </form>";
  }
}

// add button to refresh the messages
echo "<button class='sendMessageButton refreshMessages' onclick=\"location.href='messages.php?view=$receiver' \">Refresh Messages</button>";
