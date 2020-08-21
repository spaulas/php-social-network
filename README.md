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
      <li>user     VARCHAR(16);</li>
      <li>pass     VARCHAR(16);</li>
      <li>image    VARCHAR(4096);<li>
    </ul>
  </li>
  <li>friends:
    <ul>
      <li>user     VARCHAR(16);</li>
      <li>friend   VARCHAR(16);</li>
    </ul>
  </li>
  <li>profile:
    <ul>
      <li>user     VARCHAR(16);</li>
      <li>text     VARCHAR(4096);<li>
      <li>image    VARCHAR(4096);<li>
    </ul>
  </li>
  <li>messages:
    <ul>
      <li>id       INT UNSIGNED AUTO_INCREMENT PRIMARY KEY;</li>
      <li>auth     VARCHAR(16);</li>
      <li>recip    VARCHAR(16);</li>
      <li>pm       CHAR(1);</li>
      <li>time     INT UNSIGNED;</li>
      <li>message  VARCHAR(4096);</li>
      <li>answerto VARCHAR(16);</li>
    </ul>
  </li>
</ul>


<h3 align="left">Members</h3>
<p>In the members page, the logged in user has access to all the members registered. Each member is displayed in a table with:</p>
<ul>
  <li>status:
    <ul>
      <li>none: the user has no connection with the member;</li>
      <li>following: the user is following the other member (the the user can see the member's posts);</li>
      <li>followed: the user is being followed by the member (the member can see the user's posts);</li>
      <li>mutual: there is a mutual connection between the user and the member (both can see eachother posts);<li>
    </ul>
  </li>
  <li>profile picture;</li>
  <li>user name;</li>
  <li>actions:
    <ul>
      <li>if there is no connection, the user can follow the member and send messages;</li>
      <li>if the user is following the member, the user can unfollow him and send messages;</li>
      <li>if the user is being followed by the member, the user can follow him, drop him and send messages;</li>
      <li>if the user has a mutual connection with the member, the user can unfollow him, drop him and send messages;</li>
    </ul>
  </li>
 </ul>
<p>It is also possible to filter the members shown in the list, by:</p>
<ul>
  <li>searching;</li>
  <li>type of connection (none, following, followed or mutual);</li>
</ul>
<p>By clicking in the member's username, the user is redirected to the member's profile page.</p>
  
<h3 align="left">Friends</h3>
<p>In the members page, the logged in user has access to all the members he is following or has a mutual connection with. Each member is displayed in the table the same way it is being done in the members page, except that in this table it is only possible to filter the users by 'following' or 'mutual'.</p>

<h3 align="left">Profile</h3>
<p>In the profile page, the user can add a picture and an about message.</p>

<h3 align="left">Messages</h3>
<p>In the messages page, the user can see all of his messages, the ones he sent or received.</p>
<p>Since the initial message can have replies, the initial and all the replies are grouped. A user can delete his own message or messages he received. By deleting a reply, only the reply is deleted, but if a main message is deleted all the replies are too.</p>
<p>The messages page can also only show the messages exchanged between the user and another member, by clicking in the action 'message' from the members or friends table.</p>

<h3 align="left">Home</h3>
<p>In the home page, the user can see all of his messages or messages directed to him and all public messages of the users he is following or has a mutual connection with. If the connection with a member is lost, either by unfollowing or by being dropped by the member, the messages of said member are no longer displayed in the home page. If the user had exchanged messages with that member, they can be accessed in the messages page.</p>

