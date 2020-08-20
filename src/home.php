<?php
require_once 'components/header.php';

if (!$loggedin) die("</div></body></html>");

// post a message
if (isset($_POST['text'])) {
  $text = clearString($_POST['text']);

  if ($text != "") {
    $pm   = substr(clearString($_POST['pm']), 0, 1);
    $time = time();


    if (isset($_GET['reply'])) {
      $reply = $_GET['reply'];
      $pm = substr(clearString($_GET['pm']), 0, 1);
    } else {
      $reply = 'NULL';
    }
    queryMysql("INSERT INTO messages VALUES (NULL, '$user',
        '$$user', '$pm', $time, '$text', $reply)");
  }
}
// delete messages
if (isset($_GET['erase'])) {
  $erase = clearString($_GET['erase']);
  $eraseAll = clearString($_GET['eraseAll']);

  if ($eraseAll == 1) {
    queryMysql("DELETE FROM messages WHERE id=$erase OR answerto=$erase");
  } else {
    queryMysql("DELETE FROM messages WHERE id=$erase");
  }
}

// get all the user's previous messages
$query  = "SELECT * FROM messages WHERE pm='0' AND answerto IS NULL ORDER BY time DESC";
$mainMessages = queryMysql($query);
$num    = $mainMessages->num_rows;

$messageInfoLabel = "<label class='messageInfoLabel'>From $user</label>";


echo "<div class='sendMessageContainer'>
    <form class='messageForm' method='post' action='messages.php'>
      <div class='messageHeader'>
        $messageInfoLabel
        <div class='radioContainer'>
          <fieldset data-role='controlgroup' data-type='horizontal'>
            <input class='radioButtonNone' type='radio' name='pm' id='public' value='0' checked='checked'>
            <label for='public'>Public</label>
          </fieldset>
        </div>
      </div>
      <textarea name='text'></textarea>
      <button class='sendMessageButton' type='submit'>Post</button>
    </form>
  </div>";

function printOldMessage($isMainMessage, $author, $dest, $mType, $time, $message, $id)
{
  global $user;

  if ($dest ==  $author) {
    $oldMessageInfoLabel = "<label class='messageInfoLabel author'>From $author</label>";
  } else {
    $oldMessageInfoLabel = "<label class='messageInfoLabel author'>From $author to $dest</label>";
  }

  if ($mType == 1) {
    $oldMessageTypeLabel = "<label class='messageInfoLabel messageType'>Private</label>";
  } else {
    $oldMessageTypeLabel = "<label class='messageInfoLabel messageType'>Public</label>";
  }

  $oldMessageTime = "<label class='messageInfoLabel messageDate'>" . date('M jS \'y g:ia', $time) . "</label>";

  $answerClass = $isMainMessage ? '' : 'answerDiff';

  if ($user == $author) {
    if ($isMainMessage) {
      $deleteButton = "<img src='images/delete.svg' class='deleteMessage' onclick=\"location.href='messages.php?erase=$id&eraseAll=1' \">";
    } else {
      $deleteButton = "<img src='images/delete.svg' class='deleteMessage' onclick=\"location.href='messages.php?erase=$id&eraseAll=0' \">";
    }
  } else {
    $deleteButton = "";
  }

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

if (!$num) {
  echo "<label class='noMessagesYet'>No posts yet</label>";
} else {
  for ($j = 0; $j < $num; ++$j) {
    $row = $mainMessages->fetch_array(MYSQLI_ASSOC);


    // the main message should be a big div
    echo printOldMessage(true, $row['auth'], $row['recip'], $row['pm'], $row['time'], $row['message'], $row['id']);

    // get all the answers to that message
    $query  = "SELECT * FROM messages WHERE answerto='" . $row['id'] . "' ORDER BY time ASC";
    $answerMessages = queryMysql($query);
    $nAnswers    = $answerMessages->num_rows;

    if ($nAnswers) {
      for ($l = 0; $l < $nAnswers; ++$l) {
        $answersRow = $answerMessages->fetch_array(MYSQLI_ASSOC);
        echo printOldMessage(false, $answersRow['auth'], $answersRow['recip'], $answersRow['pm'], $answersRow['time'], $answersRow['message'], $answersRow['id']);
      }
    }

    // add extra field to respond to the post
    echo "<form class='answerDiff replyMessageForm' method='post' action='messages.php?reply=" . $row['id'] . "&replyTo=" . $row['auth'] . "&pm=" . $row['pm'] . "'>
            <textarea name='text'></textarea>
            <button class='sendMessageButton buttonReply'>Reply</button>
          </form>";
  }
}

echo "<button class='sendMessageButton refreshMessages' onclick=\"location.href='messages.php' \">Refresh Posts</button>";

?>

</div><br>
</body>

</html>