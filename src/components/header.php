
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
    <link rel='stylesheet' href='styles/jquery.mobile-1.4.5.min.css'>
    <link rel='stylesheet' href='styles/mainStyles.css' type='text/css'>
    <link rel='stylesheet' href='styles/componentsStyles.css' type='text/css'>
    <script src='javascript/javascript.js'></script>
    <script src='javascript/jquery-2.2.4.min.js'></script>
    <script src='javascript/jquery.mobile-1.4.5.min.js'></script>

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
      <div data-role='header'>
        <div id='logo' class='center'>
          <div class='titleBox'>
            SPAULAS
          </div>
        </div>
      </div>
      <div data-role='content'>

_MAIN;

if ($loggedin) {
  echo <<<_LOGGEDIN
        <div class='center'>
          <button data-inline='true' data-icon='home' data-transition='slide' title='Home' onclick="location.href = 'pages/members.php?view=$user';">
            <img classname='navBarIcon' src='/images/home.svg'/>
            <label>
              Home
            </label>
          </button>
          <button data-inline='true' data-icon='user' data-transition='slide' title='Home' onclick="location.href = 'pages/members.php';">
            <img classname='navBarIcon' src='/images/members.svg'/>
            <label>
              Members
            </label>
          </button>
          <button data-inline='true' data-icon='heart' data-transition='slide' title='Friends' onclick="location.href = 'pages/friends.php';">
            <img classname='navBarIcon' src='/images/friends.svg'/>
            <label>
              Friends
            </label>
          </button>
          <button data-inline='true' data-icon='mail' data-transition='slide' title='Messages' onclick="location.href = 'pages/messages.php';">
            <img classname='navBarIcon' src='/images/messages.svg'/>
            <label>
              Messages
            </label>
          </button>
          <button data-inline='true' data-icon='edit' data-transition='slide' title='Profile' onclick="location.href = 'pages/profile.php';">
            <img classname='navBarIcon' src='/images/profile.svg'/>
            <label>
              Profile
            </label>
          </button>
          <button data-inline='true' data-icon='action' data-transition='slide' title='Log Out' onclick="location.href = 'pages/logout.php';">
            <img classname='navBarIcon' src='/images/logout.svg'/>
            <label>
              Log Out
            </label>
          </button>
        </div>
        
_LOGGEDIN;
} else {
  // Menu Buttons to Login
  echo <<<_GUEST
    <div class='center'>
      <p class='info'>(You must be logged in to use this app)</p>
    </div>
        
_GUEST;
}
