<!DOCTYPE html>
<html lang="en">
	
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="description" content="">
		<meta name="author" content="">
		<link rel="icon" href="../../favicon.ico">
		
		<title>Login</title>
		<link href="../../dist/css/bootstrap.min.css" rel="stylesheet">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
		<link href="../../assets/css/ie10-viewport-bug-workaround.css" rel="stylesheet">
		<link href="signin.css" rel="stylesheet">
		<script src="../../assets/js/ie-emulation-modes-warning.js"></script>
	</head>
	<body>
		<nav class="navbar navbar-inverse navbar-fixed-top">
			<div class="container">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<a class="navbar-brand" href="index.html">Secure Web Portal</a>
				</div>
				<div id="navbar" class="collapse navbar-collapse">
					<ul class="nav navbar-nav">
						<li class="active"><a href="index.html">Home</a></li>
						<li><a href="login.php">Account</a></li>
						<li><a href="newuser.php">Register</a></li>
						<li><a href="myprofile.php">My Profile</a></li>
						<li><a href="about.html">About</a></li>
					</ul>
				</div><!--/.nav-collapse -->
			</div>
		</nav>
		
		<div class="container">
			<form class="form-signin" action="login.php" method="POST">
				<h2 class="form-signin-heading">Please sign in</h2>
				<label for="inputEmail" class="sr-only">Email address</label>
				<input type="email" id="inputEmail" name="email" class="form-control" placeholder="Email address" required autofocus>
				<label for="inputPassword" class="sr-only">Password</label>
				<input type="password" id="inputPassword" name="password" class="form-control" placeholder="Password" required>
				<div class="checkbox">
					<label>
						<input type="checkbox" value="remember-me"> Remember me
					</label>
				</div>
				<?php session_start();
					if (!isset($_SESSION['token'])) {
						$_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(32));
						} else {
						$token = $_SESSION['token'];
					}
					#echo $token;
				?>
				<button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
				<input type="hidden" name="token" value="<?php echo $token; ?>" />
			</form>
			<?php
			if ((!empty($_POST))) // remember these?
				{ 
					$db = pg_connect('host=localhost dbname=ssd user=sig password=ssd')
					or die("Can't connect to database".pg_last_error());
					$email = $_POST["email"];
					$password = $_POST["password"];	
					#echo $email;
					#echo $password;
					$query = "select passhash, username from piddle where email like '".$email."';";
					$result = pg_query($db,$query);
					$row = pg_fetch_assoc($result);
					$storedpassword = $row['passhash'];
					$user = $row['username'];
					echo $user." ";	
					echo $storedpassword." ";
					$hashed_password = password_hash($password.$user,PASSWORD_DEFAULT);
					echo "--".$hashed_password."--";
					#your code is not getting down here
					if (hash_equals($storedpassword, $hashed_password)) 
					{
						echo "Password verified!";
					} 
					else 
					{
						echo "Password no good!";	
					}
				}
			?>
		</div> 
		<script src="../../assets/js/ie10-viewport-bug-workaround.js"></script>
	</body>
</html>
