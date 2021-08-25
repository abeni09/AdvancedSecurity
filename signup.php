
<?php include('server.php') ?>
<!DOCTYPE html>
<html>
<head>
  <title>Help My City</title>
  <link rel="stylesheet" type="text/css" href="/styles/signupLogin.css">
</head>
<body>
  <div class="header">
  	<h2>Sign up</h2>
  </div>
	
  <form method="post" action="signup.php">
  	<?php include('errors.php'); ?>
  	<div class="input-group">
  	  <label>Username</label>
  	  <input required type="text" name="username" value="<?php echo $username; ?>">
  	</div>
  	<div class="input-group">
  	  <label>Email</label>
  	  <input required type="email" name="email" value="<?php echo $email; ?>">
  	</div>
  	<div class="input-group">
  	  <label>Password</label>
  	  <input required type="password" name="password_1">
  	</div>
  	<div class="input-group">
  	  <label>Confirm password</label>
  	  <input required type="password" name="password_2">
  	</div>
  	<div class="input-group">
  	  <button type="submit" class="btn" name="reg_user">Sign up</button>
  	</div>
  	<p>
  		Already a member? <a href="login.php">Login</a>
  	</p>
  </form>
</body>
</html>