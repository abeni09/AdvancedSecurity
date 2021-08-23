<?php include('config.php') ?>
<?php
// REGISTER USER
if (isset($_POST['reg_user'])) {
  // receive all input values from the form
  $username = mysqli_real_escape_string($readDB, trim($_POST['username']));
  $email = mysqli_real_escape_string($readDB, trim($_POST['email']));
  $password_1 = mysqli_real_escape_string($readDB, trim($_POST['password_1']));
  $password_2 = mysqli_real_escape_string($readDB, trim($_POST['password_2']));
  if (empty($username)) { array_push($errors, "Username is required"); }
  if (empty($email)) { array_push($errors, "Email is required"); }
  if (empty($password_1)) { array_push($errors, "Password is required"); }
  if ($password_1 != $password_2) {
	array_push($errors, "The two passwords do not match");
  }

  // first check the database to make sure 
  // a user does not already exist with the same username and/or email
  $user_check_query = "SELECT * FROM users WHERE username='$username' OR email='$email' LIMIT 1";
  $result = mysqli_query($readDB, $user_check_query);
  $user = mysqli_fetch_assoc($result);
  
  if ($user) { // if user exists
    if ($user['username'] === $username) {
      array_push($errors, "Username already exists");
    }

    if ($user['email'] === $email) {
      array_push($errors, "email already exists");
    }
  }

  // Finally, register user if there are no errors in the form
  if (count($errors) == 0) {
  	$password = md5($password_1);//encrypt the password before saving in the database

  	$query = "INSERT INTO users (username, email, password) 
  			  VALUES('$username', '$email', '$password')";
  	mysqli_query($writeDB, $query);
  	$_SESSION['username'] = $username;
  	$_SESSION['success'] = "You are now logged in";
  	header('location: index.php');
  }
}

// LOGIN USER
if (isset($_POST['login_user'])) {
  if(isset($_SESSION['username'])){
    array_push($errors,"You are already logged in. Logout to login using another account.");
    header("index.php");
  }
  else
    $username = mysqli_real_escape_string($readDB, trim($_POST['username']));
    $password = mysqli_real_escape_string($readDB, trim($_POST['password']));
  
    if (empty($username)) {
        array_push($errors, "Username is required");
    }
    if (empty($password)) {
        array_push($errors, "Password is required");
    }
  
    if (count($errors) == 0) {
        $password = md5($password);
        $query = "SELECT * FROM users WHERE username='$username' AND password='$password'";
        $results = mysqli_query($readDB, $query);
        if (mysqli_num_rows($results) == 1) {
          $_SESSION['username'] = $username;
          $_SESSION['success'] = "You are now logged in";
          header('location: index.php');
        }else {
            array_push($errors, "Wrong username/password combination");
        }
    }
  }
  //reset password
  // if (isset($_POST['reset_pwd']) && ($_POST['email']!="")) {
  //   # code...
  //   $email=trim($_POST['email']); // get email address from user form
  //   // $code=md5(uniqid(true)); // random alphernumeric character store in $code variable
    
  //   if (!filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
  
  //    $checkmail=$db->query("SELECT email FROM users WHERE email='$email' ") or die();
  //    $count=mysqli_num_rows($checkmail); // check if user is on our data base
  
  //    if ($count==1) { // if email is stored in our database update lost password field with random code for reset
  //     # code...s
  //     $inserted=$db->query("UPDATE users SET lost='$code' WHERE email='$email' ");
  //       // update our table users with unique random code
  //       /* Send a link to reset password */
  //       $to = $email;
  //       $subject = "reset password link";
  //       $header = "HelpMyCity";
  //       $body = "to reset your password visit the link below : 
  //      helpmycity/updatepassword.php?email=$email&code=$code";
  
  //       $sent=mail($to,$subject,$body,$header);
        
  //        # code...
  //     if ($inserted) { /* update is successfull */
  //      # code...
  //      array_push($errors, "Check your mail we have sent you reset link to change your password!");
  
  //     }
  //    }
  //    else
  //    {
  //      array_push($errors,"Oops! Sorry, $email dose not belong to any account!");
  //    }
  
  //   } else {
  //     array_push($errors,"$email is not a valid email address");
  //   }
  //  }  

   //submit feedback(text)
  if (isset($_POST['saveTEXT'])) {

    $feedtitle = mysqli_real_escape_string($readDB, trim($_POST['title']));
    if (empty($feedtitle)) {
      array_push($errors, "Title is required");
      }
    $feedbackfrom = $_SESSION['username'];
    $feedcomment = mysqli_real_escape_string($readDB, trim($_POST['comments']));
    if (empty($feedcomment)) {
      array_push($errors, "Comment is required");
      }
    if (count($errors) == 0) {
      $query = "INSERT INTO feedbacks (title, feedback, feedbackfrom) 
            VALUES('$feedtitle', '$feedcomment', '$feedbackfrom')";
      mysqli_query($writeDB, $query);
      array_push($errors, "Your feedback has been successfully submitted!");
      }
  }
   //submit feedback(file)
  if (isset($_POST['savePDF'])) {
    $feedtitle = mysqli_real_escape_string($readDB, trim($_POST['title']));
    if (empty($feedtitle)) {
      array_push($errors, "Title is required");
      }

    $feedbackfrom = $_SESSION['username'];
    // File upload path
    // $file=$_FILES['file'];
    $fileName=$_FILES['file']['name'];
    $targetDir = "uploads/";
    $targetfolder = $targetDir . $fileName  ;
    $file_type=$_FILES['file']['type'];
    if(empty($fileName)){
      array_push($errors,"Please select a file to upload.");
    }
    else{
      if ($file_type=="application/pdf"){
        if(move_uploaded_file($_FILES['file']['tmp_name'], $targetfolder)){          
          if (count($errors) == 0) {
            $query = "INSERT INTO feedbacks (title, pdffile, feedbackfrom) 
                  VALUES('$feedtitle', '$fileName', '$feedbackfrom')";
            if(mysqli_query($writeDB, $query)){
              array_push($errors, "Your feedback has been successfully submitted!");
            }
            else{
              array_push($errors, "Unable to upload your file!");
            }
            }
  
        }  
        else{
          array_push($errors, "Sorry,error uploading your file. Please try again.");
          }
      }
      else {
        array_push($errors,"You may only upload PDFs");
       }

    }
    
          // $query = "INSERT INTO users (username, email, password) VALUES('$username', '$email', '$password')";
          // mysqli_query($writeDB, $query);
    }

    #admin login
    if (isset($_POST['login_admin'])) {
      if(isset($_SESSION['admin'])){
        array_push($errors,"You are already logged in. Logout to login using another account.");
        header("index.php");
      }
      else
        $username = mysqli_real_escape_string($readDB, trim($_POST['username']));
        $password = mysqli_real_escape_string($readDB, trim($_POST['password']));
      
        if (empty($username)) {
            array_push($errors, "Username is required");
        }
        if (empty($password)) {
            array_push($errors, "Password is required");
        }
      
        if (count($errors) == 0) {
            $password = md5($password);
            $query = "SELECT * FROM dbadmin WHERE username='$username' AND password='$password'";
            $results = mysqli_query($readDB, $query);
            if (mysqli_num_rows($results) == 1) {
              $_SESSION['admin'] = $username;
              $_SESSION['success'] = "You are now logged in";
              header('location: secretword.php');
            }else {
                array_push($errors, "Wrong username/password combination");
            }
        }
      }
    #admin secret word confirmation
    if(isset($_POST['confirm_admin'])){
      $sw = md5($sw);
      $_SESSION['admin'] = $username;
      $query = "SELECT * FROM dbadmin WHERE username='$username'";
            $results = mysqli_query($readDB, $query);
            $user = mysqli_fetch_assoc($results);
            if ($user['secretword']===$sw) {
              $_SESSION['admin'] = $username;
              $_SESSION['success'] = "You are now logged in";
              header('location: admin.php');
            }else {
                array_push($errors, "Wrong username/password combination");
            }
    }
      
  
  $readDB->close();
  $writeDB->close();
  $db->close();
  
  ?>