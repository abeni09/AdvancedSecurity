<?php include('server.php') ?>
<!DOCTYPE html>
<html>
<head>
  <title>Help My City</title>
  <link rel="stylesheet" type="text/css" href="/styles/signupLogin.css">
</head>
<body>
  <div class="header">
  	<h2>Login</h2>
  </div>
	 
  <form method="post" action="login.php">
  	<?php include('errors.php');
	 array_push($errors,session_id()) 
	  ?>

  	<div class="input-group">
  		<label>Username</label>
  		<input required type="text" name="username" >
  	</div>
  	<div class="input-group">
  		<label>Password</label>
  		<input required type="password" name="password">
  	</div>
  	<div class="input-group">
  		<button type="submit" class="btn" name="login_user">Login</button>
  	</div>
	  <!-- <p>
	  <a href="forgotpwd.php">Forgot Password?</a>
	  </p> -->
  	<p>
  		Not yet a member? <a href="signup.php">Sign up</a>
  	</p>
  </form>
</body>
</html>