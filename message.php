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
  echo "<h1>Message function is not open for company account! Log out!</h1>";
  echo "<a href=\"http://localhost:8080/logout.php\">Logout</a>";
  exit;
}


// first line
showheader($connection, $USERNAME,$USERTYPE);

// New Message notifiaction
$query = "select * from Message where rcv = ". $USERNAME . " and mstatus = 'Sent'";

if (!$result = @ mysqli_query ($connection,$query))
  {  echo "Search query error";
	echo $query;
    echo "<a href=\"http://localhost:8080/login.html\">GO BACK</a>";
    exit;}
$count = mysqli_num_rows($result);

if ($count>=1){
	echo "<h3>You have ". $count ." new unread messages from</h3>";

	//show new message sender list
	$query = "select distinct sname,sid from Message natural join Student where rcv = ". $USERNAME . " and mstatus = 'Sent' and Message.sd = Student.sid";
	if (!$result = @ mysqli_query ($connection,$query))
	  {  echo "Search query error";
		echo $query;
	    echo "<a href=\"http://localhost:8080/login.html\">GO BACK</a>";
	    exit;}
	    //show result
	    echo "<table>";
		while($row = mysqli_fetch_array($result))
	    {
	      echo "<tr>";
	      $templink = "http://localhost:8080/MesHis.php?userid=" . $row['sid'];
	      $Mesbutton = "<a href=" . $templink . ">See Messages</a>";
	      echo "<td>" . $row['sname'] . "</td>";
	      echo "<td>" . $Mesbutton . "</td>";
	      echo "</tr>";
	      }
	     echo "</table>";
}
else{
	echo "<h3>You do not have any new messages</h3>";
}

// choose a friend to see message history with him/her
echo "<h3>Choose a friend to see message history and start chat</h3>";
$query = "select distinct sname,sid from friend natural join Student where fid = ". $USERNAME . " and friend.sid = Student.sid";
if (!$result = @ mysqli_query ($connection,$query))
  {  echo "Search query error";
	echo $query;
    echo "<a href=\"http://localhost:8080/login.html\">GO BACK</a>";
    exit;}
$count = mysqli_num_rows($result);
if ($count<1){
	echo "<h4>You do not have any friends yet.</h4>";
}
else{	//show result
	{
    echo "<table>";
	while($row = mysqli_fetch_array($result))
    {
      echo "<tr>";
      $templink = "http://localhost:8080/MesHis.php?userid=" . $row['sid'];
      $Mesbutton = "<a href=" . $templink . ">See Messages and Chat</a>";
      echo "<td>" . $row['sname'] . "</td>";
      echo "<td>" . $Mesbutton . "</td>";
      echo "</tr>";
    }
     echo "</table>";
	}
}



showfooter($USERTYPE);

?>
