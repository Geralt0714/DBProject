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
$newuser = $_GET['new'];



// first line
showheader($connection, $USERNAME,$USERTYPE);


//check if there is an input
if (!empty($_POST)){
	// somebody is creating a new job post

	// Clean the data collected in the <form>
	$title = mysqlclean($_POST, "title", 20, $connection);
	$location = mysqlclean($_POST, "location", 15, $connection);
	$salary = mysqlclean($_POST, "salary", 25, $connection);
	$major = mysqlclean($_POST, "Major", 25, $connection);
	$desc = mysqlclean($_POST, "Desc", 20, $connection);
	$visi = $_POST['type'];
	$tempbar = $_POST['Degree'];

	if (empty($title)||empty($location)||empty($major)||empty($visi)||empty($desc)){
		print_r($tempbar);
		echo "<h3>You have input invalid info</h3>";
		echo "<a href=\"http://localhost:8080/newjob.php\">Re-input Info</a>";
		exit;
	}
	if (count($tempbar)==0){
		echo "<h3>You must choose a required degree</h3>";
		echo "<a href=\"http://localhost:8080/newjob.php\">Re-input Info</a>";
		exit;
	}
	else{
		$n = count($tempbar);
		if($n==1){
			$bar =$tempbar[0];
		}
		elseif($n==2){
			$bar = $tempbar[0] .",". $tempbar[1];
		}
		else{
			$bar = $tempbar[0].",".$tempbar[1].",".$tempbar[2];			
		}
	}
	// construct query
	$values = "('". $USERNAME ."','". $title ."','". $location."','".$salary."','".$major."','".$bar."','".$desc."','".$visi."')";
	$query ="insert into job (`cid`,`title`,`jlocation`,`salary`,`major`,`academicbar`,`descrp`,`posttype`) values ".$values;

	if (!$result = @ mysqli_query ($connection,$query))
	  {  echo "insert query error";
		echo $query;
	    echo "<a href=\"http://localhost:8080/login.html\">GO BACK</a>";
	    exit;}

	// get jid
	$query = "select jid from job where cid = ".$USERNAME." order by posttime DESC limit 1";
	if (!$result = @ mysqli_query ($connection,$query))
	  {  echo "search query error";
		echo $query;
	    echo "<a href=\"http://localhost:8080/login.html\">GO BACK</a>";
	    exit;}
	$row = mysqli_fetch_row($result);
	$link = "window.location.href = \"job.php?newjob=1&jobid=".$row[0]."\";";

	echo "<script>".$link."</script>";

	showfooter($USERTYPE);
	exit;
}
else{
	// no input, thus show input form
}
?>

<h3>Create New Job Post</h3>
<form method="POST" action="http://localhost:8080/newjob.php">
<table>
  	<tr>
    	<td>Job Title:</td>
    	<td><input type="text" size="20" name="title"></td>
  	</tr>  	
  	<tr>
    	<td>Job Location:</td>
    	<td><input type="text" size="20" name="location"></td>
  	</tr>
  	<tr>
    	<td>Required Degree:</td>
    	<td> 
  			<input type="checkbox" name="Degree[]" value="UGrd" checked/> Bachelor's Degree
  			<input type="checkbox" name="Degree[]" value="Grad" /> Master's Degree
  			<input type="checkbox" name="Degree[]" value="PhD" /> Doctor's Degree
  		</td>
  	</tr>
  	<tr>
    	<td>Salary(leave empty if not want to show)</td>
    	<td><input type="text" size="20" name="salary"></td>
  	</tr>
  	<tr>
    	<td>Required Major:</td>
    	<td><input type="text" size="20" name="Major"></td>
  	</tr>  	
  	<tr>
    	<td>Description:</td>
    	<td><input type="text" size="20" name="Desc"></td>
  	</tr>
  	<tr>
   		<td>Visibility*:</td>
    	<td> 
			<form action="" method="">
  			<input type="radio" name="type" value="Public" checked/> Public
  			<input type="radio" name="type" value="Partial" /> Partial
			</form>
  		</td>
  	</tr>
  	<tr>
  		<td colspan=2>
  		<h5>* Visibility: If choose public, all students that follow you will see it in their feed; Otherwise you need to choose the students and they will be notified in their job notifications.</h5>
  		</td>
  	</tr>

</table>
<p><input type="submit" value="Create Job Post!">
</form>


<?php
showfooter($USERTYPE);
?>
