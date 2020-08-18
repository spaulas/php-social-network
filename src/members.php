<?php

require_once "components/header.php";

$actions = "<div class='actionsContainer'>
  <button class='actionsButton'>Follow</button>
  <button class='actionsButton'>Unfollow</button>
</div>";

$following = "<div class='tooltip'>
  <img class='statusIcons' alt='following' src='/images/following.svg'/>
  <span class='tooltiptext'>Following</span>
</div>";

$followedBy = "<div class='tooltip'>
  <img class='statusIcons' alt='following' src='/images/followingYou.svg'/>
  <span class='tooltiptext'>Following you</span>
</div>";

$both = "<div class='tooltip'>
  <img class='statusIcons' alt='following' src='/images/mutual.svg'/>
  <span class='tooltiptext'>Mutual Connection</span>
</div>";

echo <<<_MEMBERS
  <table class="membersTable">
    <thead>
      <tr>
        <th class="membersTableHeaderTitle statusColumn">Status</th>
        <th class="membersTableHeaderTitle nameColumn">Name</th>
        <th class="membersTableHeaderTitle actionColumn">Action</th>
      </tr>
    </thead>
    <tbody>
      <tr class="membersTableRow">
        <td class="membersTableElem statusColumn">$following</td>
        <td class="membersTableElem nameColumn">
          <div class='profileRowContainer'>
            <img class='profileTableImage' alt='blach' src='/images/emptyProfile.svg'/>
            <div class='profileTableName'>Paula</div>
          </div>  
        </td>
        <td class="membersTableElem actionColumn" >$actions</td>
      </tr>
      <tr class="membersTableRow">
        <td class="membersTableElem statusColumn">$followedBy</td>
        <td class="membersTableElem nameColumn" >
          <div class='profileRowContainer'>
            <img class='profileTableImage' alt='blach' src='/images/emptyProfile.svg'/>
            <div class='profileTableName'>Paula</div>
          </div>  
        </td>
        <td class="membersTableElem actionColumn" >$actions</td>
      </tr>
      <tr class="membersTableRow">
        <td class="membersTableElem statusColumn">$both</td>
        <td class="membersTableElem nameColumn" >
          <div class='profileRowContainer'>
            <img class='profileTableImage' alt='blach' src='/images/emptyProfile.svg'/>
            <div class='profileTableName'>Paula</div>
          </div>  
        </td>
        <td class="membersTableElem actionColumn" >$actions</td>
      </tr>
      <tr class="membersTableRow">
        <td class="membersTableElem statusColumn">$followedBy</td>
        <td class="membersTableElem nameColumn" >
          <div class='profileRowContainer'>
            <img class='profileTableImage' alt='blach' src='/images/emptyProfile.svg'/>
            <div class='profileTableName'>Paula</div>
          </div>  
        </td>
        <td class="membersTableElem actionColumn" >$actions</td>
      </tr>
      <tr class="membersTableRow">
        <td class="membersTableElem statusColumn">$followedBy</td>
        <td class="membersTableElem nameColumn" >
          <div class='profileRowContainer'>
            <img class='profileTableImage' alt='blach' src='/images/emptyProfile.svg'/>
            <div class='profileTableName'>Paula</div>
          </div>  
        </td>
        <td class="membersTableElem actionColumn" >$actions</td>
      </tr>
      <tr class="membersTableRow">
        <td class="membersTableElem statusColumn">$followedBy</td>
        <td class="membersTableElem nameColumn" >
          <div class='profileRowContainer'>
            <img class='profileTableImage' alt='blach' src='/images/emptyProfile.svg'/>
            <div class='profileTableName'>Paula</div>
          </div>  
        </td>
        <td class="membersTableElem actionColumn">$actions</td>
      </tr>
    </tbody>
  </table>
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
_MEMBERS;
