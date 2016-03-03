
<?php
if ((!empty($_GET))) //small check to ensure request was post, hopefully indicating form data
{
	$id = trim($_GET["id"]);
	$urltoken = trim($_GET["regtoken"]);
	#$urltoken = urlencode($urltoken);
	if (strlen( $urltoken) < 15 )
	{
		
	}
	
	$db = pg_connect('host=localhost dbname=ssd user=sig password=ssd');
	if (!pg_prepare($db, 'new_token_insert', 'SELECT regtoken from token where id = $1'))
	{
		die("Can't prepare" . pg_last_error());
	}
	$result= pg_execute($db, 'new_token_insert', array($id));
	$row = pg_fetch_assoc($result);
	# unlock account
	if (strcmp(trim($row['regtoken']),$urltoken) == 0)
	{
		if (!pg_prepare($db, 'authfail_update', 'UPDATE piddle SET "authFails" = 0 WHERE id = $1 '))
		{
			die("Can't prepare" . pg_last_error());
		}
		$updateResult = pg_execute($db, 'authfail_update', array($id));	
		# take away token
		if (!pg_prepare($db, 'token_update', 'UPDATE token SET regtoken = \'active\' WHERE id = $1 '))
		{
			die("Can't prepare" . pg_last_error());
		}
		$updateResult = pg_execute($db, 'token_update', array($id));	
	}

	header('Location: index.php');
}
else
{
	header('Location: index.php');
}
?>
