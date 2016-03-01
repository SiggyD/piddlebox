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
									//VALIDATION PATTERNS
									
									$usernamePattern = "/^[a-zA-Z]{5,15}$/";
									$emailPattern = "/^[a-zA-Z0-9-.]+\@[a-zA-Z0-9]+\.[a-zA-Z\.]{2,7}$/";
									$valid = 1;
									if ($_POST) {

										if (!preg_match($usernamePattern,$username) || empty($username)) {
											echo "<div class=\"alert alert-danger\"><strong>Username must be 5-15 characters only </strong> </div>";
											$valid = 0; 
										}
										if (!preg_match($emailPattern,$email) || empty($email)) {
											echo "<div class=\"alert alert-danger\"><strong>Email should be of form: username@domain.tld </strong> </div>";
											$valid = 0; 
										}
										if (empty($password) || empty($passwordConf)) {
											echo "<div class=\"alert alert-danger\"><strong>Password can not be empty.</strong> </div>";
											$valid = 0; 
										}
										if (strcmp($password,$passwordConf) != 0)
										{
											echo "<div class=\"alert alert-danger\"><strong>Passwords must match</strong> </div>";
											$valid = 0; 
										}
										
										
									}

								?>
							</td></tr></table></div>
					</body>
				</html>
						
