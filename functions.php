<?php

$url = getenv('mysql://g0r2b2lr88uxlmox:tsgyog8z97dgdnhf@u3y93bv513l7zv6o.chr7pe7iynqr.eu-west-1.rds.amazonaws.com:3306/z4adbn7gmyi6w8u1');
$dbparts = parse_url($url);

$dbhost = $dbparts['host'];
$dbuser = $dbparts['user'];
$dbpass = $dbparts['pass'];
$dbname = ltrim($dbparts['path'],'/');

// global variable with the stabilished connection with the database
$connection = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
// if an error happened, kill the application
if ($connection->connect_error) {
  die("Fatal Error");
}

/**
 * Checks whether a table already exists and, if not, creates it
 * @param string $name name for the new table
 * @param string $query query to create a new table
 */
function createTable($name, $query)
{
  queryMysql("CREATE TABLE IF NOT EXISTS $name($query)");
  echo "Table '$name' created or already exists.<br>";
}

/**
 * Issues a query to MySQL, outputting an error message if it fails
 * @param string $query query to be executed
 */
function queryMysql($query)
{
  global $connection;
  $result = $connection->query($query);

  if (!$result) {
    die("Fatal Error");
  }

  return $result;
}

/**
 * Destroys a PHP session and clears its data to log users out
 */
function destroySession()
{
  $_SESSION = array();

  // if a session is set or if the current session name is set in the HTTP Cookies
  if (session_id() != "" || isset($_COOKIE[session_name()])) {
    // then for the current session name, 
    // 2592000: January 1, 1970  (time zero for any device that uses Unix) - subtracting it, will make it expired!
    // path = '/' makes it available for the entire domain
    setcookie(session_name(), '', time() - 2592000, '/');
  }

  // destroy all data registration for the session
  $destroyResult = session_destroy();
  if (session_destroy() === false) {
  }
}

/**
 * Removes potentially malicious code or tags from user input
 * @param string $var value to be "cleared"
 */
function clearString($var)
{
  global $connection;
  // remove HTML and PHP tags from the string
  $var = strip_tags($var);
  // convert all applicable characters to HTML entities
  $var = htmlentities($var);
  // remove special characters
  $var = $connection->real_escape_string($var);

  return $var;
}
