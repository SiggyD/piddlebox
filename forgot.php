<?php
	include 'header.php';
?>
<!-- Custom styles for this template -->
<link href="signin.css" rel="stylesheet">
<div class="container">
	<form class="form-signin" action="forgot.php" method="POST">
		<h2 class="form-signin-heading">Password Reset</h2>
		<label for="inputEmail" class="sr-only">Email address</label>
		<input type="email" id="inputEmail" name="email" class="form-control" placeholder="Email address" required autofocus>
		<?php session_start();
			if (!isset($_SESSION['token'])) {
				$_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(32));
				} else {
				$token = $_SESSION['token'];
			}
			#echo $token;
		?>
		<input type="hidden" value="<?php echo $token;?>">
		<br>
		<button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
		<input type="hidden" name="token" value="<?php echo $token; ?>" />
	</form>
	<?php
		if ((!empty($_POST))) // remember these?
		{
			$emaillog = $_POST['email'];
			$file = '/var/www/log/security.log';
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
				file_put_contents('/var/www/log/security.log',date(DATE_RFC2822).": User ".$row['username']." reset password from ".$_SERVER['REMOTE_ADDR']." - RESET FAIL\n", FILE_APPEND);
				#continue
			}
			else
			{
				echo "<div class=\"alert alert-danger\">Invalid Username, this has been reported. </div>";
				file_put_contents($file,date(DATE_RFC2822).": User attempted to reset unknown account from ".$_SERVER['REMOTE_ADDR']." - PASS RESET\n", FILE_APPEND);
				exit();
				#exit, no log
			}
		}
	?>
</div>
<script src="../../assets/js/ie10-viewport-bug-workaround.js"></script>
</body>
</html>

