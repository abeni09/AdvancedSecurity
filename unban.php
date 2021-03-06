<?php include('server.php');?>
<?php
    $sessionExpire=$_SESSION['username'];
    $sessID=session_id();

if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 120)) {
    // last request was more than 2 minutes ago
    $insertSessionID="UPDATE dbadmin SET sessionID = '' WHERE username='$sessionExpire'";
    mysqli_query($db,$insertSessionID);
    session_unset();     // unset $_SESSION variable for the run-time 
    session_destroy();   // destroy session data in storage
    array_push($errors,"You have been inactive for a while now. Please log in again");
    header('Location:adminlogin.php');
  }
  $_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp
  
  if (isset($_GET['name']) & isset($_GET['token'])) {

    $link=$_GET['name'];
    $tok=$_GET['token'];
    $id=mysqli_real_escape_string($readDB,$link);
    $token=mysqli_real_escape_string($readDB,$tok);
    
    if (!$token || $token !== $_SESSION['admintoken']) {
        // return 405 http status code
        header($_SERVER['SERVER_PROTOCOL'] . ' 405 Method Not Allowed');
        exit;
    } 
    else {
    if (isset($_SESSION['username'])) {
            $selAQ=mysqli_query($readDB,"SELECT * from dbadmin Where username= '$sessionExpire'");
            if(mysqli_num_rows($selAQ)==1){
                $adminfetch = mysqli_fetch_assoc($selAQ);
                if ($adminfetch['sessionID']==md5($sessID)) {
                    $selFUQ=mysqli_query($readDB,"SELECT * from users Where username= '$id'");
                    $userfetch=mysqli_fetch_assoc($selFUQ);
                    // echo mysqli_num_rows($selFUQ);
                    if(mysqli_num_rows($selFUQ)!=1){
                        echo "No user with such name";
                        echo $sessionExpire;
                        header('location:admin.php');
                    }
                    elseif(mysqli_num_rows($selFUQ)==1){
                        if ($userfetch['banstatus']=='no') {
                            echo "user is not banned";
                            header('location:admin.php');
                        }
                        else{
                            if (mysqli_query($db,"UPDATE users SET banstatus = 'no' WHERE username='$id'")) {
                                echo "user unbanned";
                                header('location:admin.php');
                            }
                            else{
                                echo "unable to unban user";
                                header('location:admin.php');
                            }
                        
                        }
                        }
                    }
                    else{
                        echo "admin not logged in yet";
                        header('location:admin.php');
                    }
    }
    else{
        echo "You are not the admin";
        header('location:admin.php');
    }
}
    


    else{
        header('location:login.php');
        echo "no session";
    }
}
}
else{
    echo "no user selected to ban";

}


?>