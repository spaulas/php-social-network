<?php

require_once "components/header.php";

if (!$loggedin) {
  die("</div></body></html>");
}

// pagination variables
$currentPage = 0;
$finalIndex = 2;
$nPages = 1;
$pageSize = 10;
// search
$search = "";
// filter
$filter = "";
$inputValue = "";

// follow
if (isset($_GET['add'])) {
  $add = clearString($_GET['add']);
  $result = queryMysql("SELECT * FROM friends WHERE user='$add' AND friend='$user'");
  if (!$result->num_rows)
    queryMysql("INSERT INTO friends VALUES ('$add', '$user')");
}
// unfollow
elseif (isset($_GET['remove'])) {
  $remove = clearString($_GET['remove']);
  queryMysql("DELETE FROM friends WHERE user='$remove' AND friend='$user'");
}
// drop
elseif (isset($_GET['drop'])) {
  $remove = clearString($_GET['drop']);
  queryMysql("DELETE FROM friends WHERE user='$user' AND friend='$remove'");
}
// search
if (isset($_GET['search'])) {
  $search = $_GET['search'];
}
// filter
if (isset($_GET['filter'])) {
  $filter = $_GET['filter'];
}
// page
if (isset($_GET['page'])) {
  $page =  $_GET['page'];
  if ($page >= 0 && $page < $nPages) {
    $currentPage = $_GET['page'];
    $finalIndex = ($_GET['page'] + 1) * $pageSize;
  }
}

function noneAction($rowUser)
{
  global $currentPage;
  global $search;
  global $filter;
  return "<div class='actionsContainer'>
    <button class='actionsButton' onclick=\"document.location.href='members.php?add=" . $rowUser . "&page=" . $currentPage . "&search=" . $search . "&filter=" . $filter . "'\">Follow</button>
  </div>";
}

function followingAction($rowUser)
{
  global $currentPage;
  global $search;
  global $filter;
  return "<div class='actionsContainer'>
    <button class='actionsButton' onclick=\"document.location.href='members.php?remove=" . $rowUser . "&page=" . $currentPage . "&search=" . $search . "&filter=" . $filter . "'\">Unfollow</button>
  </div>";
}

function followingYouAction($rowUser)
{
  global $currentPage;
  global $search;
  global $filter;
  return "<div class='actionsContainer'>
    <button class='actionsButton' onclick=\"document.location.href='members.php?drop=" . $rowUser . "&page=" . $currentPage . "&search=" . $search . "&filter=" . $filter . "'\">Drop</button>
  </div>";
}

function mutualAction($rowUser)
{
  global $currentPage;
  global $search;
  global $filter;
  return "<div class='actionsContainer'>
    <button class='actionsButton' onclick=\"document.location.href='members.php?drop=" . $rowUser . "&page=" . $currentPage . "&search=" . $search . "&filter=" . $filter . "'\">Unfollow</button>
    <button class='actionsButton' onclick=\"document.location.href='members.php?remove=" . $rowUser . "&page=" . $currentPage . "&search=" . $search . "&filter=" . $filter . "'\">Drop</button>
  </div>";
}


// type of icons according to the connection between the users
$followingIcon = "<div class='tooltip'>
  <img class='statusIcons' alt='following' src='/images/following.svg'/>
  <span class='tooltiptext'>Following</span>
</div>";

$followingYouIcon = "<div class='tooltip'>
  <img class='statusIcons' alt='following' src='/images/followingYou.svg'/>
  <span class='tooltiptext'>Following you</span>
</div>";

$bothIcon = "<div class='tooltip'>
  <img class='statusIcons' alt='following' src='/images/mutual.svg'/>
  <span class='tooltiptext'>Mutual Connection</span>
</div>";

$noneIcon = "<div class='tooltip'>
  <img class='statusIcons' alt='following' src='/images/none.svg'/>
  <span class='tooltiptext'>No Connection</span>
</div>";

$result;

switch ($filter) {
  case "none":
    $result = queryMysql("SELECT * FROM members WHERE members.user LIKE '%$search%' AND members.user NOT IN (SELECT members.user FROM members INNER JOIN friends ON members.user=friends.user WHERE friends.user = '$user' OR friends.friend = '$user')");
    break;
  case "following":
    $result = queryMysql("SELECT members.user, members.pass FROM members INNER JOIN friends ON members.user=friends.user WHERE friends.user != '$user' AND friends.friend = '$user' AND members.user LIKE '%$search%'");
    break;
  case "followingYou":
    $result = queryMysql("SELECT members.user, members.pass FROM members INNER JOIN friends ON members.user=friends.user WHERE friends.user = '$user' AND friends.friend != '$user' AND members.user LIKE '%$search'");
    break;
  case "mutual":
    $result = queryMysql("SELECT members.user, members.pass FROM members INNER JOIN friends ON members.user=friends.user WHERE friends.user = '$user' AND friends.friend = '$user' AND members.user LIKE '%$search%'");
    break;
  default:
    $result = queryMysql("SELECT user FROM members WHERE (user LIKE '%$search%') ORDER BY user");
    break;
}
// get the list of all the members
$num    = $result->num_rows;
$nPages  = ceil($num / $pageSize);
$max    = $num > $finalIndex ? $finalIndex : $num;

$row = $result->fetch_all();

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
for ($j = $currentPage * $pageSize; $j < $max; $j++) {
  if ($row[$j][0] == $user) {
    echo "<tr class='membersTableRow'>
      <td class='membersTableElem statusColumn'/>
      <td class='membersTableElem nameColumn'>
        <div class='profileRowContainer'>
          <img class='profileTableImage' alt='' src='/images/noPicture.svg'/>
          <div class='profileTableName'>" . $row[$j][0] . "</div>
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
  $connectionIcon = $noneIcon;
  $connectionAction = noneAction($row[$j][0]);
  if (($t1 + $t2) > 1) {
    $connectionIcon = $mutualIcon;
    $connectionAction = mutualAction($row[$j][0]);
  } elseif ($t1) {
    $connectionIcon = $followingIcon;
    $connectionAction = followingAction($row[$j][0]);
  } elseif ($t2) {
    $connectionIcon = $followingYouIcon;
    $connectionAction = followingYouAction($row[$j][0]);
  }

  echo "<tr class='membersTableRow'>
  <td class='membersTableElem statusColumn'>
    $connectionIcon
  </td>
  <td class='membersTableElem nameColumn'>
    <div class='profileRowContainer'>
      <img class='profileTableImage' alt='' src='/images/noPicture.svg'/>
      <div class='profileTableName'>" . $row[$j][0] . "</div>
    </div>  
  </td>
  <td class='membersTableElem actionColumn'>$connectionAction</td>
</tr>";
}


$selectedNone = $filter == 'none' ? 'selectedPage' : '';
$selectedFollowing = $filter == 'following' ? 'selectedPage' : '';
$selectedFollowingYou = $filter == 'followingYou' ? 'selectedPage' : '';
$selectedMutual = $filter == 'mutual' ? 'selectedPage' : '';

echo "</tbody></table>
  <div class='optionsContainer'>
    <div class='tooltip'>
      <img class='filterOption $selectedNone' alt='none' src='/images/none.svg' onclick=\"document.location.href='members.php?page=" . $currentPage . "&search=" . $search . "&filter=none'\" />
      <span class='tooltiptext'>No Connection</span>
    </div>
    <div class='filtersContainer'>
      <div class='tooltip'>
        <img class='filterOption $selectedFollowing' alt='following' src='/images/following.svg' onclick=\"document.location.href='members.php?page=" . $currentPage . "&search=" . $search . "&filter=following'\" />
        <span class='tooltiptext'>Following</span>
      </div>
      <div class='tooltip'>
        <img class='filterOption $selectedFollowingYou' alt='following' src='/images/followingYou.svg' onclick=\"document.location.href='members.php?page=" . $currentPage . "&search=" . $search . "&filter=followingYou'\" />
        <span class='tooltiptext'>Following you</span>
      </div>
      <div class='tooltip'>
        <img class='filterOption $selectedMutual' alt='following' src='/images/mutual.svg' onclick=\"document.location.href='members.php?page=" . $currentPage . "&search=" . $search . "&filter=mutual'\" />
        <span class='tooltiptext'>Mutual Connection</span>
      </div>
      <div class='tooltip'>
        <img class='filterOption' alt='clear' src='/images/clear.svg' onclick=\"document.location.href='members.php?page=" . $currentPage . "&search=" . $search . "&filter='\" />
        <span class='tooltiptext'>Clear</span>
      </div>
    </div>";

echo "<div class='filtersContainer'>
<form class='searchForm' action='members.php?page=" . $currentPage . "&filter=" . $filter . "' method='get'>
      <input value=$search type='text' name='search' id='search' class='searchInput' />
      <button class='searchButton' type='submit'>
        Search
      </button>
    </div>";

// link to the previous page
$backLink = $currentPage > 0 ? "document.location.href='members.php?page=" . ($currentPage - 1) . "&search=" . $search . "&filter=" . $filter . "'" : null;
echo "<div class='paginationContainer' onclick=\"" . $backLink . "\">
      <button class='paginationButton'>
        <
      </button>";
// pages buttons
for ($p = 0; $p < $nPages; $p++) {
  $selectedStyle = $currentPage == $p ? "selectedPage" : "";
  echo "<button class='paginationButton $selectedStyle' onclick=\"document.location.href='members.php?page=" . $p . "&search=" . $search . "&filter=" . $filter . "'\">" . ($p + 1) . "</button>";
}
// link to the next pages
$nextLink = $currentPage < $nPages ? "document.location.href='members.php?page=" . ($currentPage + 1) . "&search=" . $search . "&filter=" . $filter . "'" : null;
echo "<button class='paginationButton'>
        >
      </button>
    </div>
  </div>";
