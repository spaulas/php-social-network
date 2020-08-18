<?php

require_once "components/header.php";

if (!$loggedin) {
  die("</div></body></html>");
}

function followingAction($rowUser)
{
  return "<div class='actionsContainer'>
    <button class='actionsButton' onclick=\"document.location.href='members.php?remove=$rowUser\">Unfollow</button>
  </div>";
}

function mutualAction($rowUser)
{
  return "<div class='actionsContainer'>
  <button class='actionsButton' onclick=\"document.location.href='members.php?remove=$rowUser\">Unfollow</button>
    <button class='actionsButton' onclick=\"document.location.href='members.php?remove=$rowUser\">Drop</button>
  </div>";
}


// type of icons according to the connection between the users
$followingIcon = "<div class='tooltip'>
  <img class='statusIcons' alt='following' src='/images/following.svg'/>
  <span class='tooltiptext'>Following</span>
</div>";

$bothIcon = "<div class='tooltip'>
  <img class='statusIcons' alt='following' src='/images/mutual.svg'/>
  <span class='tooltiptext'>Mutual Connection</span>
</div>";


// get the list of all the members
$result = queryMysql("SELECT user FROM friends ORDER BY user");
$num    = $result->num_rows;

// print table with headers
echo "<table class='membersTable'>
    <thead>
      <tr>
        <th class='membersTableHeaderTitle statusColumn'>Status</th>
        <th class='membersTableHeaderTitle nameColumn'>Name</th>
        <th class='membersTableHeaderTitle actionColumn'>Action</th>
      </tr>
    </thead>
    <tbody>";

// go through each member to create their respective table row
for ($j = 0; $j < $num; ++$j) {
  $row = $result->fetch_array(MYSQLI_ASSOC);
  if ($row['user'] == $user) continue;

  // check connections between current user to the member
  $result1 = queryMysql("SELECT * FROM friends WHERE user='" . $row['user'] . "' AND friend='$user'");
  $t1      = $result1->num_rows;
  // check connections between the member and the current user
  $result1 = queryMysql("SELECT * FROM friends WHERE user='$user' AND friend='" . $row['user'] . "'");
  $t2      = $result1->num_rows;

  // get the final result of the connection
  $connectionIcon;
  $connectionAction;
  if (($t1 + $t2) > 1) {
    $connectionIcon = $mutualIcon;
    $connectionAction = mutualAction($row['user']);
  } elseif ($t1) {
    $connectionIcon = $followingIcon;
    $connectionAction = followingAction($row['user']);
  } else {
    // in the friends list it is only visible the people who the user is following or has mutual connection
    continue;
  }

  echo "<tr class='membersTableRow'>
  <td class='membersTableElem statusColumn'>
    $connectionIcon
  </td>
  <td class='membersTableElem nameColumn'>
    <div class='profileRowContainer'>
      <img class='profileTableImage' alt='' src='/images/noPicture.svg'/>
      <div class='profileTableName'>" . $row['user'] . "</div>
    </div>  
  </td>
  <td class='membersTableElem actionColumn'>$connectionAction</td>
</tr>";
}

echo "</tbody></table>";

      /* 
  <div class='optionsContainer'>
    <div class='filtersContainer'>
      <div class='tooltip'>
        <img class='filterOption' alt='following' src='/images/following.svg'/>
        <span class='tooltiptext'>Following</span>
      </div>
      <div class='tooltip'>
        <img class='filterOption' alt='following' src='/images/followingYou.svg'/>
        <span class='tooltiptext'>Following you</span>
      </div>
      <div class='tooltip'>
        <img class='filterOption' alt='following' src='/images/mutual.svg'/>
        <span class='tooltiptext'>Mutual Connection</span>
      </div>
    </div>
    <div class='filtersContainer'>
      <input class='searchInput' />
      <button class='searchButton'>
        Search
      </button>
    </div>
    <div class='paginationContainer'>
      <button class='paginationButton'>
        <
      </button>
      <button class='paginationButton'>1</button>
      <button class='paginationButton'>
        >
      </button>
    </div>
  </div>
_MEMBERS; */
