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
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">

    <title>Welcome to Piddle Box</title>

    <!-- Bootstrap core CSS -->
    <link href="../../dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link href="../../assets/css/ie10-viewport-bug-workaround.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="signin.css" rel="stylesheet">
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
          <a class="navbar-brand" href="index.html">Super Web Portal</a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
            <li class="active"><a href="index.html">Home</a></li>
            <li><a href="login.php">Account</a></li>
            <li><a href="newuser.php">Register</a></li>
            <li><a href="about.html">About</a></li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>
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
						
