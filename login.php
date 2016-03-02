<?php 
include 'header.php';	
?>
		
		<div class="container">
			<form class="form-signin" action="login.php" method="POST">
				<h2 class="form-signin-heading">Please sign in</h2>
				<label for="inputEmail" class="sr-only">Email address</label>
				<input type="email" id="inputEmail" name="email" class="form-control" placeholder="Email address" required autofocus>
				<label for="inputPassword" class="sr-only">Password</label>
				<input type="password" id="inputPassword" name="password" class="form-control" placeholder="Password" required>
				<div class="checkbox">
					<label>
						<input type="checkbox" value="remember-me"> Remember me
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
					$db = pg_connect('host=localhost dbname=ssd user=omalax password=ssd')
					or die("Can't connect to database".pg_last_error());
					$email = $_POST["email"];
					$password = $_POST["password"];	
					$query = "select * from piddle where email like '".$email."';";
					$result = pg_query($db,$query);
					$row = pg_fetch_assoc($result);
					if ($row['authFails'] < 5){
				
						$storedpassword = $row['passhash'];
						$user = $row['username'];
						$hashed_password = password_hash($password.$user,PASSWORD_DEFAULT);
						#your code is not getting down here
						if (isset($_POST['password']) && password_verify($password.$user, $storedpassword)) {
    							echo 'Password is valid!';
							$_SESSION['user'] = $user;
						} else {
    							echo 'Invalid password.';
							$authFailUpdate = "UPDATE PIDDLE SET \"authFails\" = \"authFails\"+1 WHERE username = '".$user."' RETURNING \"authFails\";";	
							$updateResult = pg_query($db,$authFailUpdate);
							$urow = pg_fetch_assoc($updateResult);
							$fails = $urow['authFails'];
							if ($fails = 5){
								echo "Your Account Has Been Locked Due to 5 Login Failures.";							
							} else {
								echo "You have ".$fails."authorization failures";
							}
							}
					    	} else {
						echo 'Your Account is locked! Please request a password reset.';			
					}
				}
			?>
		</div> 
		<script src="../../assets/js/ie10-viewport-bug-workaround.js"></script>
	</body>
</html>
