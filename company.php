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


$comid = $_GET['comid'];
$keyword = mysqlclean($_POST, "keyword", 10, $connection);
$follow = $_GET['follow'];


if ($USERTYPE=='Com'){
    if ($comid=='myself'){
      $comid=$USERNAME;
    }
    else{
      echo "<h4>Can not search company</h4>";
      showfooter($USERTYPE);
      exit;
    }  
}
elseif($comid=='myself'){
  echo "<h3>Illegal Action!</h3>";
  showfooter($USERTYPE);
  exit;
}

// Process general company look up
if (!empty($comid)){
    // check if there is one 
    $query = "SELECT * FROM company where cid = ". $comid;
    if (!$result = @ mysqli_query ($connection,$query))
      { echo "Search query error";
        echo $query;
        echo "<a href=\"http://localhost:8080/login.html\">GO BACK</a>";
        exit;}
    $count = mysqli_num_rows($result);
    if ($count==0){
      echo "<h4>There is no such company!</h4>";
      showfooter($USERTYPE);
      exit;
    }
    else{
      echo "<h3>Company Profile</h3>";
      echo "<table>";
      echo "<tr><td>Name</td><td>Headquater Location</td><td>Industry</td><td>Action</td></tr>";
      $row = mysqli_fetch_array($result);
        {
          echo "<tr>";
          echo "<td>".$row['cname']."</td>";
          echo "<td>".$row['location']."</td>";
          echo "<td>".$row['industry']."</td>";
        }
      if ($USERNAME==$comid and $USERTYPE=="Com"){echo "<td>Myself</td></tr>";}//check myself
      else{// check follows
        $query = "SELECT * FROM following where cid = ". $comid . " and sid = ". $USERNAME;
        if (!$result = @ mysqli_query ($connection,$query))
          { echo "Search query error";
            echo $query;
            echo "<a href=\"http://localhost:8080/login.html\">GO BACK</a>";
            exit;}
        $count = mysqli_num_rows($result);          
        if ($count ==0){
          if(!empty($follow)){
              // manage follow action
            $query = "insert into following (`cid`,`sid`) values ('".$comid."','".$USERNAME."')";
            if (!$result = @ mysqli_query ($connection,$query))
              { echo "Search query error";
              echo $query;
              echo "<a href=\"http://localhost:8080/login.html\">GO BACK</a>";
              exit;}
            echo "<td>Following</td></tr>";
          }
          else{
            $templink = "http://localhost:8080/company.php?comid=".$comid."&follow=1";
            $followbutton = "<a href=" . $templink . ">Follow</a>";
            echo "<td>".$followbutton."</td>";
          }
        }
        else{
          echo "<td>Following</td></tr>";
        }
      }
      echo "</table>";

      // show public job posts of this company

      {
        echo "<h3>Job Post Provided by this company</h3>";
        $query = "select * from job where cid=".$comid." and posttype='Public' order by posttime DESC";
        if (!$result = @ mysqli_query ($connection,$query))
          { echo "Search query error";
          echo $query;
          echo "<a href=\"http://localhost:8080/login.html\">GO BACK</a>";
          exit;}        
        $count = mysqli_num_rows($result);
        if ($count==0){
          echo "<h4>This company has not post any job positions yet</h4>";
        }
        else{
          echo "<table>";
          echo "<tr><td>Job Id</td><td>Title</td><td>Location</td><td>Required Degree</td><td>Post Time</td><td>Detail</td></tr>";
          while($row = mysqli_fetch_array($result))
            {
              echo "<tr>";
              $templink = "http://localhost:8080/job.php?jobid=" . $row['jid'];
              $rentlink = "<a href=" . $templink . ">Detail</a>";
              echo "<td>" . $row['jid'] . "</td>";
              echo "<td>" . $row['title'] . "</td>";
              echo "<td>" . $row['jlocation'] . "</td>";
              echo "<td>" . $row['academicbar'] . "</td>";
              echo "<td>" . $row['posttime'] . "</td>";
              echo "<td>" . $rentlink . "</td>";
              echo "</tr>";
              }
          echo "</table>";

        }

      }









      showfooter($USERTYPE);
      exit;
    }

}
?>

<form method="POST" action="http://localhost:8080/company.php">
<table>
  <tr>
    <td>
      <h3>Search Companies</h3>
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
  $query  = "(select * from company where cname like \"%".$keyword."%\") union (select * from company where location like \"%".$keyword."%\") union (select * from company where industry like \"%".$keyword."%\")";
    if (!$result = @ mysqli_query ($connection,$query))
      { echo "Search query error";
        echo $query;
        echo "<a href=\"http://localhost:8080/login.html\">GO BACK</a>";
        exit;}
    $count = mysqli_num_rows($result);
    if ($count==0){
      echo "<h4>There is no such company with keyword ".$keyword."</h4>";
      showfooter($USERTYPE);
      exit;
    }
    else{
      echo "<h3>Companies related to keyword \"".$keyword."\"</h3>";
      echo "<table>";
      echo "<tr><td>Name</td><td>Headqueater Location</td><td>Industry</td><td>Profile Link</td></tr>";
      while($row = mysqli_fetch_array($result))
        {
          echo "<tr>";
          echo "<td>".$row['cname']."</td>";
          echo "<td>".$row['location']."</td>";
          echo "<td>".$row['industry']."</td>";
          $templink = "http://localhost:8080/company.php?comid=" . $row['cid'];
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
