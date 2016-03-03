<?php 
include 'header.php';
include 'auth.php';
$valid;
//collect user stuff

#get user from session
$user = $_SESSION['user'];
$db = pg_connect('host=localhost dbname=ssd user=sig password=ssd')
or die("Can't connect to database".pg_last_error());

if (!pg_prepare($db,'login_select', 'SELECT * FROM piddle WHERE username = $1')) 
{
	die("Can't prepare" . pg_last_error());
}

if (!pg_execute($db, 'login_select', array($user))) 
{
	die("Can't result" . pg_last_error());
}
$result = pg_execute($db, 'login_select', array($user));
#echo "<div class=\"alert alert-danger\">".$result."</div>";
$row = pg_fetch_assoc($result);
$username = $row['username'];
$email = $row['email'];
$imagepath = $row['imagepath'];
//end of populating form
if (strlen($imagepath) < 5)
{
	$imagepath = "../media/default.jpeg";
}
echo "<br><br><br><br>";
if ((!empty($_POST))) //small check to ensure request was post, hopefully indicating form data
{/**
	$valid = 0;
	//collect posted data & validate
	//username		
	$username = trim($_POST["username"]);
	$username = stripSpecial($username);
	if (!preg_match("/^[a-zA-Z]{3,20}$/",$username))
	{
		echo "<div class=\"alert alert-danger\">Username must be between 2-20 characters</div>";
		$valid = 1;
	}
	//password
	
	$password = trim($_POST["password"]);
	$passwordConf = trim($_POST["passwordConf"]);
	if (empty($password) || empty($passwordConf)) 
	{
		echo "<div class=\"alert alert-danger\">Password can not be empty.</div>";
		$valid = 1; 
	}
	
	if (strcmp($newpassword,$newConf) != 0)
	{
		echo "<div class=\"alert alert-danger\">Passwords must match.</div>";
		$valid = 1; 
	}
	* */
	
	
	//uploads
	//if (!file_exists($_FILES["avatar"]["name"]))
	//{
	$location = "/var/uploads/";
	$newname = bin2hex(openssl_random_pseudo_bytes(4));
	#echo "THIS IS name: ".$newname;
	
	$location .= $newname.".jpg";
	$tmp = $_FILES["filename"]["name"];
	if ($_FILES['filename']['size'] > 100000)
	{
		echo "<div class=\"alert alert-danger\">Too many bytes. Try 100kb or less </div>";
    }
    else
    {
		move_uploaded_file($_FILES["filename"]["tmp_name"],$location);
		$ret;
		system( "exiftool -overwrite_original -all= ".$location, $ret);
		if ($ret != 0)
		{
			system( "rm ".$location);
		}
	}

	////////////////////////////////db do
	if(false)
	{	
		//update
	}
		
}

?>
<div class="container">
	<div class="starter-template">
		
		<p class="lead">Your Profile</p>
	</div>
	<div align="center" class="panel panel-primary">
		<div align="left" class="panel-body"><strong ">User Profile<strong></div>
			
			<table class="table-condensed"><tr><td>
				<form class="registration-form" role="form" action="myprofile.php" method="POST" enctype="multipart/form-data">
					<div class="form-group">
					<?php echo "Hi ".$_SESSION['user'].", this is your profile.";?>
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

