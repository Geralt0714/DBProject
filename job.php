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
$jobid = $_GET['jobid'];
//company user url variable
$newjob = $_GET['newjob'];
$accid = $_GET['accid'];
$decid = $_GET['decid'];
//student user url variable
$keyword = mysqlclean($_GET, "keyword", 25, $connection);
$apply = $_GET['apply'];
$shareid = $_GET['shareid'];


// first line
showheader($connection, $USERNAME,$USERTYPE);

// have to bring a jobid or keyword to see job detail
if(empty($jobid)and empty($keyword)){
	showfooter($USERTYPE);
	exit;
}

//check if their is one job with that id
if(!empty($jobid)){
	$query = "select * from job where jid = ".$jobid;
	if (!$result = @ mysqli_query ($connection,$query))
	  	{echo "insert query error";
		echo $query;
	    echo "<a href=\"http://localhost:8080/login.html\">GO BACK</a>";
	    exit;}
	if(mysqli_num_rows($result)==0){
		echo "<h3>There is no such job with that jobid</h3>";
		echo $query;
		showfooter($USERTYPE);
		exit;}




	if ($USERTYPE=='Stu'){


		// manage share
		if((!empty($shareid))and checkfriend($USERNAME,$shareid,$connection)){
			$query = "insert into JobNotification (`jid`,`sid`,`source`) values('".$jobid."','".$shareid."','Friend')";
			if (!$result = @ mysqli_query ($connection,$query))
      			{  echo "Search query error";
      			echo $query;
        		echo "<a href=\"http://localhost:8080/login.html\">GO BACK</a>";
        		exit;}
		}




		// manage application
		if (!empty($apply)){
			$query = "select * from application where jid = ".$jobid." and sid = ".$USERNAME;
			if (!$result = @ mysqli_query ($connection,$query))
			  	{echo "search query error";
				echo $query;
			    echo "<a href=\"http://localhost:8080/login.html\">GO BACK</a>";
			    exit;}			
			if(mysqli_num_rows($result)==0){
				$query = "insert into application (`jid`,`sid`) values('".$jobid."','".$USERNAME."')";
				if (!$result = @ mysqli_query ($connection,$query))
			  		{echo "insert query error";
					echo $query;
			    	echo "<a href=\"http://localhost:8080/login.html\">GO BACK</a>";
			    	exit;}		
			}
		}

		//check if this student have applied this job
		{
			$query = "select * from application where jid =".$jobid." and sid = ".$USERNAME;
			if (!$result = @ mysqli_query ($connection,$query))
		  		{echo "insert query error";
				echo $query;
		    	echo "<a href=\"http://localhost:8080/login.html\">GO BACK</a>";
		    	exit;}
		    if(mysqli_num_rows($result)==1){
		    	$row = mysqli_fetch_array($result);
		    	$appresult = $row['status'];
		    }
		    else{
		    	//construct a link for later use
	          	$templink = "http://localhost:8080/job.php?jobid=".$jobid."&apply=1";
	          	$appresult = "<a href=" . $templink . ">Apply</a>";
		    }
		}



		// prepare page for student
		$query = "select cid,jid,jlocation,title,salary,major,academicbar,posttime,descrp,cname from job natural join Company where jid = ".$jobid;
		if (!$result = @ mysqli_query ($connection,$query))
		  	{echo "insert query error";
			echo $query;
		    echo "<a href=\"http://localhost:8080/login.html\">GO BACK</a>";
		    exit;}
		if(mysqli_num_rows($result)==0){
			echo "<h3>There is no such job with that jobid</h3>";
			echo $query;
			showfooter($USERTYPE);
			exit;}
		else{
			$row = mysqli_fetch_array($result);
		}
		Echo "<h3>Job Detail</h3>";
		echo "<table>";
		echo "<tr><td>Job Id</td><td>Company</td><td>Job Location</td><td>Salary</td><td>Degree Requied</td><td>Post Time</td><td>Description</td><td>Apply</td></tr>";

			//show result 

        {
	        echo "<tr>";
			{
		        echo "<td>".$row['jid']."</td>";
		        echo "<td>".$row['cname']."</td>";
		        echo "<td>" . $row['jlocation'] . "</td>";
		        if(!empty($row['salary'])){echo "<td>".$row['salary']."</td>";}
		        else{echo "<td>Contact Company</td>";}
		        echo "<td>".$row['academicbar']."</td>";
		        echo "<td>".$row['posttime']."</td>";
		        echo "<td>".$row['descrp']."</td>";
		        echo "<td>".$appresult."</td>";
	        }
	        echo "</tr>";
	        echo "</table>";
        }

        	//share to a friend from list

        {
        	echo "<h3>Share To A Friend!</h3>";
    		$query = "select * from friend natural join student where friend.fid = ".$USERNAME." and friend.sid=student.sid";
    		if (!$result = @ mysqli_query ($connection,$query))
      			{  echo "Search query error";
      			echo $query;
        		echo "<a href=\"http://localhost:8080/login.html\">GO BACK</a>";
        		exit;}
        	if(mysqli_num_rows($result)==0){
        		Echo "<h4>You do not have any friends yet</h4>";
        	}
        	else{
        			echo "<table>";
        			echo "<tr><td>Friend Name</td><td>Share Button</td></tr>";
        		    while($row = mysqli_fetch_array($result))
				        {
				          echo "<tr>";
				          $templink = "http://localhost:8080/job.php?jobid=" . $jobid.
				                      "&shareid=" . $row['sid'];
				          $rentlink = "<a href=" . $templink . ">Share!</a>";
				          echo "<td>" . $row['sname'] . "</td>";
				          echo "<td>" . $rentlink . "</td>";
				          echo "</tr>";
				          }
				    echo "</table>";
		        }
        }
    showfooter($USERTYPE);
    exit;
	}
	else{
		// prepare page for company
		// only show job info post by himself
		if(!checkjobposter($USERNAME,$jobid,$connection)){
			showfooter($USERTYPE);
			exit;
		}
		else{
			//manage new job post creation notification
			if(!empty($newjob)){
				echo "<h4>New Job Post Created!</h4>";
			}

			// manage response for applicant;
			if(!empty($accid)||!empty($decid)){
				//check him/her did applied for this job
				if(empty($accid)){$respondid=$decid;}
				else{$respondid=$accid;}
				$query = "select * from application where jid=".$jobid." and sid=".$respondid;
				if (!$result = @ mysqli_query ($connection,$query))
				  	{echo "search query error";
					echo $query;
				    echo "<a href=\"http://localhost:8080/login.html\">GO BACK</a>";
				    exit;}
				if(mysqli_num_rows($result)!=0){
					// update response to applicant
					if(!empty($accid)){
						$query = "update application set status='selected' where jid=".$jobid." and sid=".$accid;
					}
					else{
						$query = "update application set status='rejected' where jid=".$jobid." and sid=".$decid;						
					}
					if (!$result = @ mysqli_query ($connection,$query))
					  	{echo "update response query error";
						echo $query;}
				}
			}

			// get job info
			$query = "select * from job natural join Company where jid = ".$jobid;
			if (!$result = @ mysqli_query ($connection,$query))
			  	{echo "insert query error";
				echo $query;
			    echo "<a href=\"http://localhost:8080/login.html\">GO BACK</a>";
			    exit;}
			if(mysqli_num_rows($result)==0){
				echo "<h3>There is no such job with that jobid</h3>";
				echo $query;
				showfooter($USERTYPE);
				exit;}
			else{
				$row = mysqli_fetch_array($result);
			}
				//show result 
			{
			Echo "<h3>Job Detail</h3>";
			echo "<table>";
			echo "<tr><td>Job Id</td><td>Company</td><td>Job Location</td><td>Salary</td><td>Degree Requied</td><td>Post Time</td><td>Visibility</td><td>Description</td></tr>";

		        echo "<tr>";
				{
			        echo "<td>".$row['jid']."</td>";
			        echo "<td>".$row['cname']."</td>";
			        echo "<td>" . $row['jlocation'] . "</td>";
			        if(!empty($row['salary'])){echo "<td>".$row['salary']."</td>";}
			        else{echo "<td>Contact Company</td>";}
			        echo "<td>".$row['academicbar']."</td>";
			        echo "<td>".$row['posttime']."</td>";
			        echo "<td>".$row['posttype']."</td>";
			        echo "<td>".$row['descrp']."</td>";
		        }
		        echo "</tr>";
		        echo "</table>";
	        }
	        // show who has applied for this job
	        {
	        	Echo "<h3>Applicant list</h3>";
	        	$query = "select sname,sid,status,apptime from application natural join student where jid =".$jobid." order by apptime DESC";
				if (!$result = @ mysqli_query ($connection,$query))
				  	{echo "search query error";
					echo $query;
				    echo "<a href=\"http://localhost:8080/login.html\">GO BACK</a>";
				    exit;}
				if(mysqli_num_rows($result)==0){
					echo "<h4>There is no applicant yet</h4>";}
				else{
					//show list
					echo "<table>";
					echo "<tr><td>Applicant Name</td><td>Applicant Profile</td><td>Application Time</td><td>Status</td><td>Action</td></tr>";
				    while($row = mysqli_fetch_array($result))
				        {
				          	echo "<tr>";
				          	echo "<td>" . $row['sname'] . "</td>";
				          	$templink = "http://localhost:8080/people.php?userid=" . $row['sid'];
				          	$prolink = "<a href=" . $templink . ">Profile!</a>";
				          	echo "<td>" . $prolink . "</td>";
				          	echo "<td>" . $row['status'] . "</td>";
				          	echo "<td>" . $row['apptime'] . "</td>";
				          	if($row['status']=='received'){
				          		echo "<td><table>";
				          		$templink = "http://localhost:8080/job.php?jobid=" . $jobid."&accid=".$row['sid'];
				          		$acclink = "<a href=" . $templink . ">Select</a>";
				          		echo "<tr><td>" . $acclink . "</td>";				          		
				          		$templink = "http://localhost:8080/job.php?jobid=" . $jobid."&decid=".$row['sid'];
				          		$declink = "<a href=" . $templink . ">Decline</a>";
				          		echo "<td>" . $declink . "</td></tr></table></td>";
				          	}
				          	else{
				          		echo "<td>None</td>";
				          	}
				          	echo "</tr>";
				        }
				    echo "</table>";
				}
	        }
	        // manage special notification from post method
	        if(!empty($_POST)){
	        	if(isset($_POST['Degree']))$degree = implode(",",$_POST['Degree']);
	        	$major = mysqlclean($_POST, "major", 25, $connection);
	        	$uni = mysqlclean($_POST, "uni", 25, $connection);
	        	$gpa = mysqlclean($_POST, "gpa", 25, $connection);
	        	$keyword = mysqlclean($_POST, "keyword", 25, $connection);
	        	// test 
	        	//echo $degree;
	        	//echo $major;
	        	//echo $university;
	        	//echo $gpa;
	        	//echo $keyword;
	        	if(!(empty($degree) and empty($major) and empty($uni)and empty($gpa)and empty($keyword))){
	        		//construct query
	        		$query = "select sid from student where ";
	        		if(!empty($major)){
	        			$part['1'] = " major like \"%".$major."%\" ";
	        		}
	        		if(!empty($uni)){
	        			$part['2'] = " university like \"%".$uni."%\" ";
	        		}
	        		if(!empty($gpa)){
	        			$part['3'] = " gpa >".$gpa." ";
	        		}
	        		if(!empty($keyword)){
	        			$part['4'] = "resume like \"%".$major."%\" ";
	        		}
	        		if(!empty($degree)){
	        			$part['5'] = " LOCATE(degree,\"".$degree."\")>0";
	        		}
	        		$query = $query.implode("and", $part);

	        		if (!$result = @ mysqli_query ($connection,$query))
					  	{echo "search query error";
						echo $query;
					    echo "<a href=\"http://localhost:8080/login.html\">GO BACK</a>";
					    exit;}
					$count = mysqli_num_rows($result);
					if ($count==0){
						echo "<h4>Sorry. There is no student that meets your requirement.</h4>";
					}
					else{
						while($row = mysqli_fetch_array($result))
							{
								$secquery = "insert into JobNotification (`jid`,`sid`,`source`)values('".$jobid."','".$row['sid']."','Company')";
								if (!$res = @ mysqli_query ($connection,$secquery))
								  	{echo "insert query error";
									echo $query;
								    echo "<a href=\"http://localhost:8080/login.html\">GO BACK</a>";
								    exit;}
							}
						echo "<h4>You have sent special job notifications to ".$count ." student(s) successfully!</h4>";
					}
	        	}
	        }


	        // show list of people who were sent job notifications
	        {
	        	echo "<h3>The Job Notification has been sent to students below</h3>";
	        	$query = "select * from jobnotification natural join student where jid=".$jobid." and source='Company' order by mtime DESC";
        		if (!$result = @ mysqli_query ($connection,$query))
				  	{echo "search query error";
					echo $query;
				    echo "<a href=\"http://localhost:8080/login.html\">GO BACK</a>";
				    exit;}
				$count = mysqli_num_rows($result);
				if($count==0){
					echo "<h4>No students have received notification about this job yet</h4>";
				}
				else{
					echo "<table>";
					echo "<tr><td>Name</td><td>University</td><td>Degree</td><td>Major</td><td>Notification Time</td><td>Profile link</td></tr>";
				    while($row = mysqli_fetch_array($result))
			        {
			          echo "<tr>";
			          $templink = "http://localhost:8080/people.php?userid=" . $row['sid'];
			          $peoplelink = "<a href=" . $templink . ">Profile</a>";
			          echo "<td>" . $row['sname'] . "</td>";
			          echo "<td>" . $row['university'] . "</td>";
			          echo "<td>" . $row['degree'] . "</td>";
			          echo "<td>" . $row['major'] . "</td>";
			          echo "<td>" . $row['mtime'] . "</td>";
			          echo "<td>" . $peoplelink . "</td>";
			          echo "</tr>";
			          }
				    echo "</table>";
				}


	        }





		}
	}
}

else{
	echo "<h3>Bad link</h3>";
	showfooter($USERTYPE);
	exit;
}
echo "<h3>Select Students to send special job notification to them</h3>";
echo "<form method=\"POST\" action=\"http://localhost:8080/job.php?jobid=".$jobid."\">";
?>


<table>
  	<tr>
    	<td>Required Degree:</td>
    	<td> 
  			<input type="checkbox" name="Degree[]" value="UGrd" /> Bachelor's Degree
  			<input type="checkbox" name="Degree[]" value="Grad" /> Master's Degree
  			<input type="checkbox" name="Degree[]" value="PhD" /> Doctor's Degree
  		</td>
  	</tr>
  	<tr>
    	<td>Required Major:</td>
    	<td><input type="text" size="20" name="major"></td>
  	</tr>  	
  	<tr>
    	<td>Required University:</td>
    	<td><input type="text" size="20" name="uni"></td>
  	</tr>
  	<tr>
   		<td>Minimal GPA:</td>
    	<td><input type="text" size="20" name="gpa"></td>
  	</tr>
  	<tr>
   		<td>Resume Keyword:</td>
    	<td><input type="text" size="20" name="keyword"></td>
  	</tr>

</table>
<p><input type="submit" value="Send Job Notification!">
</form>



<?php
showfooter($USERTYPE);

?>