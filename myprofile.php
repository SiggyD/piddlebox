<?php 
include 'header.php';
include 'auth.php';
$valid;
if ((!empty($_POST))) //small check to ensure request was post, hopefully indicating form data
{
	$valid = 0;
	//collect posted data & validate
	//username		
	$username = trim($_POST["username"]);
	$username = stripSpecial($username);
	if (!preg_match("/^[a-zA-Z]{2,20}$/",$username))
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
	
	////////////////////////////////db do
	if(false)
	{	
		//update
	}
	
}
function stripSpecial($string)	//get rid of comment chars.
{
	$string = str_replace("#","",$string);
	$string = str_replace("--","",$string);
	return $string;		
}

function cleanInput($string,$name,$required)
{
	if ($required ==1 && strlen($string) < 1)//check if actually there
	{
		echo("<li>".$name." is a required field.");
		$valid = 1;
	}
	
	$string = makeDBSafe($string);
	$string = urlencode($string);
	return $string;
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
					<?php echo "Hi ".$_SESSION['user'].", this is your profile."?>
					</div>
					<div class="form-group">
						<input type="text" name="username" placeholder="Username..." class="form-control" id="username" required autofocus>
					</div>
					<div class="form-group">
						
					</div>
					<div class="form-group">
						<input type="password" name="newpassword" placeholder="New Password..." class="form-control" id="newpassword"required>
					</div>
					<div class="form-group">
						<input type="password" name="newConf" placeholder="Retype Password..." class="form-control" id="newConf"required>
					</div>
					<div class="form-group">
						<input type="file" name="avatar" class="form-control" id="avatar">
					</div>
					<br>
					<input type="password" name="password" placeholder="Password..." class="form-control" id="password"required>
					<br>
					<button type="submit" class="btn btn-primary">Submit</button>
				</form></td>
				<td>
					
				</td></tr></table></div>
	</body>
</html>

