<?php


function authenticateUser($connection, $username,$password,$type)
{
  // Test the username and password parameters
  if (!isset($username))
    return false;

  // Create a digest of the password collected from
  // the challenge

  // Formulate the SQL find the user
  if ($type=='Stu'){$query = "SELECT * FROM Student WHERE sid = '$username' and password = '$password'";}
  else {$query = "SELECT * FROM Company WHERE cid = '$username' and password = ". $password;}

  // Execute the query
  if (!$result = @ mysqli_query ($connection,$query))
    {echo "login query error";
      echo $query;
    };
  
  $count = mysqli_num_rows($result);
  // exactly one row? then we have found the user
  if ($count== 1)
    return True;
  else
    {echo "Invalid Login Info!";
    echo $query;
    return False;}
}

function mysqlclean($array, $index, $maxlength, $connection)
   {
     if (isset($array["{$index}"]))
     {
        $input = substr($array["{$index}"], 0, $maxlength);
        $input = mysqli_real_escape_string($connection, $input);
        return ($input);
     }
     return NULL;
   }


function showfooter($type)
  {
    if($type=='Stu'){
      echo "<h3>Fast Link</h3>";
      echo "<table>";
      echo "<tr><td>";
      echo "<a href=\"http://localhost:8080/StuHome.php\">Home</a></td><td>";
      echo "<a href=\"http://localhost:8080/Friend.php\">Friends    </a></td><td>";
      echo "<a href=\"http://localhost:8080/Message.php\">Message     </a></td><td>";
      echo "<a href=\"http://localhost:8080/JobNot.php\">Job Notification</a></td><td>";
      echo "<a href=\"http://localhost:8080/JobFeed.php\"> Job Feed</a></td><td>";
      echo "<a href=\"http://localhost:8080/people.php?userid=myself\"> My Profile</a></td><td>";
      echo "<a href=\"http://localhost:8080/logout.php\">Logout</a></td></table>";
    }
    else{
      echo "<h3>Fast Link</h3>";
      echo "<table>";
      echo "<tr><td>";
      echo "<a href=\"http://localhost:8080/ComHome.php\">Home</a></td><td>";
      echo "<a href=\"http://localhost:8080/newjob.php\">New Job Post</a></td><td>";
      echo "<a href=\"http://localhost:8080/company.php?comid=myself\">My Company Profile</a></td><td>";
      echo "<a href=\"http://localhost:8080/logout.php\">Logout</a></td></table>";
    }
  }

function showheader($connection,$USERNAME,$type)
  {
    if($type=='Stu'){
      $query = "select sname as name from student where sid = ". $USERNAME;
    }
    else{
      $query = "select cname as name from company where cid = ". $USERNAME;

    }
    if (!$result = @ mysqli_query ($connection,$query))
      {  echo "Search query error";
      echo $query;
        echo "<a href=\"http://localhost:8080/login.html\">GO BACK</a>";
        exit;}
    $row = mysqli_fetch_array($result);
    echo "<h3>Hello! You have logged in as ". $row['name']. "<a href=\"http://localhost:8080/logout.php\">Logout</a></h3>";

  }


function checkfriend($userid_a,$userid_b,$connection)
  {
    $query = "select * from friend where sid =".$userid_a." and fid=".$userid_b;
    if (!$result = @ mysqli_query ($connection,$query))
      {  echo "Search query error";
        echo $query;
        echo "<a href=\"http://localhost:8080/login.html\">GO BACK</a>";
        exit;}
    if (mysqli_num_rows($result)==0){return False;}
    else return True;

  }

function checkjobposter($userid,$jobid,$connection)
{
  $query = "select * from job where jid = ".$jobid." and cid= ".$userid;
  if (!$result = @ mysqli_query ($connection,$query))
      {  echo "Search query error";
        echo $query;
        echo "<a href=\"http://localhost:8080/login.html\">GO BACK</a>";
        exit;}
    if (mysqli_num_rows($result)==0){return False;}
    else return True;
}





?>