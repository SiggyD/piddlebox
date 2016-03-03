<?php include 'header.php';?>

		<div class="container">

			<div class="starter-template">

				<p class="lead">Register</p>
			</div>
			<div align="center" class="panel panel-primary">
				<div align="left" class="panel-body"><strong><strong></div>

					<table class="table-condensed"><tr><td>
						<form class="registration-form" role="form" action="newuser.php" method="POST">
							<div class="form-group">
								<input type="text" name="username" placeholder="Username..." class="form-control" id="username" pattern="[A-Za-z0-9]{2,20}" title="2 or more characters" required autofocus>
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
							<?php session_start();
								if (!isset($_SESSION['token'])) {
									$_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(32));
									} else {
									$token = $_SESSION['token'];
								}
								#echo $token;
							?>
							<input type="hidden" name="token" value="<?php echo $token; ?>" />
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
									if (!preg_match("/^[a-zA-Z]{3,20}$/",$username))
									{
										echo "<div class=\"alert alert-danger\">Username must be between 3-20 characters</div>";
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
									if (preg_match("/^.{1,4}$/",$password))
									{
										echo "<div class=\"alert alert-danger\">Password must be at least 5 charcters</div>";
										$valid = 1;
									}
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
										$db = pg_connect('host=localhost dbname=ssd user=sig password=ssd');
										if (!pg_prepare($db, 'new_user_insert', 'INSERT INTO piddle (username, email, passhash, "authFails") VALUES ($1,$2,$3,9) RETURNING id')) {
			                  die("Can't prepare" . pg_last_error());
			              }
										$insertStatement = pg_execute($db, 'new_user_insert', array($username,$email,$hash));
										$row = pg_fetch_assoc($insertStatement);
										$id = $row['id'];
										$regtoken = bin2hex(openssl_random_pseudo_bytes(32));

										if (!pg_prepare($db, 'new_token_insert', 'INSERT INTO token (id, regtoken) VALUES ($1,$2) RETURNING id')) {
												die("Can't prepare" . pg_last_error());
										}
										$insertToken = pg_execute($db, 'new_token_insert', array($id,$regtoken));
										$regtokenlogstring = "https://piddlebox.xyz/reg.php?id=".$id."&regtoken=".$regtoken;
										#echo urlencode($regtokenlogstring);
										#$insertStatement = "INSERT INTO piddle (username, email, passhash) VALUES ('" . $username . "','" . $email . "','" . $hash . "');";
										//DO
										//placeholder for insertion of key into activation table
										#$insertResult = pg_query($insertStatement);
										echo "<script type='text/javascript'> alert('User Added!')</script>";
										$logEntry = microtime()."- User ".$username." has registered.";
										$file = '/var/www/log/registration.log';
										$succ = file_put_contents($file,date(DATE_RFC2822).": User ".$username." has registered from ".$_SERVER['REMOTE_ADDR']." - registration link : ".$regtokenlogstring."\n", FILE_APPEND);
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

