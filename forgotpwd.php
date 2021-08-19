<?php include('config.php') ?>
<?php include 'server.php'?>
<!DOCTYPE HTML>
<html>
<head>
    <title>Forgot Password</title>
  <link rel="stylesheet" type="text/css" href="/styles/signupLogin.scss">
</head>
<body>
<div class="header">
 <h2>Reset Password</h2>
</div>
    <form method="post" action="forgotpwd.php">
  	<?php include('errors.php'); ?>
        <div class="input-group">
            <p><label>Email: </label><input required type="text" name="email" /></p>
        </div>
        <div class="input-group">
            <p><button class="btn" type="submit" name="reset_pwd" value="Reset">Reset</button><a href="login.php">Cancel</a></p>
        </div>
    </form>
</body>
</html>