<!--/*
	Nebo 
	&
	Dave
	Secure Software Development
	Assignment 2
	March 3 2016
-->

<html>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
	
	<nav class="navbar navbar-default">
		<div class="container-fluid">
			<div class="navbar-header">
				<a class="navbar-brand" href="index.html">Secure Web Portal</a>
			</div>
			<ul class="nav navbar-nav">
				<li class="active"><a href="newuser.php">New User</a></li>
				<li ><a href="index.html.php">THIS GOES NOWHERE</a></li>
			</ul></div>
			<head>
				<br>
				<br>
			</head>
			<body>
				<div align="center" class="panel panel-primary">
					<div align="left" class="panel-body"><strong style="font-size:2em;">Register<strong></div>
						
						<table class="table-condensed"><tr><td>
							<form class="registration-form" role="form" action="newuser.php" method="post">
								<div class="form-group">
									<input type="text" name="username" placeholder="Username..." class="form-control" id="username">
								</div>
								<div class="form-group">
									<input type="email" name="email" placeholder="Email..." class="form-control" id="email">
								</div>
								<div class="form-group">
									<input type="password" name="password" placeholder="Password..." class="form-control" id="password">
								</div>
								<div class="form-group">
									<input type="password" name="passwordConf" placeholder="Retype Password..." class="form-control" id="passwordConf">
								</div>
								
								
								<button type="submit" class="btn btn-primary">Submit</button>
							</form></td>
							<td>
								<?php
								
$valid;
if ($_SERVER['REQUEST_METHOD'] == 'POST') //small check to ensure request was post, hopefully indicating form data
	{
		$valid = 0;
//collect posted data & validate
//username		
		$username = trim($_POST["username"]);
		$username = stripSpecial($username);
		if (!preg_match("/^[a-zA-Z]{2,20}$/",$username))
		{
			echo "<div class=\"alert alert-danger\"><strong>Username must be between 5-20 characters</strong> </div>";
			$valid = 1;
		}
//email		
		$email = trim($_POST["email"]);
		$email = stripSpecial($email );
		if (!preg_match("/^[a-zA-Z.-_]+\@[a-zA-Z-_]+\.[a-zA-Z\.]{2,3}$/",$email))
		{
			echo "<div class=\"alert alert-danger\"><strong>Please input a valid email</strong> </div>";
			$valid = 1;
		}
		$email = trim($_POST["email"]);
//password
		$password = trim($_POST["password"]);
		$passwordConf = trim($_POST["passwordConf"]);
		if (empty($password) || empty($passwordConf)) 
		{
			echo "<div class=\"alert alert-danger\"><strong>Password can not be empty.</strong> </div>";
			$valid = 1; 
		}
		if (strcmp($password,$passwordConf) != 0)
		{
			echo "<div class=\"alert alert-danger\"><strong>Passwords must match.</strong> </div>";
			$valid = 1; 
		}
	
//db do
		if($valid == 0)
		{
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
						
