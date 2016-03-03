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
if (strlen($imagepath) < 5)
{
	$imagepath = "../media/default.jpeg";
}

if ((!empty($_POST))) //small check to ensure request was post, hopefully indicating form data
{
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
	$target_dir = "/var/uploads/";
	$newname = base64_encode(openssl_random_pseudo_bytes(8));
	$target_file = $target_dir.basename($_FILES["fileToUpload"]["png"]);
	
	$uploadOk = 1;
	$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
	// Check if image file is a actual image or fake image
	if(isset($_POST["submit"])) {
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if($check !== false) {
	echo "File is an image - " . $check["mime"] . ".";
	$uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
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
				<form class="registration-form" role="form" action="newuser.php" method="POST">
					<div class="form-group">
					<?php echo "Hi ".$_SESSION['user'].", this is your profile.";?>
					</div>
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
					<div align="center">
						<img src="<?php echo $imagepath;?>" alt="This is you?" width="200" height="200" style="border: 5px solid bisque;margin: auto; border-radius:15px;">
					</div>
					<div class="form-group">
						<input type="file" name="avatar" class="form-control" id="avatar">
					</div>
					</br>
					<input type="password" name="password" placeholder="Password..." class="form-control" id="password"required>
					<br>
					<button type="submit" class="btn btn-primary">Submit</button>
				</form></td>
				<td>
					
				</td></tr></table></div>
	</body>
</html>

