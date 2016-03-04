<?php 
include 'header.php';
include 'auth.php';
//collect user stuff

#get user from session
$email = $_SESSION['email'];
$db = pg_connect('host=localhost dbname=ssd user=sig password=ssd')
or die("Can't connect to database".pg_last_error());

if (!pg_prepare($db,'login_select', 'SELECT * FROM piddle WHERE email = $1')) 
{
	die("Can't prepare" . pg_last_error());
}

if (!pg_execute($db, 'login_select', array($email))) 
{
	die("Can't result" . pg_last_error());
}
$result = pg_execute($db, 'login_select', array($email));
$row = pg_fetch_assoc($result);
$username = $row['username'];
$email = $row['email'];
$imagepath = "../uploads/".$row['imagepath'];
$storedpassword = $row['passhash'];
//end of populating form
if (strcmp($imagepath,"../uploads/") == 0)
{
	$imagepath = "../uploads/default.jpeg";
}
$newname = $imagepath;
echo "<br><br><br><br>";

if (!empty($_POST)) //changes submitted
{
	$go = true;
	# check pass
	$password = $_POST["password"];
	if ( password_verify($password.$email, $storedpassword)) 
	{
		#continue
		$newusername = $_POST["username"]; #will be old username if not touched
		if (!preg_match("/^[a-zA-Z]{3,20}$/",$newusername))
		{
			echo "<div class=\"alert alert-danger\">Username must be between 3-20 characters</div>";
			$go = false;
		}
		$newpassword = $_POST["newpassword"]; 
		$newConf = $_POST["newConf"]; #will be old username if not touched
		if (strcmp($newpassword,$newConf) != 0)
		{
			echo "<div class=\"alert alert-danger\">New passwords didn't match. </div>";
			$go = false;
		}
		
		if (strlen($newpassword) < 5 && strlen($newpassword) != 0)
		{
			echo "<div class=\"alert alert-danger\">New password must be at least 5 characters.</div>";
			$go = false;
		}
		else if (strlen($newpassword) == 0 )
		{
			$newpassword = $storedpassword;
		}
		else
		{
			$newpassword =  password_hash($newpassword.$email,PASSWORD_DEFAULT);
		}
		
		if (!empty($_FILES['filename']['name']) && $go == true) #check if file exists
		{
			$location = "/var/uploads/";
			$newname = bin2hex(openssl_random_pseudo_bytes(4)).".jpg";
			$location .= $newname;
			$tmp = $_FILES["filename"]["name"];
			if ($_FILES['filename']['size'] > 100000) # too much file
			{
				echo "<div class=\"alert alert-danger\">Too many bytes. Try 100kb or less </div>";
			}
			else # File is aight
			{
				move_uploaded_file($_FILES["filename"]["tmp_name"],$location);
				$ret=0;
				system( "exiftool -overwrite_original -all= ".$location, $ret);
				$imagepath = "../uploads/".$newname;
				if ($ret != 0) #Something went wrong. DESTROY.
				{
					system("rm ".$location);
				}
			}
		}
	}
	else
	{
		echo "<div class=\"alert alert-danger\">Failed to reauthenticate. </div>";
	}
	if($go == true)
	{	
		$db = pg_connect('host=localhost dbname=ssd user=sig password=ssd');
		if (pg_prepare($db, 'profile_update', 'UPDATE piddle SET username = $1, passhash = $2, imagepath = $3  WHERE email = $4 ')) 
		{
			$insertStatement = pg_execute($db, 'profile_update', array($newusername,$newpassword,$newname,$email));                  
		}
		
	}

}
?>
<div class="container">
	<div class="starter-template">
		
		<p class="lead"><?php echo "Hi ".$row['username'].", this is your profile.";?></p>
	</div>
	<div align="center" class="panel panel-primary">
		<div align="left" class="panel-body"><strong ">User Profile<strong></div>
			
			<table class="table-condensed"><tr><td>
				<form class="registration-form" role="form" action="myprofile.php" method="POST" enctype="multipart/form-data">
					<div class="form-group">
					
					</div>
					<div align="center">
						<img src="<?php echo $imagepath;?>" alt="This is you?" width="200" height="200" style="border: 5px solid bisque;margin: auto; border-radius:15px;">
					</div>
					</br>
					<div class="form-group">
						<input type="text" name="username" value="<?php echo $username;?>" class="form-control" id="username" >
					</div>
					<div class="form-group">
						
					</div>
					<div class="form-group">
						<input type="password" name="newpassword" placeholder="New Password..." class="form-control" id="newpassword">
					</div>
					<div class="form-group">
						<input type="password" name="newConf" placeholder="Retype Password..." class="form-control" id="newConf">
					</div>

					<div class="form-group">
						<input type="file" name="filename" class="form-control" id="filename">
					</div>
					</br>
					<input type="password" name="password" placeholder="Password..." class="form-control" id="password"required>
					<br>
					<button type="submit" class="btn btn-primary"  >Submit</button>
				</form></td>
				<td>
					
				</td></tr></table></div>
	</body>
</html>

