<?php include 'header.php';?>
		<div class="container">
			<div class="starter-template">
				
				<p class="lead">Your Profile</p>
			</div>
			<div align="center" class="panel panel-primary">
				<div align="left" class="panel-body"><strong ">User Profile<strong></div>
					
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
							<div class="form-group">
								<input type="file" name="avatar" class="form-control" id="avatar">
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
									if(false)
									{	
										#$salt = base64_encode(openssl_random_pseudo_bytes(20));
										$hash =  password_hash($password.$username,PASSWORD_DEFAULT);
										$db = pg_connect('host=localhost dbname=ssd user=sig password=ssd');
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
				
