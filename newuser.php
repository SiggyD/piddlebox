<!--/*
Nebo n Dabe
Secure Software Development
Assignment 2
March 3 2016
-->

<html>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

  <nav class="navbar navbar-default">
    <div class="container-fluid">
      <div class="navbar-header">
        <a class="navbar-brand" href="newuser.php">PiddleBox SSD 01</a>
      </div>
      <ul class="nav navbar-nav">

        <li class="active"><a href="newuser.php">New User</a></li>
        <li><a href="userlist.php">User List</a></li>
        <li ><a href="userdetails.php">User Details</a></li>
      </ul></div>
<head>
<br>
<br>
</head>

<body>
  <div align="center" class="panel panel-primary">
  <div align="left" class="panel-body"><strong>Register a new User<strong></div>

<table class="table-condensed"><tr><td>
  <form class="registration-form" role="form" action="newuser.php" method="post">
    <div class="form-group">
<input type="text" name="uname" placeholder="Username..." class="form-control" id="uname">
</div>
  <div class="form-group">
<input type="email" name="email" placeholder="Email..." class="form-control" id="email">
  </div>
  <div class="form-group">
<input type="text" name="address" placeholder="Address..." class="form-control" id="address">
</div>
<div class="form-group">
  <input type="text" name="city" placeholder="City..." class="form-control" id="city">
</div>
<div class="form-group">
<input type="text" name="postal" placeholder="Postal Code..." class="form-control" id="postal">
</div>
<div class="form-group">
<input type="text" name="country" placeholder="Country..." class="form-control" id="country">
</div>
<div class="form-group">
<input type="phone" name="phone" placeholder="Phone Number..." class="form-control" id="phone">
</div>
<div class="form-group">
<textarea name="bio" rows="5" wrap="hard" placeholder="A little something about yourself..." class="form-control" id="bio"></textarea>
</div>

  <button type="submit" class="btn btn-primary">Submit</button>
</form></td>
<td>
<?php
//VALIDATION PATTERNS

$unamePattern = "/^[a-zA-Z]{5,15}$/";
$phonePattern = "/^\(?[0-9]{3}\)?[\s.-]?[0-9]{3}[\s.-]?[0-9]{4}$/";
$emailPattern = "/^[a-zA-Z0-9-.]+\@[a-zA-Z0-9]+\.[a-zA-Z\.]{2,7}$/";
$postalPattern = "/^[a-zA-Z][0-9][a-zA-Z](\s)?[0-9][a-zA-Z][0-9]$/";
$valid = 1;
//http://www.tutorialspoint.com/php/php_validation_example.htm
//http://www.w3schools.com/php/func_string_str_replace.asp
function sanitize($data) {
  $sqlKeywords = array("SELECT","INSERT","UPDATE","DELETE","DROP","CREATE","GRANT","select","insert","update","delete", "drop", "create", "grant");

          //standard sanitation + extra keyword filtering
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            $data = str_replace("#","",$data);
            $data = str_replace("--","",$data);
            foreach ($sqlKeywords as $kw) {
              $data = str_replace($kw,"",$data);
            }
            $data = trim($data);
            return $data;
         }

if ($_POST) {
  //CONSUUUUME aka grab and store data
  //regex for uname - 5-20 chars no nums or special chars
  if (!preg_match($unamePattern,$uname) || empty($uname)) {
    echo "<div class=\"alert alert-danger\"><strong>Username must be 5-15 characters only </strong> </div>";
    $valid = 0; //info is invalid -- do not insert into db
  }

  //regex for email formatting
  if (!preg_match($emailPattern,$email) || empty($email)) {
    echo "<div class=\"alert alert-danger\"><strong>Email should be of form: username@domain.tld </strong> </div>";
    $valid = 0; //info is invalid -- do not insert into db
  }
  //regex for phone formatting
  if (!preg_match($phonePattern,$phone) || empty($phone)) {
    echo "<div class=\"alert alert-danger\"><strong>Phone must match one of the following formats:<br><li>1234567890</li><li>123.456.7890</li><li>123-456-7890</li><li>(123) 456-7890</li></strong> </div>";
    $valid = 0; //info is invalid -- do not insert into db
  }
  //regex for postal code formatting. cdn only
  if (!preg_match($postalPattern,$postal) || empty($postal)) {
        echo "<div class=\"alert alert-danger\"><strong>Postal Code must match one of the following formats<br><li>A1A1A1</li><li>A1A 1A1</li></strong> </div>";
    $valid = 0; //info is invalid -- do not insert into db
  }

}


//connect to the db with magic
//only submit if valid and submit has been clicked
//can proooooooobably be broken but so can most of this site


?>
</td></tr></table></div>
</body>
</html>
