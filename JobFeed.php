<?php
require 'function.inc';

if (!$connection = @ mysqli_connect("127.0.0.1", "geralt", "","project"))
  die("Cannot connect");

session_start();
if (!isset($_SESSION["loginUsername"])||empty($_SESSION["loginUsername"])||($_SESSION['type']=='Com')){
  echo "<h1>You are hijacking session! Log out!</h1>";
  echo "<a href=\"http://localhost:8080/logout.php\">Logout</a>";
  exit;
}
$USERNAME = $_SESSION["loginUsername"];
$USERTYPE = $_SESSION["type"];
$unfo=$_SESSION['unfo'];
// first line
showheader($connection, $USERNAME,$USERTYPE);

echo "<h3>Job Feed</h3>";
$query = "select * from following natural join job natural join company where posttype='Public' and sid = ".$USERNAME." order by posttime DESC";
if (!$result = @ mysqli_query ($connection,$query))
  {  echo "Search query error";
	echo $query;
    echo "<a href=\"http://localhost:8080/login.html\">GO BACK</a>";
    exit;}
if(mysqli_num_rows($result)==0){
	echo "<h4>There is no Job Feed yet!";
}
else{
	//show result
	echo "<table>";
    echo "<tr><td>Job Id</td><td>Title</td><td>Location</td><td>Employer</td><td>Post Time</td><td>Details</td></tr>";
    while($row = mysqli_fetch_array($result))
        {
          echo "<tr>";
          $templink = "http://localhost:8080/job.php?jobid=" . $row['jid'];
          $joblink = "<a href=" . $templink . ">Detail</a>";
          echo "<td>" . $row['jid'] . "</td>";
          echo "<td>" . $row['title'] . "</td>";
          echo "<td>" . $row['jlocation'] . "</td>";
          echo "<td>" . $row['cname'] . "</td>";
          echo "<td>" . $row['posttime'] . "</td>";
          echo "<td>" . $joblink . "</td>";
          echo "</tr>";
          }
    echo "</table>";
	}
echo "<h3>You are following these companies</h3>";
$query = "select cid,cname from following natural join company where sid=".$USERNAME;
if (!$result = @ mysqli_query ($connection,$query))
  {  echo "Search query error";
	echo $query;
    echo "<a href=\"http://localhost:8080/login.html\">GO BACK</a>";
    exit;}
if(mysqli_num_rows($result)==0){
	echo "<h4>You are not following any companies yet!";
}
else{
	//show result
	echo "<table>";
    echo "<tr><td>Company Name</td><td>Company Profile</td></tr>";
    while($row = mysqli_fetch_array($result))
        {
          echo "<tr>";
          $templink = "http://localhost:8080/company.php?comid=" . $row['cid'];
          $joblink = "<a href=" . $templink . ">Detail</a>";
          echo "<td>" . $row['cname'] . "</td>";
          echo "<td>" . $joblink . "</td>";
          echo "</tr>";
          }
    echo "</table>";
	}





showfooter($USERTYPE);
?>