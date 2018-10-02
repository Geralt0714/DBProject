<?php
require 'function.inc';

if (!$connection = @ mysqli_connect("127.0.0.1", "geralt", "","project"))
  die("Cannot connect");

session_start();
if (!isset($_SESSION["loginUsername"])||empty($_SESSION["loginUsername"])){
  echo "<h1>You are hijacking session! Log out!</h1>";
  echo "<a href=\"http://localhost:8080/logout.php\">Logout</a>";
  exit;
}
$USERNAME = $_SESSION["loginUsername"];
$USERTYPE = $_SESSION["type"];
if ($USERTYPE=='Com'){
  echo "<h1>You are hijacking session! Log out!</h1>";
  echo "<a href=\"http://localhost:8080/logout.php\">Logout</a>";
  exit;
}
$chatid = $_GET['userid'];
$content = mysqlclean($_POST, "messagecontent", 20, $connection);


// first line
showheader($connection, $USERNAME,$USERTYPE);

if(empty($chatid)){
  echo "Please choose a friend to see Message History and chat!";
  echo "<a href=\"http://localhost:8080/Message.php\">GO BACK</a>";
  exit;
}

// check people and friends relation
  // first check if there is a real chatid and get his/her name
$query = "select * from student where sid = ". $chatid;
if (!$result = @ mysqli_query ($connection,$query))
  {  echo "Search query error";
  echo $query;
    echo "<a href=\"http://localhost:8080/login.html\">GO BACK</a>";
    exit;}
$count = mysqli_num_rows($result);
if ($count==0){
  echo "There is no such people with that userid!";
  echo "<a href=\"http://localhost:8080/Message.php\">GO BACK</a>";
  exit;
}
$row = mysqli_fetch_array($result);
$chatname = $row['sname'];

  // then check firend relation

if (!checkfriend($USERNAME,$chatid,$connection)){
  echo "You have to be friends of each other first!";
  echo "<a href=\"http://localhost:8080/Message.php\">GO BACK</a>";
  exit;
}


// send message if there is one
if (!empty($content)){
  $query = "INSERT INTO `Message` (`sd`,`rcv`,`content`,`mstatus`) VALUES('".$USERNAME."','".$chatid."','".$content."','Sent')";
  if (!$result = @ mysqli_query ($connection,$query))
    {  echo "Search query error";
      echo $query;
      echo "<a href=\"http://localhost:8080/login.html\">GO BACK</a>";
      exit;}
}

// get history
$query = "select * from Message where (sd = ". $USERNAME . " and rcv = ". $chatid . ") or (sd = ". $chatid . " and rcv = ". $USERNAME . ") order by mtime DESC";
if (!$result = @ mysqli_query ($connection,$query))
  {  echo "Search query error";
  echo $query;
    echo "<a href=\"http://localhost:8080/login.html\">GO BACK</a>";
    exit;}
$count = mysqli_num_rows($result);

if ($count>0){
  //show history
  echo "<h3>Message History</h3>";
    echo "<table>";
    echo "<tr><td>Sender</td><td>Receiver</td><td colspan=\"2\">Message Content</td><td>Time</td></tr>";
    while($row = mysqli_fetch_array($result))
        {
          echo "<tr>";
          if ($row['sd']==$USERNAME){
          echo "<td>Me</td>";
          echo "<td>".$chatname."</td>";
          echo "<td></td>";
          echo "<td>" . $row['content'] . "</td>";
          echo "<td>" . $row['mtime'] . "</td>";
          }
          else{
          echo "<td>".$chatname."</td>";
          echo "<td>Me</td>";
          echo "<td>" . $row['content'] . "</td>";
          echo "<td></td>";
          echo "<td>" . $row['mtime'] . "</td>";
          }
          echo "</tr>";
          }
    echo "</table>";
}
else{
  echo "<h3>Message History</h3>";
  echo "<h4>You do not have message history with him/her yet!</h4>";
}
// clear all received new message flag

$query = "update message set mstatus = 'Received' where (sd = ". $chatid . " and rcv = ". $USERNAME . ")";
if (!$result = @ mysqli_query ($connection,$query))
  {  echo "Search query error";
    echo $query;
    echo "<a href=\"http://localhost:8080/login.html\">GO BACK</a>";
    exit;}


// message box
    echo   "<h3>Send a Message!</h3>";
    echo "<table><tr><td><h4>Send TO</h4></td>";
    echo "<td>". $chatname. "</td></tr>";

    echo "<tr><td><h4>Content</h4></td><td>";
    echo "<form method=\"POST\" action=\"http://localhost:8080/MesHis.php?userid=".$chatid."\">";
    echo "<input type=\"text\" size=\"20\" name=\"messagecontent\" style=\"height:100px;width:200px;\"><input type=\"submit\" name='stu' value=\"Send\">";
    echo "</form>";
    echo "</td></tr></table>";


showfooter($USERTYPE);

?>
