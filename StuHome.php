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
$newuser = $_GET['newuser'];



// first line
showheader($connection, $USERNAME,$USERTYPE);


// new user welcome
if(!empty($newuser)){
  echo "<h2>Thank you for joining us. Your Login Number is ". $USERNAME ."</h2>";
}

echo "<table>";

// Friend request notifiaction
$query = "select sname,sid from Invitation natural join student where rcv = ". $USERNAME . " and Invitation.sd = student.sid and status = 'Awaiting'";

if (!$result = @ mysqli_query ($connection,$query))
  {  echo "Search query error";
	echo $query;
    echo "<a href=\"http://localhost:8080/login.html\">GO BACK</a>";
    exit;}
  $count = mysqli_num_rows($result);

  {	echo "<tr><td>";
  	echo "You have ". $count ." new friend requests!";
  	echo "</td><td>";
    echo "<a href=\"http://localhost:8080/friend.php\">See All Friends and Friend Requests</a>";
    echo "</td></tr>";
  }



// New Message notifiaction
$query = "select * from Message where rcv = ". $USERNAME . " and mstatus = 'Sent'";

if (!$result = @ mysqli_query ($connection,$query))
  {  echo "Search query error";
	echo $query;
    echo "<a href=\"http://localhost:8080/login.html\">GO BACK</a>";
    exit;}
  $count = mysqli_num_rows($result);

  {echo "<tr><td>";
  	echo "You have ". $count ." new unread messages!";
  	echo "</td><td>";
    echo "<a href=\"http://localhost:8080/message.php\">See Messages</a>";
    echo "</td></tr>";
  }



// New Job notifiaction
$query = "select * from JobNotification where sid = ". $USERNAME . " and status = 'unread'";

if (!$result = @ mysqli_query ($connection,$query))
  {  echo "Search query error";
	   echo $query;
    echo "<a href=\"http://localhost:8080/login.html\">GO BACK</a>";
    exit;}
  $count = mysqli_num_rows($result);

  {echo "<tr><td>";
  	echo "You have ". $count ." new unread Job Notifications!";
  	echo "</td><td>";
    echo "<a href=\"http://localhost:8080/JobNot.php\">See Job Notifications</a>";
    echo "</td></tr>";
  }

echo "</table>";

// job feed link
echo "<a href=\"http://localhost:8080/JobFeed.php\">See Job feed</a>";

// job application status
echo "<h3>Job Application Status</h3>";
$query = "select * from Application natural join job where sid=".$USERNAME." order by apptime DESC";

if (!$result = @ mysqli_query ($connection,$query))
  {  echo "Search query error";
  echo $query;
    echo "<a href=\"http://localhost:8080/login.html\">GO BACK</a>";
    exit;}
$count = mysqli_num_rows($result);
if($count==0){
  echo "<h4>You have not applied to any jobs yet</h4>";
}
else{
  echo "<table>";
  echo "<tr><td>Job Id</td><td>Title</td><td>Location</td><td>Application Time</td><td>Status</td><td>Job Detail</td></tr>";
  while($row = mysqli_fetch_array($result))
    {
      echo "<tr>";
      echo "<td>".$row['jid']."</td>";
      echo "<td>".$row['title']."</td>";
      echo "<td>".$row['jlocation']."</td>";
      echo "<td>".$row['apptime']."</td>";
      echo "<td>".$row['status']."</td>";
      $templink = "http://localhost:8080/job.php?jobid=" . $row['jid'];
      $detaillink = "<a href=" . $templink . ">Detail</a>";    
      echo "<td>".$detaillink."</td>";
      echo "</tr>";
    }
  echo "</table>";
}


?>



<table>
  <tr>
    <td>

      <form method="POST" action="http://localhost:8080/people.php">
      <table>
        <tr>
          <td>
            <h3>Search People</h3>
          </td>
        </tr>
        <tr>
          <td>
            <input type="text" size="15" name="keyword">
          </td>
        </tr>
      </table>
      <p><input type="submit" name='searchpp' value="Search">
        </p>
      </form>

    </td>  
    <td>

      <form method="POST" action="http://localhost:8080/company.php">
      <table>
        <tr>
          <td>
            <h3>Search Companies</h3>
          </td>
        </tr>
        <tr>
          <td>
            <input type="text" size="15" name="keyword">
          </td>
        </tr>
      </table>
      <p><input type="submit" name='searchcm' value="Search">
        </p>
      </form>

    </td>
  </tr>
</table>

<?php






showfooter($USERTYPE);
?>