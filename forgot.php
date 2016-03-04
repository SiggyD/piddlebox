<?php
	include 'header.php';
	  $token = bin2hex(openssl_random_pseudo_bytes(32));
?>
<!-- Custom styles for this template -->
<link href="signin.css" rel="stylesheet">
<div class="container">
	<form class="form-signin" action="forgot.php" method="POST">
		<h2 class="form-signin-heading">Password Reset</h2>
		<label for="inputEmail" class="sr-only">Email address</label>
		<input type="email" id="inputEmail" name="email" class="form-control" placeholder="Email address" required autofocus>
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
				#continue
				#$db = pg_connect('host=localhost dbname=ssd user=omalax password=ssd');
				if (!pg_prepare($db, 'get_id', 'SELECT id FROM piddle WHERE email = $1')) {
						die("Can't prepare" . pg_last_error());
				}
				$insertStatement = pg_execute($db, 'get_id', array($email));
				#echo $insertStatement;
				$row = pg_fetch_assoc($insertStatement);
				$id = $row['id'];
				$passtoken = bin2hex(openssl_random_pseudo_bytes(32));

				if (!pg_prepare($db, 'new_token_insert', 'UPDATE token SET locktoken = $1 WHERE id = $2 RETURNING id')) {
						die("Can't prepare" . pg_last_error());
				}
				$insertToken = pg_execute($db, 'new_token_insert', array($passtoken, $id));
				#$passtokenlogstring = "https://piddlebox.xyz/reg.php?id=".$id."&regtoken=".$passtoken;
				$passtokenlogstring = "https://www.piddlebox.xyz/reset.php?id=".$id."&locktoken=".$passtoken;
				file_put_contents('/var/www/log/security.log',date(DATE_RFC2822).": User ".$row['username']." reset password from ".$_SERVER['REMOTE_ADDR']." - RESET LINK - ".$passtokenlogstring."\n", FILE_APPEND);
				echo "<script type='text/javascript'> alert('Reset Sent!')</script>";
			}
			else
			{
				echo "<div class=\"alert alert-danger\">Invalid Username, this has been reported. </div>";
				file_put_contents($file,date(DATE_RFC2822).": User attempted to reset unknown account from ".$_SERVER['REMOTE_ADDR']." - RESET FAIL\n", FILE_APPEND);
				exit();
				#exit, no log
			}
		}
	?>
</div>
<script src="../../assets/js/ie10-viewport-bug-workaround.js"></script>
</body>
</html>
