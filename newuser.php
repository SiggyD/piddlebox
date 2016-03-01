<!--/*
	Nebo 
	&
	Dave
	Secure Software Development
	Assignment 2
	March 3 2016
-->
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="description" content="">
		<meta name="author" content="">
		<link rel="icon" href="../../favicon.ico">
		<title>Piddlebox</title>
		<link href="../../dist/css/bootstrap.min.css" rel="stylesheet">
		<link href="../../assets/css/ie10-viewport-bug-workaround.css" rel="stylesheet">
		<link href="starter-template.css" rel="stylesheet">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
		
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
						<li><a href="userdetails.php">Account</a></li>
						<li><a href="about.html">About</a></li>
					</ul>
				</div><!--/.nav-collapse -->
			</div>
		</nav>
		
		<div class="container">
			
			<div class="starter-template">
				
				<p class="lead">Register</p>
			</div>
			<div align="center" class="panel panel-primary">
				<div align="left" class="panel-body"><strong ">Register<strong></div>
					
					<table class="table-condensed"><tr><td>
						<form class="registration-form" role="form" action="newuser.php" method="POST">
							<div class="form-group">
								<input type="text" name="username" placeholder="Username..." class="form-control" id="username" required autofocus>
							</div>
							<div class="form-group">
								<input type="email" name="email" placeholder="Email..." class="form-control" id="email"required>
							</div>
							<div class="form-group">
								<input type="password" name="password" placeholder="Password..." class="form-control" id="password"required>
							</div>
							<div class="form-group">
								<input type="password" name="passwordConf" placeholder="Retype Password..." class="form-control" id="passwordConf"required>
							</div>
							<button type="submit" class="btn btn-primary">Submit</button>
						</form></td>
						<td>
							<?php
								$valid;
								#if ($_SERVER['REQUEST_METHOD'] == 'POST') //small check to ensure request was post, hopefully indicating form data
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
									//email	
										
									$email = trim($_POST["email"]);
									$email = stripSpecial($email );
									if (!preg_match("/^[a-zA-Z.-_]+\@[a-zA-Z-_]+\.[a-zA-Z\.]{2,3}$/",$email))
									{
										echo "<div class=\"alert alert-danger\">Please input a valid email</div>";
										$valid = 1;
									}
									$email = trim($_POST["email"]);
									//password
									
									$password = trim($_POST["password"]);
									$passwordConf = trim($_POST["passwordConf"]);
									if (empty($password) || empty($passwordConf)) 
									{
										echo "<div class=\"alert alert-danger\">Password can not be empty.</div>";
										$valid = 1; 
									}
									
									if (strcmp($password,$passwordConf) != 0)
									{
										echo "<div class=\"alert alert-danger\">Passwords must match.</div>";
										$valid = 1; 
									}
									
									////////////////////////////////db do
									if($valid == 0)
									{	
										#$salt = base64_encode(openssl_random_pseudo_bytes(20));
										$hash =  password_hash($password.$username,PASSWORD_DEFAULT);
										#DEBUG INFO						
										#echo "<br>username: ".$username;
										#echo "<br>email: ".$email;
										#echo "<br>hash: ".$hash;
										#DEBUG INFO 
										$db = pg_connect('host=localhost dbname= user= password=');
										$insertStatement = "INSERT INTO piddle (username, email, passhash) VALUES ('" . $username . "','" . $email . "','" . $hash . "');";
										//DO
										$insertResult = pg_query($insertStatement);
										echo "<script type='text/javascript'> alert('User Added!')</script>";
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
						</td></tr></table></div>
				</body>
			</html>
				
