<?php include('server.php') ?>
<!DOCTYPE html>
<html>
<head>
  <title>Help My City</title>
  <link rel="stylesheet" type="text/css" href="/styles/signupLogin.scss">
</head>
<body>
  <div class="header">
  	<h2>Admin Login?</h2>
  </div>
	 
  <form method="post" action="login.php">
  	<?php include('errors.php'); ?>
  	<div class="input-group">
  		<label>Secret Word</label>
  		<input required type="password" name="sw">
  	</div>
  	<div class="input-group">
  		<button type="submit" class="btn" name="confirm_admin">Confirm</button>
  	</div>

  </form>
</body>
</html>