<?php
	include 'header.php';
?>
<!-- Custom styles for this template -->
<link href="signin.css" rel="stylesheet">
<div class="container">
	<form class="form-signin" action="login.php" method="POST">
		<h2 class="form-signin-heading">Please sign in</h2>
		<label for="inputEmail" class="sr-only">Email address</label>
		<input type="email" id="inputEmail" name="email" class="form-control" placeholder="Email address" required autofocus>
		<label for="inputPassword" class="sr-only">Password</label>
		<input type="password" id="inputPassword" name="password" class="form-control" placeholder="Password" required>
		<div class="checkbox">
			<label>
				<input type="checkbox" value="remember-me"> Remember me <br>New to Piddlebox? <a href=newuser.php> Register Here</a> 
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
			if (!pg_prepare($db,'login_select', 'SELECT * FROM piddle WHERE email = $1')) {
				die("Can't prepare" . pg_last_error());
			}
			$email = $_POST["email"];
			$password = $_POST["password"];
			$result = pg_execute($db, 'login_select', array($email));
			$row = pg_fetch_assoc($result);
			if ($row['authFails'] < 5){
				
				$storedpassword = $row['passhash'];
				$user = $row['username'];
				$hashed_password = password_hash($password.$user,PASSWORD_DEFAULT);
				#your code is not getting down here
				if (isset($_POST['password']) && password_verify($password.$user, $storedpassword)) {
					echo 'Password is valid!';
					$_SESSION['user'] = $user;
					} 
					else 
					{
						echo "<div class=\"alert alert-danger\">Invalid Password.</div>";
						if (!pg_prepare($db, 'authfail_update', 'UPDATE piddle SET "authFails" = "authFails"+1 WHERE username = $1 RETURNING "authFails"')) {
						die("Can't prepare" . pg_last_error());
						$file = '/var/www/log/auth.log';
						$succ = file_put_contents($file,date(DATE_RFC2822).": User has failed to authenticate with username  ".$username." from ".$_SERVER['REMOTE_ADDR']."\n", FILE_APPEND);
					}
					#$updateResult = pg_query($db,$authFailUpdate);";
					#echo "STUFFFFFFFFFFF: ".$updateResult;
					$updateResult = pg_execute($db, 'authfail_update', array($user));
					$urow = pg_fetch_assoc($updateResult);
					$fails = $urow['authFails'];
					#echo "FAILS:  ".$fails;
					if ($fails == 5)
					{
						echo "<div class=\"alert alert-danger\">Your Account Has Been Locked Due to 5 Login Failures.</div>";
					} 
					else 
					{
						echo "You have ".$fails."authorization failures";
						echo "<div class=\"alert alert-danger\">You have ".$fails."authorization failures</div>";
					}
				}
				} else {
				echo "<div class=\"alert alert-danger\">Your Account is locked! Please request a password reset.</div>";
			}
		}
	?>
</div>
<script src="../../assets/js/ie10-viewport-bug-workaround.js"></script>
</body>
</html>


