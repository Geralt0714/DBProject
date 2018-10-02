<?php
require 'function.inc';

if (!$connection = @ mysqli_connect("127.0.0.1", "geralt", "","project"))
  die("Cannot connect");

session_start();
$USERNAME = $_SESSION["loginUsername"];
$USERTYPE = $_SESSION['type'];
if (!isset($_SESSION["loginUsername"])||empty($_SESSION["loginUsername"])){
  echo "<h1>You are hijacking session! Log out!</h1>";
  echo "<a href=\"http://localhost:8080/logout.php\">Logout</a>";
  exit;
}
if ($_SESSION[type]!='Stu'){
	echo "<h3>User Type Error</h3>";
	echo "<a href=\"http://localhost:8080/logout.html\">GO BACK</a>";
}
$sd = $_GET['userid'];
$action = $_GET['action'];

// first line
showheader($connection, $USERNAME,$USERTYPE);

// response to user's action
if (isset($sd) and isset($action)){
	$query = "select * from Invitation where sd = ". $sd ." and rcv = ". $USERNAME . " and status = 'Awaiting'"; 
	if (!$result = @ mysqli_query ($connection,$query))
	{  echo "Search query error";
	echo $query;
	echo "<a href=\"http://localhost:8080/login.html\">GO BACK</a>";
	exit;}
	$count = mysqli_num_rows($result);
	if ($count<1){
		echo "<h3>Action Error</h3>";
		}
	else{
		// manage invitation table
		if ($action==1){
			$query = "update Invitation set status = 'Accepted' where sd = ". $sd ." and rcv = ". $USERNAME . " and status = 'Awaiting'";
		}
		else{
			$query = "update Invitation set status = 'Declined' where sd = ". $sd ." and rcv = ". $USERNAME . " and status = 'Awaiting'";		
		}
		if (!$result = @ mysqli_query ($connection,$query))
			{ echo "Search query error";
			echo $query;
			echo "<a href=\"http://localhost:8080/login.html\">GO BACK</a>";
			exit;}

		// manage friend table
		if ($action==1){
			$query = "insert into friend values ('". $sd . "','" . $USERNAME . "')";
			if (!$result = @ mysqli_query ($connection,$query))
				{ echo "Search query error";
				echo $query;
				echo "<a href=\"http://localhost:8080/login.html\">GO BACK</a>";
				exit;}
			$query = "insert into friend values ('". $USERNAME . "','" . $sd . "')";
			if (!$result = @ mysqli_query ($connection,$query))
				{ echo "Search query error";
				echo $query;
				echo "<a href=\"http://localhost:8080/login.html\">GO BACK</a>";
				exit;}
		}
	}
}






// Friend Requests List
echo "<h3> Friend Invitations List</h3>";
 //Received Invitation list 
echo "<h4> Invitation Received List</h4>";
$query = "select sname,sid,itime,status from Invitation natural join student where rcv = ". $USERNAME . " and Invitation.sd = student.sid order by itime DESC";
if (!$result = @ mysqli_query ($connection,$query))
  	{  echo "Search query error";
	echo $query;
    echo "<a href=\"http://localhost:8080/login.html\">GO BACK</a>";
    exit;
	}
$count = mysqli_num_rows($result);
if ($count==0){
  echo "No Invitation Received Yet";
}
else{    // show all result
    echo "<table>";
    echo "<tr><td>Name</td><td>Request time</td><td>Decision</td><td>Profile</td><td>Action</td></tr>";
    while($row = mysqli_fetch_array($result))
        {
          echo "<tr>";
          $templink = "http://localhost:8080/people.php?userid=" . $row['sid'];
          $peoplebutton = "<a href=" . $templink . ">See His/Her Information!</a>";
          echo "<td>" . $row['sname'] . "</td>";
          echo "<td>" . $row['itime'] . "</td>";
          echo "<td>" . $row['status'] . "</td>";
          echo "<td>" . $peoplebutton . "</td>";
          echo "<td>";
          if ($row['status']=='Awaiting'){
          	$templink = "http://localhost:8080/friend.php?userid=" . $row['sid']. "&action=1";
          	$accbutton = "<a href=" . $templink . ">Accept</a>";
          	$templink = "http://localhost:8080/friend.php?userid=" . $row['sid']. "&action=2";
          	$decbutton = "<a href=" . $templink . ">Decline</a>";
          	echo "<table>";
            echo "<tr><td>". $accbutton."</td>";
          	echo "<td>".$decbutton."</td><tr>";
            echo"</table>";
          }
          echo "</td></tr>";
          }
    echo "</table>";
    }


  // Invitation sent list
echo "<h4> Invitation Sent List</h4>";

$query = "select sname,sid,itime,status from Invitation natural join student where sd = ". $USERNAME . " and Invitation.rcv = student.sid order by itime DESC";
if (!$result = @ mysqli_query ($connection,$query))
    {  echo "Search query error";
  echo $query;
    echo "<a href=\"http://localhost:8080/login.html\">GO BACK</a>";
    exit;
  }
$count = mysqli_num_rows($result);
if ($count==0){
  echo "No Invitation Sent Yet";
}
else{    // show all result
    echo "<table>";
    echo "<tr><td>Name</td><td>Request time</td><td>Decision</td><td>Profile</td></tr>";
    while($row = mysqli_fetch_array($result))
        {
          echo "<tr>";
          $templink = "http://localhost:8080/people.php?userid=" . $row['sid'];
          $peoplebutton = "<a href=" . $templink . ">See His/Her Information!</a>";
          echo "<td>" . $row['sname'] . "</td>";
          echo "<td>" . $row['itime'] . "</td>";
          echo "<td>" . $row['status'] . "</td>";
          echo "<td>" . $peoplebutton . "</td>";
          echo "</tr>";
        }
    echo "</table>";
    }


// Friend  List
echo "<h3> Friend List</h3>";
$query = "select sname,sid from friend natural join student where fid = ". $USERNAME . " and friend.sid = student.sid order by sname";
if (!$result = @ mysqli_query ($connection,$query))
  	{  echo "Search query error";
	echo $query;
    echo "<a href=\"http://localhost:8080/login.html\">GO BACK</a>";
    exit;
	}
  	$count = mysqli_num_rows($result);
  	if ($count==0){
  		echo "<h4>You do not have friends yet</h4>";
  	}
    // show all result
    else{echo "<table>";
    echo "<tr><td>Name</td><td>Profile</td><td>Message</td></tr>";
    while($row = mysqli_fetch_array($result))
        {
          echo "<tr>";
          $templink = "http://localhost:8080/people.php?userid=" . $row['sid'];
          $profilebutton = "<a href=" . $templink . ">See His/Her Information!</a>";
          $templink = "http://localhost:8080/MesHis.php?userid=" . $row['sid'];
          $messagebutton = "<a href=" . $templink . ">Send a Message!</a>";

          echo "<td>" . $row['sname'] . "</td>";
          echo "<td>" . $profilebutton . "</td>";
          echo "<td>" . $messagebutton . "</td>";
          echo "</tr>";
          }
    echo "</table>";
    }


showfooter($USERTYPE);

?>