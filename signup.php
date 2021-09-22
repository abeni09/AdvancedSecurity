
<?php include('server.php') ?>
<?php include('captcha.php') ?>
<!DOCTYPE html>
<html>
<head>

  <title>Help My City</title>
  <style>
	  * {
    margin: 0px;
    padding: 0px;
  }
  body {
    font-size: 120%;
    background: #F8F8FF;
  }
  
  .header {
    width: 30%;
    margin: 50px auto 0px;
    color: white;
    background: #5F9EA0;
    text-align: center;
    border: 1px solid #B0C4DE;
    border-bottom: none;
    border-radius: 10px 10px 0px 0px;
    padding: 20px;
  }
  form, .content {
    width: 30%;
    margin: 0px auto;
    padding: 20px;
    border: 1px solid #B0C4DE;
    background: white;
    border-radius: 0px 0px 10px 10px;
  }
  .input-group {
    margin: 10px 0px 10px 0px;
  }
  .input-group label {
    display: block;
    text-align: left;
    margin: 3px;
  }
  .input-group input {
    height: 30px;
    width: 93%;
    padding: 5px 10px;
    font-size: 16px;
    border-radius: 5px;
    border: 1px solid gray;
  }
  .btn {
    padding: 10px;
    font-size: 15px;
    color: white;
    background: #5F9EA0;
    border: none;
    border-radius: 5px;
  }
  .error {
    width: 92%; 
    margin: 0px auto; 
    padding: 10px; 
    border: 1px solid #a94442; 
    color: #a94442; 
    background: #f2dede; 
    border-radius: 5px; 
    text-align: left;
  }
  .success {
    color: #3c763d; 
    background: #dff0d8; 
    border: 1px solid #3c763d;
    margin-bottom: 20px;
  }
  </style>
  
  <!-- <link rel="stylesheet" type="text/css" href="/styles/signupLogin.css"> -->
</head>
<body>
  <div class="header">
  	<h2>Sign up</h2>
  </div>
	
  <form method="post" action="signup.php" autocomplete="off">
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
  	  <input minlength="8" required type="password" name="password_1">
  	</div>
  	<div class="input-group">
  	  <label>Confirm password</label>
  	  <input minlength="8" required type="password" name="password_2">
  	</div>
  	<div class="input-group">
      <img src="captcha.png" alt="CAPTCHA" class="captchaimage">
      <!-- <button class="refreshB" type="reset" name="refreshCaptcha"><img id="refresh" style="width: 35px; height:45px;" src="crefresh.png" alt="REFRESH"></button> -->
  	  <input required type="text" id="captcha" name="captcha_challenge">
      <!-- <button type="submit" class="btn" name="refreshCaptcha">Refresh</button> -->
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