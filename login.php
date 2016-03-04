<?php
	include 'header.php';
  $token = bin2hex(openssl_random_pseudo_bytes(32));
?>
<!-- Custom styles for this template -->
<link href="signin.css" rel="stylesheet">
<div class="container">
	<form class="form-signin" action="login.php" method="POST">
		<h2 class="form-signin-heading">Please Sign In</h2>
		<label for="inputEmail" class="sr-only">Email address</label>
		<input type="email" id="inputEmail" name="email" class="form-control" placeholder="Email address" required autofocus>
		<label for="inputPassword" class="sr-only">Password</label>
		<input type="password" id="inputPassword" name="password" class="form-control" placeholder="Password" required>
		<div class="checkbox">
			<label>
				<li><a href=newuser.php> Register Here</a></li>
				<li><a href=forgot.php> Reset Password</a></li>
			</label>
		</div>
		<?php #session_start();
			if (!isset($_SESSION['token'])) {
				$_SESSION['token'] = $token;
				} else {
				$token = $_SESSION['token'];
        if (isset($_POST['token']) && ($_POST['token'] != $token)) {
          echo "<div class=\"alert alert-danger\">Invalid csrftoken. This event has been logged.</div>";
          exit();
        }
			}
			#echo $token;
		?>
		<button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
		<input type="hidden" name="token" value="<?php echo $token; ?>" />
	</form>
	<?php
		if ((!empty($_POST))) // remember these?
		{
			$validemail = 1;
			$emaillog = $_POST['email'];
			$file = '/var/www/log/auth.log';
			$db = pg_connect('host=localhost dbname=ssd user=sig password=ssd')
			or die("Can't connect to database".pg_last_error());
			if (!pg_prepare($db,'login_select', 'SELECT * FROM piddle WHERE email = $1')) 
			{
				die("Can't prepare" . pg_last_error());
			}
			
			$email = $_POST["email"];
			$password = $_POST["password"];
			$result = pg_execute($db, 'login_select', array($email));
			$row = pg_fetch_assoc($result);
#db stuff is available
			# after user fetch 
# check 1, user exists
			if ($email == $row['email']) # check if user was returned
			{
				#continue
			}
			else
			{
				echo "<div class=\"alert alert-danger\">Invalid Username/Password.</div>";
				exit();
				#exit, no log
			}
			$storedpassword = $row['passhash'];
			#$username = $row['username'];
			$fails = $row['authFails'];
			$hashed_password = password_hash($password.$email,PASSWORD_DEFAULT);
# check 2 user exits, check pass
			if (isset($_POST['password']) && password_verify($password.$email, $storedpassword)) 
			{
				#continue
			}
			else #password has failed
			{
				echo "<div class=\"alert alert-danger\">Invalid Username/Password.</div>";
				if (!pg_prepare($db, 'authfail_update', 'UPDATE piddle SET "authFails" = "authFails"+1 WHERE username = $1 RETURNING "authFails"')) 
				{
					die("Can't prepare" . pg_last_error());
				}
				file_put_contents($file,date(DATE_RFC2822).": User has failed to authenticate with email ".$emaillog." from ".$_SERVER['REMOTE_ADDR']." Number of tries remaining : ".(5 - $fails)."\n", FILE_APPEND);
				if ($fails < 5)
				{
					$updateResult = pg_execute($db, 'authfail_update', array($username));
				}
				
				#pretty sure this doesnt do anything
				$urow = pg_fetch_assoc($updateResult);
				$fails = $urow['authFails'];
				#echo "<div class=\"alert alert-danger\">Auth failure number ".$fails."</div>";
				exit();
			}
# check 3 user exits, password correct, check status
			//save for the very end 
			
			if ($fails == 9)	# account is not active yet
			{
				echo "<div class=\"alert alert-danger\">Your account is inactive. Please follow the extra secret confirmation link.</div>";
				file_put_contents($file,date(DATE_RFC2822).": User has failed to authenticate with email  ".$emaillog." from ".$_SERVER['REMOTE_ADDR']." - NOT ACTIVATED\n", FILE_APPEND);
			}
			else if ($fails == 5) # account has been auth locked :(
			{
				echo "<div class=\"alert alert-danger\">Your Account Has Been Locked Due to 5 Login Failures.</div>";
				file_put_contents($file,date(DATE_RFC2822).": User has failed to authenticate with email  ".$emaillog." from ".$_SERVER['REMOTE_ADDR']." - ACCOUNT LOCK\n", FILE_APPEND);
			}
			else
			{
					$_SESSION['email'] = $email;
				echo "<script type='text/javascript'> alert('You logged in. Enjoy the site.')</script>";
				#header("Location: login.php"); # make this happen last...
			}
		}
	?>
</div>
<script src="../../assets/js/ie10-viewport-bug-workaround.js"></script>
</body>
</html>

