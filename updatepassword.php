<?php include 'config.php'?>
<?php include 'server.php'?>
<!DOCTYPE HTML>
<html>
<head>
    <title>Update Password</title>
  <link rel="stylesheet" type="text/css" href="/styles/signupLogin.scss">
</head>
<body>
<?php
 if (isset($_GET['email']) && ($_GET['code']!="")) {
  # code...
  $code=$_GET['code'];
  $email=$_GET['email'];

  $checkmail=$db->query("SELECT email FROM users WHERE email='$email' AND lost='$code' AND lost!='' ") or die();
  $count=mysqli_num_rows($checkmail);
  if ($count) {
   if (isset($_POST['password']) AND ($_POST['password']!="")){

     $password=md5($_POST['password']);
     $repassword=md5($_POST['repassword']);
     if ($password===$repassword) {
      # code...
      $inserted=$db->query("UPDATE users SET lost='', password='$password' WHERE email='$email' ");
       // insert into our table users with new password
      if ($inserted) {
       # code...
       echo "<h1>Successfully changed!</h1>
       <a href='index.php'>Return home</a>";
      }

     }
     else
     {
      echo "Password do not match!";
     }

   }
   # code...
   echo '
    <div class="header">
        <h2>Create New Password</h2>
    </div>
    <form method="post" action="updatepassword.php">
        <div class="input-group">
          <p><label>New Password: </label><input type="text" name="password" /></p>
        </div>
        <div class="input-group">
          <p><label>Retype New Password: </label><input type="text" name="repassword" /></p>
        </div>
        <div class="input-group">
          <p><input type="submit" name="create" value="Submit"/></p>
        </div>
      </form>
   ';

  }
  else
  {
   echo "<h2>Error occure! <a href='index.php'>Return</a></h2>";
  }

  
 }
//  $db->close();
 ?>
</body>
</html>
 