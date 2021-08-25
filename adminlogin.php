<?php include('server.php') ?>
<!DOCTYPE html>
<html>
<head>
  <title>Help My City</title>
  <link rel="stylesheet" type="text/css" href="/styles/signupLogin.css">
</head>
<body>
  <div class="header">
  	<h2>Admin Login</h2>
  </div>
	 
  <form method="post" action="adminlogin.php">
  	<?php include('errors.php');?>
  	<div class="input-group">
  		<label>Username</label>
  		<input required type="text" name="username" >
  	</div>
  	<div class="input-group">
  		<label>Password</label>
  		<input required type="password" name="password">
  	</div>
  	<div class="input-group">
  		<label>Secret Word</label>
  		<input required type="password" name="sw">
  	</div>
  	<div class="input-group">
  		<button type="submit" class="btn" name="login_admin">Login</button>
  	</div>
	  <!-- <p>
	  <a href="forgotpwd.php">Forgot Password?</a>
	  </p> -->
  </form>
</body>
</html>