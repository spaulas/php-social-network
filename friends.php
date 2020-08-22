<?php

require_once "components/header.php";

// if the user is not logged in then show the message and die
if (!$loggedin) {
  include_once("components/loggedOutMessage.php");
  die();
}

// TABLE VARIABLES ------------------------------------------------------------------------------
// pagination
$currentPage = 0;   // current page index being displayed
$finalIndex = 1;    // index of the final element to be shown
$nPages = 1;        // total number of pages
$pageSize = 10;     // number of elements per page
// search
$search = "";       // search value
// filter
$filter = "";       // filter by value


// POST AND DELETE REQUESTS ------------------------------------------------------------------------------
// unfollow
// remove member selected of the current user's following list
if (isset($_GET['remove'])) {
  $remove = clearString($_GET['remove']);
  queryMysql("DELETE FROM friends WHERE user='$user' AND friend='$remove'");
}
// drop
// remove the current user from the selected member's following list
elseif (isset($_GET['drop'])) {
  $remove = clearString($_GET['drop']);
  queryMysql("DELETE FROM friends WHERE user='$remove' AND friend='$user'");
}

// GET REQUESTS ------------------------------------------------------------------------------
// save search value
if (isset($_GET['search'])) {
  $search = $_GET['search'];
}
// save filter value
if (isset($_GET['filter'])) {
  $filter = $_GET['filter'];
}
// save current page and final index
if (isset($_GET['page'])) {
  $page =  $_GET['page'];
  if ($page >= 0 && $page < $nPages) {
    $currentPage = $_GET['page'];
    $finalIndex = ($_GET['page'] + 1) * $pageSize;
  }
}

// CREATE ACTIONS BUTTONS ------------------------------------------------------------------------------
// returns the actions available to a member that the user is following
// actions: 'message' and 'unfollow'
function followingAction($rowUser)
{
  global $currentPage;
  global $search;
  global $filter;
  return "<div class='actionsContainer'>
    <button class='actionsButton' onclick=\"document.location.href='messages.php?view=" . $rowUser . "'\">Message</button>
    <button class='actionsButton' onclick=\"document.location.href='friends.php?remove=" . $rowUser . "&page=" . $currentPage . "&search=" . $search . "&filter=" . $filter . "'\">Unfollow</button>
  </div>";
}

// returns the actions available to a member that if following the user
// actions: 'message', 'follow' and 'drop'
function followingYouAction($rowUser)
{
  global $currentPage;
  global $search;
  global $filter;
  return "<div class='actionsContainer'>
    <button class='actionsButton' onclick=\"document.location.href='messages.php?view=" . $rowUser . "'\">Message</button>
    <button class='actionsButton' onclick=\"document.location.href='friends.php?remove=" . $rowUser . "&page=" . $currentPage . "&search=" . $search . "&filter=" . $filter . "'\">Unfollow</button>
    <button class='actionsButton' onclick=\"document.location.href='friends.php?drop=" . $rowUser . "&page=" . $currentPage . "&search=" . $search . "&filter=" . $filter . "'\">Drop</button>
  </div>";
}

// returns the actions available to a member that the user has a mutual connection with
// actions: 'message', 'unfollow' and 'drop'
function mutualAction($rowUser)
{
  global $currentPage;
  global $search;
  global $filter;
  return "<div class='actionsContainer'>
    <button class='actionsButton' onclick=\"document.location.href='messages.php?view=" . $rowUser . "'\">Message</button>
    <button class='actionsButton' onclick=\"document.location.href='friends.php?remove=" . $rowUser . "&page=" . $currentPage . "&search=" . $search . "&filter=" . $filter . "'\">Unfollow</button>
    <button class='actionsButton' onclick=\"document.location.href='friends.php?drop=" . $rowUser . "&page=" . $currentPage . "&search=" . $search . "&filter=" . $filter . "'\">Drop</button>
  </div>";
}

// CREATE CONNECTION ICONS ------------------------------------------------------------------------------

// Following Icon
$followingIcon = "<div class='tooltip'>
  <img class='statusIcons' alt='following' src='/images/following.svg'/>
  <span class='tooltiptext'>Following</span>
</div>";

// Mutual Icon
$mutualIcon = "<div class='tooltip'>
  <img class='statusIcons' alt='following' src='/images/mutual.svg'/>
  <span class='tooltiptext'>Mutual Connection</span>
</div>";

// GET ELEMENTS REQUESTS ------------------------------------------------------------------------------
$result;

// the request to be done depends on the type of connection choosen in the filter
switch ($filter) {
  case "following":
    // get all members the user has as friend but the members don't have as friends in the friends table
    $result = queryMysql("SELECT members.user, members.image FROM members INNER JOIN friends ON members.user=friends.friend WHERE friends.user = '$user' AND members.user NOT IN (SELECT members.user FROM members INNER JOIN friends ON members.user=friends.user WHERE friends.friend = '$user') AND members.user LIKE '%$search%'");
    break;
  case "mutual":
    // get all members the user has as friend and the members have as friends as well in the friends table
    $result = queryMysql("SELECT members.user, members.image FROM members INNER JOIN friends ON members.user=friends.friend WHERE friends.user = '$user' AND members.user IN (SELECT members.user FROM members INNER JOIN friends ON members.user=friends.user WHERE friends.friend = '$user') AND members.user LIKE '%$search%'");
    break;
  default:
    // @TODO
    $result = queryMysql("SELECT members.user, members.image FROM members WHERE (user LIKE '%$search%') ORDER BY user");
    break;
}
// get the list of all the members
$num    = $result->num_rows;
// the number of pages available is the total number of members divided by the max number of members per page
$nPages  = ceil($num / $pageSize);
// if in the last page, the members don't fill all the spots, then return the index of the last member
$max    = $num > $finalIndex ? $finalIndex : $num;
// get all the info in an array
$row = $result->fetch_all();

// PRINT TABLE ------------------------------------------------------------------------------

// print table with headers (status, name and action)
echo "<table class='membersTable'>
    <thead>
      <tr>
        <th class='membersTableHeaderTitle statusColumn'>Status</th>
        <th class='membersTableHeaderTitle nameColumn'>Name</th>
        <th class='membersTableHeaderTitle actionColumn'>Action</th>
      </tr>
    </thead>
    <tbody>";


if ($num > 0) {
  // go through each member to create their respective table row
  for ($j = $currentPage * $pageSize; $j < $max; $j++) {
    // check if the member has a profile picture
    // if yes, then put it inside an img tag
    if ($row[$j][1] != '') {
      $profileIcon = "<img class='profileTableImage' alt='' src='" . $row[$j][1] . "'/>";
    }
    // if no, render the default image
    else {
      $profileIcon = "<img class='profileTableImage' alt='' src='/images/noPicture.svg'/>";
    }

    // if the row is the current user, then print without the status and actions columns and skip the rest of the loop
    if ($row[$j][0] == $user) {
      echo "<tr class='membersTableRow'>
      <td class='membersTableElem statusColumn'/>
      <td class='membersTableElem nameColumn'>
        <div class='profileRowContainer'>
          $profileIcon
          <div class='profileTableName' onclick=\"location.href = 'profile.php?user=" . $row[$j][0] . "';\">" . $row[$j][0] . "</div>
        </div>  
      </td>
      <td class='membersTableElem actionColumn'/>
    </tr>";
      continue;
    }

    // check connections between current user to the member
    $result1 = queryMysql("SELECT * FROM friends WHERE user='" . $row[$j][0] . "' AND friend='$user'");
    $t1      = $result1->num_rows;
    // check connections between the member and the current user
    $result1 = queryMysql("SELECT * FROM friends WHERE user='$user' AND friend='" . $row[$j][0] . "'");
    $t2      = $result1->num_rows;

    // get the final result of the connection
    $connectionIcon = null;
    $connectionAction = null;
    if (($t1 + $t2) > 1) {
      $connectionIcon = $mutualIcon;
      $connectionAction = mutualAction($row[$j][0]);
    } elseif ($t2) {
      $connectionIcon = $followingIcon;
      $connectionAction = followingAction($row[$j][0]);
    } else {
      continue;
    }

    // print the status, name and actions columns
    echo "<tr class='membersTableRow'>
  <td class='membersTableElem statusColumn'>
    $connectionIcon
  </td>
  <td class='membersTableElem nameColumn'>
    <div class='profileRowContainer'>
      $profileIcon
      <div class='profileTableName' onclick=\"location.href = 'profile.php?user=" . $row[$j][0] . "';\">" . $row[$j][0] . "</div>
    </div>  
  </td>
  <td class='membersTableElem actionColumn'>$connectionAction</td>
</tr>";
  }
  echo "</tbody></table>";
} else {
  echo "</tbody>
          <div class='emptyTableInfo'>
            No data
          </div></table>";
}

// PRINT TABLE OPTIONS ------------------------------------------------------------------------------

// check which filter is selected and add the 'selectedPage' classname (to highlight the applied filter)
$selectedFollowing = $filter == 'following' ? 'selectedPage' : '';
$selectedMutual = $filter == 'mutual' ? 'selectedPage' : '';

echo "</tbody></table>
  <div class='optionsContainer'>
    <div class='filtersContainer'>
      <div class='tooltip'>
        <img class='filterOption $selectedFollowing' alt='following' src='/images/following.svg' onclick=\"document.location.href='friends.php?page=" . $currentPage . "&search=" . $search . "&filter=following'\" />
        <span class='tooltiptext'>Following</span>
      </div>
      <div class='tooltip'>
        <img class='filterOption $selectedMutual' alt='following' src='/images/mutual.svg' onclick=\"document.location.href='friends.php?page=" . $currentPage . "&search=" . $search . "&filter=mutual'\" />
        <span class='tooltiptext'>Mutual Connection</span>
      </div>
      <div class='tooltip'>
        <img class='filterOption' alt='clear' src='/images/clear.svg' onclick=\"document.location.href='friends.php?page=" . $currentPage . "&search=" . $search . "&filter='\" />
        <span class='tooltiptext'>Clear</span>
      </div>
    </div>
    <div class='filtersContainer'>
      <form class='searchForm' action='friends.php?page=" . $currentPage . "&filter=" . $filter . "' method='get'>
        <input value='$search' type='text' name='search' id='search' class='searchInput' />
        <button class='searchButton' type='submit'>
          Search
        </button>
      </form>
    </div>";

// link to the previous page
$backLink = $currentPage > 0 ? "document.location.href='friends.php?page=" . ($currentPage - 1) . "&search=" . $search . "&filter=" . $filter . "'" : null;
echo "<div class='paginationContainer' onclick=\"" . $backLink . "\">
      <button class='paginationButton'>
        <
      </button>";
// pages buttons
for ($p = 0; $p < $nPages; $p++) {
  $selectedStyle = $currentPage == $p ? "selectedPage" : "";
  echo "<button class='paginationButton $selectedStyle' onclick=\"document.location.href='friends.php?page=" . $p . "&search=" . $search . "&filter=" . $filter . "'\">" . ($p + 1) . "</button>";
}
// link to the next page
$nextLink = $currentPage < $nPages ? "document.location.href='friends.php?page=" . ($currentPage + 1) . "&search=" . $search . "&filter=" . $filter . "'" : null;
echo "<button class='paginationButton'>
        >
      </button>
    </div>
  </div>";
