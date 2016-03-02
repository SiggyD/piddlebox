<h1>HAI</h1>
<?php
if ((!empty($_GET))) //small check to ensure request was post, hopefully indicating form data
{
	$id = trim($_GET["id"]);
	echo $id."<br>";
	$urltoken = trim($_GET["regtoken"]);
	echo $urltoken."<br>";
	$urltoken = urlencode($urltoken);
	$db = pg_connect('host=localhost dbname=ssd user=sig password=ssd');
	if (!pg_prepare($db, 'new_token_insert', 'SELECT regtoken from token where id = $1'))
	{
		die("Can't prepare" . pg_last_error());
	}
	$result= pg_execute($db, 'new_token_insert', array($id));
	$row = pg_fetch_assoc($result);
	
	echo "<br>".$row['regtoken']." - is what came out";
	if (strcmp($row['regtoken'],$urltoken))
	{
		if (!pg_prepare($db, 'authfail_update', 'UPDATE piddle SET "authFails" = 0 WHERE id = $1 '))
		{
			die("Can't prepare" . pg_last_error());
		}
		$updateResult = pg_execute($db, 'authfail_update', array($id));		
	}
	
}
?>
