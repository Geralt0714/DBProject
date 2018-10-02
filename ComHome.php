<?php
require 'function.inc';

if (!$connection = @ mysqli_connect("127.0.0.1", "geralt", "","project"))
  die("Cannot connect");

session_start();
if (!isset($_SESSION["loginUsername"])||empty($_SESSION["loginUsername"])||($USERTYPE=='Stu')){
  echo "<h1>You are hijacking session! Log out!</h1>";
  echo "<a href=\"http://localhost:8080/logout.php\">Logout</a>";
  exit;
}
$USERNAME = $_SESSION["loginUsername"];
$USERTYPE = $_SESSION["type"];
$newuser = $_GET['newuser'];
$newpost = $_GET['newpost'];


// first line
showheader($connection, $USERNAME,$USERTYPE);


// new user welcome
if(!empty($newuser)){
  echo "<h2>Thank you for joining us. Your Login Number is ". $USERNAME ."</h2>";
}

// new job post alert
if(!empty($newpost)){
  echo "<h2>Job Post Created</h2>";
}

// show job list created by this company
{
	$query = "select * from job where cid = ". $USERNAME ." order by posttime DESC";
	if (!$result = @ mysqli_query ($connection,$query))
	  {  echo "Search query error";
		echo $query;
	    echo "<a href=\"http://localhost:8080/login.html\">GO BACK</a>";
	    exit;}
	$count = mysqli_num_rows($result);
	echo "<h3>Post Job list</h3>";
	if ($count==0){
		echo "<h4>You have not post a job yet</h4>";
	}
	else{
		echo "<table>";
		echo "<tr><td>Job Id</td><td>Job Title</td><td>Job Location</td><td>Post Time</td><td>Detail Link</td></tr>";
		while($row = mysqli_fetch_array($result))
	        {
	          echo "<tr>";
	          $templink = "http://localhost:8080/job.php?jobid=" . $row['jid'];
	          $rentlink = "<a href=" . $templink . ">Details</a>";
	          echo "<td>" . $row['jid'] . "</td>";
	          echo "<td>" . $row['title'] . "</td>";
	          echo "<td>" . $row['jlocation'] . "</td>";
	          echo "<td>" . $row['posttime'] . "</td>";
	          echo "<td>" . $rentlink . "</td>";
	          echo "</tr>";
	        }
	    echo "</table>";
	}

// create-job-post link
{	  
	$templink = "http://localhost:8080/newjob.php";
	$clicklink = "<a href=" . $templink . ">Click here to create new job post</a>";
	echo $clicklink;
}
}









showfooter($USERTYPE);
?>