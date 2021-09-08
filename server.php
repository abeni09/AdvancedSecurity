<?php include('config.php') ?>
<?php
// REGISTER USER
if (isset($_POST['reg_user'])) {
  // echo"damn";
  // receive all input values from the form
  $username = mysqli_real_escape_string($readDB, trim(htmlEntities($_POST['username'])));
  $email = mysqli_real_escape_string($readDB, trim(htmlEntities($_POST['email'])));
  $password_1 = mysqli_real_escape_string($readDB, ($_POST['password_1']));
  $password_2 = mysqli_real_escape_string($readDB, ($_POST['password_2'])); 
  if (empty($username)) { array_push($errors, "Username is required"); }
  if ($username == "admin" or $username == "ADMIN" or $username == "AdMiN" or $username == "aDmIn") { array_push($errors, "Username already taken."); }
  if (empty($email)) { array_push($errors, "Email is required"); }
  if (empty($password_1)) { array_push($errors, "Password is required"); }
  if (strlen($password_1)<8) { array_push($errors, "Password must be atlease 8 characters long"); }
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
    // $sessionID=session_id();
    // $sessionID =md5($sessionID);
    $userType = "user";
  	$query = "INSERT INTO users (username, email, password) 
  			  VALUES('$username', '$email', '$password')";
  	mysqli_query($writeDB, $query);
  	// $_SESSION['success'] = "You are now logged in";
  	header('location: login.php');
  }
}

// LOGIN USER
if (isset($_POST['login_user'])) {
  if(isset($_SESSION['username'])){
    array_push($errors,"You are already logged in.");
    // header("index.php");
  }
  else
    $username = mysqli_real_escape_string($readDB, trim(htmlEntities($_POST['username'])));
    $password = mysqli_real_escape_string($readDB, ($_POST['password']));
  
    if (empty($username)) {
        array_push($errors, "Username is required");
    }
    if (empty($password)) {
        array_push($errors, "Password is required");
    }
  
    if (count($errors) == 0) {
        $password = md5($password);
        $sessionID=session_id();
        $sessionID =md5($sessionID);
        $query = "SELECT * FROM users WHERE username='$username' AND password='$password'";
        $updateUser="UPDATE users SET sessionID = '$sessionID' WHERE username='$username'";
        $results = mysqli_query($readDB, $query);
        if (mysqli_num_rows($results) == 1) {
          $userCred=mysqli_fetch_assoc($results);
          $status=$userCred['banstatus'];
          if ($status=='no') {
            $userUpdated=mysqli_query($db,$updateUser);
            if($userUpdated){
              $_SESSION['username'] = $username;
              // $_SESSION['success'] = "You are now logged in";
              header('location: index.php');
            }
            else{
              array_push($errors,"Lost connection to the server.");
            }
          }
          elseif($status=='yes'){
            array_push($errors,"Your account is temporarily banned! Contact your admin!");
            unset($_SESSION['username']);
            // mysqli_query($db,"UPDATE users SET sessionID = '' WHERE username='$username'");
          }
        }
        else {
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
    $texttoken = filter_input(INPUT_POST, 'token', FILTER_SANITIZE_STRING);
    if (!$texttoken || $texttoken !== $_SESSION['token']) {
        // return 405 http status code
        // header($_SERVER['SERVER_PROTOCOL'] . ' 405 Method Not Allowed');
        // exit;
        // echo 'vbnm,';
    } 
    else {
    $feedbackfrom = $_SESSION['username'];
    $userDetFetch=mysqli_query($readDB,"SELECT * from users WHERE username='$feedbackfrom'");
    if (mysqli_num_rows($userDetFetch)==1){
      $userDet=mysqli_fetch_assoc($userDetFetch);
      if (empty($userDet['sessionID'])) {
        unset($_SESSION['username']);
        // unset(session_id());
        header('Location:login.php');
        // array_push($errors,"what");
      }
      else{
        $feedtitle = mysqli_real_escape_string($readDB, trim(htmlEntities($_POST['title'])));
        if (empty($feedtitle)) {
          array_push($errors, "Title is required");
          }
        if (!empty($feedbackfrom)) {
            $feedcomment = mysqli_real_escape_string($readDB, trim(htmlEntities($_POST['comments'])));
            if (empty($feedcomment)) {
              array_push($errors, "Comment is required");
              }
            if (count($errors) == 0) {
              $query = "INSERT INTO feedbacks (title, feedback, feedbackfrom) 
                    VALUES('$feedtitle', '$feedcomment', '$feedbackfrom')";
              if(mysqli_query($writeDB, $query)){
                array_push($errors, "Your feedback has been successfully submitted!");
                unset($_SESSION['token']);
              }
              else{
                array_push($errors, "Unable to submit your feedback!");
              }
            }
          }
          else{
            array_push($errors,"User logged out due to inactivity");
          }
        }

    }
    else{
      header('location:login.php');
      // array_push($errors,"ohh");
    }
  }
    
  }
   //submit feedback(file)
  if (isset($_POST['savePDF'])) {
    $token = filter_input(INPUT_POST, 'token', FILTER_SANITIZE_STRING);
    if (!$token || $token !== $_SESSION['token']) {
        // return 405 http status code
        header($_SERVER['SERVER_PROTOCOL'] . ' 405 Method Not Allowed');
        exit;
    } 
    else {
    $feedbackfrom = $_SESSION['username'];
    $userDetFetch=mysqli_query($readDB,"SELECT * from users WHERE username='$feedbackfrom'");
    $userDet=mysqli_fetch_assoc($userDetFetch);
    if (empty($userDet['sessionID'])) {
      unset($_SESSION['username']);
      header('Location:login.php');
    }
    else{
      $feedtitle = mysqli_real_escape_string($readDB, trim(htmlEntities($_POST['title'])));
      if (empty($feedtitle)) {
        array_push($errors, "Title is required");
        }
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
        if (file_exists($targetfolder)) {
          $copy=rand(1000,9999);
          $fileName ="[" . $copy. "]" . $fileName ;
          $targetfolder = $targetDir . $fileName  ;
        }
        else{
          
        }
        if(move_uploaded_file($_FILES['file']['tmp_name'], $targetfolder)){  
          $finfo = finfo_open(FILEINFO_MIME_TYPE);
          $real_file_type=finfo_file($finfo, $targetfolder); 
          finfo_close($finfo);
          if ($real_file_type=="application/pdf"){       
            if (count($errors) == 0) { 
              $query = "INSERT INTO feedbacks (title, pdffile, feedbackfrom) 
                    VALUES('$feedtitle', '$fileName', '$feedbackfrom')";
              if(mysqli_query($writeDB, $query)){
                array_push($errors, "Your feedback has been successfully submitted!");
                unset($_SESSION['token']);
              }
              else{
                array_push($errors, "Unable to upload your file!");
              }
              }
    
          }  
          else{
            array_push($errors, "You may only upload PDFs");
            }
        }
        else {
          array_push($errors,"Sorry,error uploading your file. Please try again.");
        }
      }

    }
  }
 }

    #admin login
    if (isset($_POST['login_admin'])) {
      $time=time()-30;
      $ipAddr=getIP();
      $laquery=mysqli_query($readDB,"SELECT * from loginlogs where trytime<$time and ipAddress='$ipAddr'");
      // $loginRow=mysqli_fetch_assoc($laquery);
      $total_count=mysqli_num_rows($laquery);
      if(isset($_SESSION['username'])){
        array_push($errors,"You are already logged in.");
        // header("index.php");
      }
      else
        // array_push($errors,$total_count);
        $adminname = mysqli_real_escape_string($readDB, trim(htmlEntities($_POST['username'])));
        $adminpassword = mysqli_real_escape_string($readDB, ($_POST['password']));
        $sw = mysqli_real_escape_string($readDB, trim($_POST['sw']));
      
        if (empty($adminname)) {
            array_push($errors, "Username is required");
        }
        if (empty($adminpassword)) {
            array_push($errors, "Password is required");
        } 
        if (empty($sw)) {
          array_push($errors, "Secret Word is required");
        }
      
        if (count($errors) == 0) {
            $adminpassword = md5($adminpassword);
            $sw = md5($sw);
            $adminsessionID=session_id();
            $adminsessionID =md5($adminsessionID);
            $adminquery = "SELECT * FROM dbadmin WHERE username='$adminname' AND password='$adminpassword' AND secretword='$sw'";
            $adminresults = mysqli_query($readDB, $adminquery);
            $updateAdmin="UPDATE dbadmin SET sessionID = '$adminsessionID' WHERE username='$adminname'";
            $difference=0;

            // elseif($total_count>=3){
          
            // }
            if (isset($_SESSION["locked"])){
              $timequery=mysqli_query($readDB, "SELECT * FROM lastattempt WHERE ip='$ipAddr'");
                // echo mysqli_num_rows($timequery)-1;
                $timequeryresults = mysqli_fetch_assoc($timequery);
                // array_push($errors,"session locked");
                // array_push($errors,$timequeryresults['attempttime']-time());
                $difference = time() - $timequeryresults['attempttime'];
                // array_push($errors,$difference);
                // array_push($errors,time());
                if ($difference >= 30)
                {
                  mysqli_query($db,"DELETE from lastattempt where ip='$ipAddr'");
                  mysqli_query($db,"DELETE from loginlogs where ipAddress='$ipAddr'");
                  // echo"yay";
                    unset($_SESSION["locked"]);
                    unset($_SESSION["login_attempts"]);
                  
                  }
            }
            if($total_count==3){
              $Counter=30-$difference;
              $la=$_SESSION["locked"] = time();
              mysqli_query($writeDB,"INSERT into lastattempt(ip,attempttime) values ('$ipAddr','$la')");
              // $time=time();   
              array_push($errors,"Too many failed attempts. Please login after " .$Counter. " seconds.");
              if ($Counter<=0) {
                mysqli_query($db,"DELETE from lastattempt where ip='$ipAddr'");
              }
            }
            else{
            if (mysqli_num_rows($adminresults) == 1) {
                // $admin = mysqli_fetch_assoc($results); 
                $adminUpdated=mysqli_query($db,$updateAdmin);   
                if($adminUpdated){
                  $_SESSION['username'] = $adminname;
                  mysqli_query($db,"DELETE from loginlogs where ipAddress='$ipAddr'");
                  mysqli_query($db,"DELETE from lastattempt where ip='$ipAddr'");
                      // $_SESSION['success'] = "You are now logged in";
                  header('location: admin.php');  
                }
                else{
                  array_push($errors,"Lost connection to server.");
                }
            }
            else {
              $total_count++;
              $remAttempt=3-$total_count;
              if($remAttempt==0){
                array_push($errors,"Too many failed attempts.");
              }
              else{
                array_push($errors, "You have $remAttempt tries left! ");
              }
              mysqli_query($writeDB,"INSERT into loginlogs(ipAddress,trytime) values ('$ipAddr','$time')");                
            }
          }
        }
      }
    #admin secret word confirmation
    // if(isset($_POST['confirm_admin'])){
    //   $time=time()-10;
    //   $ipAddr=getIP();
    //   $laquery=mysqli_query($readDB,"SELECT * from loginlogs where trytime<'$time' and ipAddress='$ipAddr'");
    //   // $loginRow=mysqli_fetch_assoc($laquery);
    //   $total_count=mysqli_num_rows($laquery);
    //   if($total_count==3){
    //     array_push($errors,"Too many failed attempts. Please login after 10 seconds.");
    //   }
    //   else{
    //     // array_push($errors,$total_count);
    //     $sw = mysqli_real_escape_string($readDB, trim($_POST['sw']));
    //     $sw = md5($sw);
    //     $adname=$_SESSION['username'];
    //     $query = "SELECT * FROM dbadmin WHERE username='$adname'";
      
    //         if($results = mysqli_query($readDB, $query)){
    //           $admin = mysqli_fetch_assoc($results);
    //           if ($admin['secretword']===$sw) {
    //             $adminame=$_SESSION['username'];
    //             $_SESSION['username']=$adminname;
    //             mysqli_query($deleteDB,"DELETE from loginlogs where ipAddress='$ipAddr");
    //             header('location: admin.php');
               
    //             $user['username'] = $username;
    //             $_SESSION['success'] = "You are now logged in";
    //           }
    //           else {
    //             $total_count++;
    //             $remAttempt=3-$total_count;
    //             if($remAttempt==0){
    //               array_push($errors,"Too many login attempts. Please login after 5 minutes");
    //             }
    //             else{
    //               array_push($errors, "You have $remAttempt tries left");
    //             }
    //             mysqli_query($writeDB,"INSERT into loginlogs(ipAddress,trytime) values ('$ipAddr','$time')");
  
    //           }
            
              // array_push($errors, $user['secretword']);
              // array_push($errors, $user);
    //         }
    //         else{
    //           array_push($errors,"Unauthorized access.");
    //       }
    //     }
    // }
     
    
    //listfeedbacks
        // $_SESSION['username'] = $username;
        // $userret = array();
        // $usersql = "SELECT * FROM feedbacks WHERE username='$username'";
        // $userres = mysqli_query($readDB, $usersql);
        // if(mysqli_num_rows($userres) >= 1){
        //   while($userar = mysqli_fetch_assoc($userres))
        //   {
        //       $userret[] = $userar;
        //   }
        //   return $userret;
        // }
        // else{
        //   array_push($errors,"Unable to retrieve feedbacks.");
        // }

      // }
    //list all feedbacks
    // if(isset($_POST('listAllFeedback'))){
    //   $adminret = array();
    //   $adminsql = "SELECT * FROM feedbacks";
    //   $adminres = mysqli_query($readDB, $usersql);  

    //   while($adminar = mysqli_fetch_assoc($adminres))
    //   {
    //       $adminret[] = $adminar;
    //   }
    //   return $adminret;

    // }
    function getIP(){
      if(!empty($_SERVER['HTTP_CLIENT_IP'])){
        $ip=$_SERVER['HTTP_CLIENT_IP'];
      }
      elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
        $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
      }
      else{
        $ip=$_SERVER['REMOTE_ADDR'];  
      }
      return $ip;
    }
    //login attempt

    //delete feedback



  // $readDB->close();
  // $writeDB->close();
  // $db->close();
  // $updateDB->close();
  // $deleteDB->close();


  
  ?>
  