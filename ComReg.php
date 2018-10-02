<?php
require 'function.inc';

if (!$connection = @ mysqli_connect("127.0.0.1", "geralt", "","project"))
  die("Cannot connect");

// Clean the data collected in the <form>
$comname = mysqlclean($_POST, "comname", 20, $connection);
$Password = mysqlclean($_POST, "Password", 15, $connection);
$loca = mysqlclean($_POST, "location", 20, $connection);
$indu = mysqlclean($_POST, "industry", 25, $connection);

// input info check
if (empty($comname) or empty($Password) or empty($loca) or empty($indu)){
  echo "Invalid input information! Please try again!";
  echo "<a href=\"http://localhost:8080/ComReg.html\">GO BACK</a>";
}

// insert process

$query = "INSERT INTO `Company`(`cname`,`password`,`location`,`industry`) VALUES ('".$comname."','".$Password."','".$loca."','".$indu."')";
if (!$result = @ mysqli_query ($connection,$query))
  {  echo "Insert query error";
  echo $query;
    echo "<a href=\"http://localhost:8080/login.html\">GO BACK</a>";
    exit;}

// get user sid    
$query = "select * from company order by rgtime DESC limit 1";
if (!$result = @ mysqli_query ($connection,$query))
  {  echo "Get sid query error";
    echo $query;
    echo "<a href=\"http://localhost:8080/login.html\">GO BACK</a>";
    exit;}
$row = mysqli_fetch_array($result);
$cid = $row['cid'];
$type = 'Com';
session_start();
if (authenticateUser($connection, $cid,$Password,$type))
{
  // Register the loginUsername
  $_SESSION["loginUsername"] = $cid;

  // Register the IP address that started this session
  $_SESSION["type"] = $type;
  echo 'Login Success!';
  echo '<script>window.location.href = "ComHome.php?newuser=1";</script>';

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
