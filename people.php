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

// first line
showheader($connection, $USERNAME,$USERTYPE);


$userid = $_GET['userid'];
$keyword = mysqlclean($_POST, "keyword", 10, $connection);
$frdinv = $_GET['frdinv'];
if($userid=='myself'){
  $userid = $USERNAME;
}
$switch=$_GET['switch'];



// Process general user look up
if (!empty($userid)){
  // check if there is one 
  $query = "SELECT * FROM student where sid = ". $userid;
  if (!$result = @ mysqli_query ($connection,$query))
      { echo "Search query error";
        echo $query;
        echo "<a href=\"http://localhost:8080/login.html\">GO BACK</a>";
        exit;}
  $count = mysqli_num_rows($result);
  if ($count==0){
      echo "<h4>There is no such user!</h4>";
      showfooter($USERTYPE);
      exit;
  }
  else{
    echo "<h3>Member Profile</h3>";
    echo "<table>";
    echo "<tr><td>Name</td><td>University</td><td>Major</td><td>Degree</td><td>GPA</td><td>Resume</td><td>Action</td></tr>";
    $row = mysqli_fetch_array($result);
      {
        echo "<tr>";
        echo "<td>".$row['sname']."</td>";
        echo "<td>".$row['university']."</td>";
        echo "<td>".$row['major']."</td>";
        echo "<td>".$row['degree']."</td>";
      }
    if ($USERNAME==$userid and $USERTYPE=='Stu'){
        if (!empty($switch)){
          if($row['access']==0){
            $query='update student set access=\'1\' where sid='.$USERNAME;
            $result = @ mysqli_query ($connection,$query);
            echo "Access restriction switched!";

          }
          else{            
            $query='update student set access=\'0\' where sid='.$USERNAME;
            $result = @ mysqli_query ($connection,$query);
            echo "Access restriction switched!";
          }
        }


        echo "<td>".$row['gpa']."</td>";
        echo "<td>".$row['skill']."</td>"; //resume link later
        $templink = "http://localhost:8080/people.php?switch=1&userid=" . $userid;
        $switchbutton = "<a href=" . $templink . ">Switch Access Restriction</a>";
        echo "<td>".$switchbutton."</td>";

    }//check myself
    else{
      if ($USERTYPE=="Stu"){
          // check friends
          $query = "SELECT * FROM friend where sid = ". $userid . " and fid = ". $USERNAME;
          if (!$result = @ mysqli_query ($connection,$query))
            { echo "Search query error";
              echo $query;
              echo "<a href=\"http://localhost:8080/login.html\">GO BACK</a>";
              exit;}
          $count = mysqli_num_rows($result);          
          if ($count ==1){
            $templink = "http://localhost:8080/MesHis.php?userid=" . $userid;
            $Mesbutton = "<a href=" . $templink . ">See Messages and Chat</a>";
            echo "<td>".$row['gpa']."</td>";
            echo "<td>".$row['skill']."</td>"; //resume link later
            echo "<td>".$Mesbutton."</td>";
          }
          else{
            // check invitation
            $query = "SELECT * FROM invitation where status = 'Awaiting' and ((rcv = ". $userid . " and sd = ". $USERNAME.") or (rcv = ". $USERNAME . " and sd = ". $userid."))";
            if (!$result = @ mysqli_query ($connection,$query))
              { echo "Search query error";
                echo $query;
                echo "<a href=\"http://localhost:8080/login.html\">GO BACK</a>";
                exit;}
            $count = mysqli_num_rows($result);     
            if ($count==1){
              // its a user with pending invitation
              if ($row['access']=='0'){
                echo "<td>Hidden</td>";
                echo "<td>Hidden</td>"; //resume link later
              }
              else{
                echo "<td>".$row['gpa']."</td>";
                echo "<td>".$row['skill']."</td>"; //resume link later                
              }
              echo "<td>Friend Invitation Awaiting</td>";
            }  
            else{
              // its a user did not send invitation
              if ($frdinv==1){
                // respond to current user's invitation request
                $query = "INSERT INTO `Invitation`(`sd`,`rcv`,`status`) VALUES ('".$USERNAME."','".$userid."','Awaiting')";
                if (!$result = @ mysqli_query ($connection,$query))
                  { echo "Search query error";
                  echo $query;
                  echo "<a href=\"http://localhost:8080/login.html\">GO BACK</a>";
                  exit;}
                //make sure we did send the friend invitation
                $query = "SELECT * FROM invitation where status = 'Awaiting' and rcv = ". $userid . " and sd = ". $USERNAME;
                if (!$result = @ mysqli_query ($connection,$query))
                  { echo "Search query error";
                    echo $query;
                    echo "<a href=\"http://localhost:8080/login.html\">GO BACK</a>";
                    exit;}
                $count = mysqli_num_rows($result);     
                if ($count==1){
                if ($row['access']=='0'){
                  echo "<td>Hidden</td>";
                  echo "<td>Hidden</td>"; //resume link later
                }
                else{
                  echo "<td>".$row['gpa']."</td>";
                  echo "<td>".$row['skill']."</td>"; //resume link later                
                }                  
                echo "<td>Friend Invitation Sent</td>";
                }  
              }
              else{
                if ($row['access']=='0'){
                  echo "<td>Hidden</td>";
                  echo "<td>Hidden</td>"; //resume link later
                }
                else{
                  echo "<td>".$row['gpa']."</td>";
                  echo "<td>".$row['skill']."</td>"; //resume link later                
                }
                $templink = "http://localhost:8080/people.php?frdinv=1&userid=" . $userid;
                $friendbutton = "<a href=" . $templink . ">Send Friend Invitation </a>";
                echo "<td>".$friendbutton."</td>";
              }
            }
          }
      }
      else{
        // manage gpa and resume for company
        //check if applied for company's job
        $secquery = "select * from application natural join job where sid=".$userid." and cid=".$USERNAME;
        if (!$res = @ mysqli_query ($connection,$secquery))
        { echo "Search query error";
          echo $secquery;
          echo "<a href=\"http://localhost:8080/login.html\">GO BACK</a>";
          exit;}
        $count=mysqli_num_rows($res);
        if ($count>0){
          // this student has applied for company's job
          echo "<td>".$row['gpa']."</td>";
          echo "<td>".$row['skill']."</td>";
          echo "<td></td>";
        }
        else{
          // never applied, so check user access
          if ($row['access']=='0'){
            echo "<td>Hidden</td>";
            echo "<td>Hidden</td>";
            echo "<td></td>"; 
          }
          else{
            echo "1";
            echo "<td>".$row['gpa']."</td>";
            echo "<td>".$row['skill']."</td>";
            echo "<td></td>";            
          }
        }
      }
    }
    echo "</tr>";
    echo "</table>";
  }
  showfooter($USERTYPE);
  exit;
}
?>

<form method="POST" action="http://localhost:8080/people.php">
<table>
  <tr>
    <td>
      <h3>Search People</h3>
    </td>
  </tr>
  <tr>
    <td>
      <input type="text" size="10" name="keyword">
    </td>
  </tr>
</table>
<p><input type="submit" name='searchpp' value="Search">
  </p>
</form>





<?php
// process keyword search
if(!empty($keyword)){
  $query  = "(select * from student where sname like \"%".$keyword."%\") union (select * from student where university like \"%".$keyword."%\") union (select * from student where major like \"%".$keyword."%\")";
    if (!$result = @ mysqli_query ($connection,$query))
      { echo "Search query error";
        echo $query;
        echo "<a href=\"http://localhost:8080/login.html\">GO BACK</a>";
        exit;}
    $count = mysqli_num_rows($result);
    if ($count==0){
      echo "<h4>There is no such user!</h4>";
      showfooter($USERTYPE);
      exit;
    }
    else{
      echo "<h3>Members related to keyword \"".$keyword."\"</h3>";
      echo "<table>";
      echo "<tr><td>Name</td><td>University</td><td>Major</td><td>Profile Link</td></tr>";
      while($row = mysqli_fetch_array($result))
        {
          echo "<tr>";
          echo "<td>".$row['sname']."</td>";
          echo "<td>".$row['university']."</td>";
          echo "<td>".$row['major']."</td>";
          $templink = "http://localhost:8080/people.php?userid=" . $row['sid'];
          $link = "<a href=" . $templink . ">See Profile</a>";
          echo "<td>".$link."</td>";

          echo "</tr>";
          }
      echo "</table>";
      showfooter($USERTYPE);
      exit;
    }
}









showfooter($USERTYPE);
?>
