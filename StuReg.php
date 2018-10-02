<?php
require 'function.inc';

if (!$connection = @ mysqli_connect("127.0.0.1", "geralt", "","project"))
  die("Cannot connect");

// Clean the data collected in the <form>
$Username = mysqlclean($_POST, "Username", 20, $connection);
$Password = mysqlclean($_POST, "Password", 15, $connection);
$Degree = mysqlclean($_POST, "Degree", 5, $connection);
$uni = mysqlclean($_POST, "uni", 25, $connection);
$major = mysqlclean($_POST, "Major", 25, $connection);
$gpa = mysqlclean($_POST, "gpa", 25, $connection);
$skill = mysqlclean($_POST, "skill", 25, $connection);
$access = mysqlclean($_POST, "access", 10, $connection);

// input info check
if (empty($Username) or empty($Password) or empty($Degree) or empty($uni) or empty($major) or empty($gpa) or empty($skill)){
	echo "Invalid input information! Please try again!";
	echo "<a href=\"http://localhost:8080/StuReg.html\">GO BACK</a>";
}

// insert process
$query = "INSERT INTO `Student`(`sname`,`password`,`degree`,`university`,`major`,`gpa`,`skill`,`access`) VALUES ('".$Username."','".$Password."','".$Degree."','".$uni."','".$major."','".$gpa."','".$skill."','".$access."')";
if (!$result = @ mysqli_query ($connection,$query))
  {  echo "Insert query error";
    echo $query;
    echo "<a href=\"http://localhost:8080/login.html\">GO BACK</a>";
    exit;}

// get user sid    
$query = "select * from student order by rgtime DESC limit 1";
if (!$result = @ mysqli_query ($connection,$query))
  {  echo "Get sid query error";
  echo $query;
    echo "<a href=\"http://localhost:8080/login.html\">GO BACK</a>";
    exit;}
$row = mysqli_fetch_array($result);
$sid = $row['sid'];
$type = 'Stu';
session_start();
if (authenticateUser($connection, $sid,$Password,$type))
{
  // Register the loginUsername
  $_SESSION["loginUsername"] = $sid;

  // Register the IP address that started this session
  $_SESSION["type"] = $type;
  echo 'Login Success!';
  echo $_SESSION["loginUsername"];
  echo $_SESSION["type"];
  echo '<script>window.location.href = "StuHome.php?newuser=1";</script>';
  exit;
}
else
{
  // The authentication failed: setup a logout message
  $_SESSION["message"] = 
    "Could not connect to the application as '{$loginUsername}'";

  // Relocate to the logout page
  exit;
}

?>
