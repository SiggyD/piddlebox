
<?php
if ((!empty($_GET))) //small check to ensure request was post, hopefully indicating form data
{
  $redir = "reset.php?id=".$id."&locktoken=".$locktoken;
	$id = trim($_GET["id"]);
	$urltoken = trim($_GET["locktoken"]);
	#$urltoken = urlencode($urltoken);
	if (strlen( $urltoken) < 15 )
	{

	}

	$db = pg_connect('host=localhost dbname=ssd user=omalax password=ssd');
	if (!pg_prepare($db, 'lock_token_select', 'SELECT piddle.username, token.locktoken from piddle, token WHERE piddle.id = $1 AND token.id = $1'))
	{
		die("Can't prepare" . pg_last_error());
	}
	$result= pg_execute($db, 'lock_token_select', array($id));
	$row = pg_fetch_assoc($result);
  $username = $row['username'];
	# unlock account
	if (strcmp(trim($row['locktoken']),$urltoken) == 0)
	{
    #echo "<br>MATCH";
    #echo "<br> urltoken".$urltoken;
    #echo "<br> rowurltoken".$row['locktoken'];
    #echo "<br>".$username;

		/*if (!pg_prepare($db, 'authfail_update', 'UPDATE piddle SET "authFails" = 0 WHERE id = $1 '))
		{
			die("Can't prepare" . pg_last_error());
		}
		$updateResult = pg_execute($db, 'authfail_update', array($id));
		# take away token
    */
	if (!pg_prepare($db, 'locktoken_update', 'UPDATE token SET locktoken = \' \' WHERE id = $1 '))
		{
			die("Can't prepare" . pg_last_error());
		}
		$updateResult = pg_execute($db, 'locktoken_update', array($id));
	} else {
  	header('Location: index.php');
}

	#header('Location: index.php');
	#header('Location: piddlebox/index.php');
}
else
{
	#header('Location: index.php');
}
?>
<?php include 'header.php';?>

		<div class="container">

			<div class="starter-template">

				<p class="lead">Change Password</p>
			</div>
			<div align="center" class="panel panel-primary">
				<div align="left" class="panel-body"><strong><strong></div>

					<table class="table-condensed"><tr><td>
						<form class="registration-form" role="form" action="<?php $redir?>" method="POST">
							<div class="form-group">
								<input type="password" name="password" placeholder="Password..." class="form-control" id="password"required>
							</div>
							<div class="form-group">
								<input type="password" name="passwordConf" placeholder="Retype Password..." class="form-control" id="passwordConf"required>
							</div>
							<?php
								if (!isset($_SESSION['token'])) {
									session_start();
									$_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(32));
									} else {
									$token = $_SESSION['token'];
									if (isset($_POST['token']) && ($_POST['token'] != $token)) {
										echo "<div class=\"alert alert-danger\">Invalid csrftoken. This event has been logged.</div>";
										exit();
									}
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

									$password = trim($_POST["password"]);
									$passwordConf = trim($_POST["passwordConf"]);
									if (strlen($password) < 5)
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
										$db = pg_connect('host=localhost dbname=ssd user=omalax password=ssd');
										if (!pg_prepare($db, 'new_hash_update', 'UPDATE piddle SET passhash = $1 WHERE id = $2')) {
			                  die("Can't prepare" . pg_last_error());
			              }
                    if(!pg_execute($db, 'new_hash_update', array($hash,$id))) {
                      die("Can't reset".pg_last_error());
                    } else {
										$insertStatement = pg_execute($db, 'new_hash_update', array($hash,$id));
										#$row = pg_fetch_assoc($insertStatement);
                    echo pg_fetch_result($insertStatement);
									  echo "<script type='text/javascript'> alert('Password Changed!')</script>";
                   header('Location: login.php');
                  }
										#$logEntry = microtime()."- User ".$username." has registered.";
										$file = '/var/www/log/security.log';
									  $succ = file_put_contents($file,date(DATE_RFC2822).": User ".$username." has reset password from ".$_SERVER['REMOTE_ADDR']"."\n", FILE_APPEND);
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
