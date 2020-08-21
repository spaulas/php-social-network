<h1 align="center">PHP Social Network</h1>
<p align="center">
  <img alt="GitHub top language" src="https://img.shields.io/github/languages/top/spaulas/solitaireGame?logo=typescript&style=for-the-badge">
  <img alt="GitHub package.json version" src="https://img.shields.io/github/package-json/v/spaulas/react-solitaire?style=for-the-badge">
  <img alt="GitHub last commit" src="https://img.shields.io/github/last-commit/spaulas/solitaireGame?style=for-the-badge">
  <img alt="GitHub closed issues" src="https://img.shields.io/github/issues-closed-raw/spaulas/solitaireGame?style=for-the-badge">
  <img alt="GitHub issues" src="https://img.shields.io/github/issues-raw/spaulas/solitaireGame?style=for-the-badge">
</p>
<img align="left" alt="php-social-network" width="100%" src="https://i.ibb.co/RhJpG21/social-Network-Banner.png" />
&nbsp;

<h3 align="center"><a href="https://php-social-network.herokuapp.com/" target="_blank">Website</a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;<a href="http://youtube.com" target="_blank">Demo</a></h3>
<h3 align="left">Description</h3>

<p>Social network implemented with PHP.</p>
<p>In this project, it is necessary to sign up with an unique username in order to have access to the website. By signing up, the user can change their profile and create new posts or can access a friends profile page and see their public messages. </p>

<h3 align="left">Set up</h3>

<p>To set up a local database, it is necessary to change the following values:</p>

```
$dbhost = $dbparts['host'];
$dbuser = $dbparts['user'];
$dbpass = $dbparts['pass'];
$dbname = ltrim($dbparts['path'],'/');
$port   = 3306;
```

<p>Then, to create all the tables, it is necessary to access the setup page.</p>

```
127.0.0.1/setup.php
```

<h3 align="left">Tech Stack</h3>
<img align="left" alt="PHP" height="30px" src="https://cdn.iconscout.com/icon/free/png-512/php-28-226043.png" />
<img align="left" alt="MySQL" height="30px" src="https://www.freepnglogos.com/uploads/logo-mysql-png/logo-mysql-mysql-and-moodle-elearningworld-5.png" />
<img align="left" alt="CSS3" height="30px" src="https://3.bp.blogspot.com/-oRSUw_TmO9o/XIb61m88fcI/AAAAAAAAIq0/vnxl2zzsXEQsnHI2fH4GjKu_ZT0urRo4wCK4BGAYYCw/s1600/icon%2Bcss%2B3.png" />
<img align="left" alt="Javascript" height="30px" src="https://encrypted-tbn0.gstatic.com/images?q=tbn%3AANd9GcTGnwhltDp6v141Wc08D17U-3zGku-gjJEgNg&usqp=CAU" />
<img align="left" alt="Visual Studio Code" height="30px" src="https://upload.wikimedia.org/wikipedia/commons/thumb/9/9a/Visual_Studio_Code_1.35_icon.svg/1024px-Visual_Studio_Code_1.35_icon.svg.png" />
<img align="left" alt="Git" height="25px" src="https://upload.wikimedia.org/wikipedia/commons/thumb/e/e0/Git-logo.svg/1280px-Git-logo.svg.png" />
<br/>


<h3 align="left">Deployment</h3>
<p>Since this project uses its own server, it is being deployed by <a href='https://dashboard.heroku.com'>Heroku</a> with the help of the database-as-service <a href='https://www.jawsdb.com/'>JawsDB</a>.</p>

<h3 align="left">Database</h3>
<p>The database created is rather simple, and consists of 4 tables:</p>
<ul>
  <li>members:
    <ul>
      <li>user  VARCHAR(16) - PRIMARY KEY;</li>
      <li>pass  VARCHAR(16);</li>
      <li>image VARCHAR(4096);<li>
    </ul>
  </li>
  <li>friends'</li>
  <li>profile;</li>
  <li>messages;</li>
</ul>

