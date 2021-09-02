<?php include('server.php') ?>
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
  <link rel="stylesheet" type="text/css" href="/styles/table.css"></link>
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