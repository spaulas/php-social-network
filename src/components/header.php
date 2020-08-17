
<?php

// start session to 'remember' values to be stored accross the different php files
session_start();

echo <<<_INIT
<!DOCTYPE html> 
<html>
  <head>
    <meta charset='utf-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@200&display=swap" rel="stylesheet">
    <link rel='stylesheet' href='/styles/jquery.mobile-1.4.5.min.css'>
    <link rel='stylesheet' href='/styles/mainStyles.css' type='text/css'>
    <link rel='stylesheet' href='/styles/componentsStyles.css' type='text/css'>
    <script src='/javascript/javascript.js'></script>
    <script src='/javascript/jquery-2.2.4.min.js'></script>
    <script src='/javascript/jquery.mobile-1.4.5.min.js'></script>

_INIT;

require_once 'functions.php';

$userstr = '';

if (isset($_SESSION['user'])) {
  $user     = $_SESSION['user'];
  $loggedin = TRUE;
  $userstr  = "Logged in as: $user";
} else $loggedin = FALSE;

// App Banner
echo <<<_MAIN
    <title>SPAULAS</title>
  </head>
  <body>
    <div data-role='page'>
      <div data-role='header' class='header'>
        <div id='logo' class='center'>
          <div class='titleBox'>
            SPAULAS
          </div>
        </div>
      </div>

_MAIN;

if ($loggedin) {
  echo <<<_LOGGEDIN
        <div class='navBar'>
          <button class='navBarButton' data-inline='true' data-transition='slide' title='Home' onclick="location.href = 'pages/members.php?view=$user';">
            <img class='navBarIcon' src='/images/home.svg'/>
            <label class='navBarTitle'>
              Home
            </label>
          </button>
          <button class='navBarButton' data-inline='true' data-transition='slide' title='Home' onclick="location.href = 'pages/members.php';">
            <img class='navBarIcon' src='/images/members.svg'/>
            <label class='navBarTitle'>
              Members
            </label>
          </button>
          <button class='navBarButton' data-inline='true' data-transition='slide' title='Friends' onclick="location.href = 'pages/friends.php';">
            <img class='navBarIcon' src='/images/friends.svg'/>
            <label class='navBarTitle'>
              Friends
            </label>
          </button>
          <button class='navBarButton' data-inline='true' data-transition='slide' title='Messages' onclick="location.href = 'pages/messages.php';">
            <img class='navBarIcon' src='/images/messages.svg'/>
            <label class='navBarTitle'>
              Messages
            </label>
          </button>
          <button class='navBarButton' data-inline='true' data-transition='slide' title='Profile' onclick="location.href = 'pages/profile.php';">
            <img class='navBarIcon' src='/images/profile.svg'/>
            <label class='navBarTitle'>
              Profile
            </label>
          </button>
          <button class='navBarButton' data-inline='true' data-transition='slide' title='Log Out' onclick="location.href = 'pages/logout.php';">
            <img class='navBarIcon' src='/images/logout.svg'/>
            <label class='navBarTitle'>
              Log Out
            </label>
          </button>
        </div>
        
_LOGGEDIN;
} else {
  // Menu Buttons to Login
  echo <<<_GUEST
    <div class='center'>
      <p class='infoMessage'>(You must be logged in to use this app)</p>
    </div>
        
_GUEST;
}
