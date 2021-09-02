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
  
if (isset($_GET['name'])) {
    $link=$_GET['name'];
    $id=mysqli_real_escape_string($readDB,$link);
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
                        // header('location:admin.php');
                    }
                    elseif(mysqli_num_rows($selFUQ)==1){
                        
                        if ($userfetch['banstatus']==='yes') {
                            echo "user is already banned";
                        }
                        elseif($userfetch['banstatus']==='no'){
                            if (mysqli_query($db,"UPDATE users SET banstatus = 'yes' WHERE username='$id'")) {
                                echo "user banned";
                            }
                            else{
                                echo "unable to ban user";
                                echo $userfetch['banstatus'];
                            }
                        
                        }
                        }
                    }
                    else{
                        echo "admin not logged in yet";
                    }
    }
    else{
        echo "You are not the admin";
    }
}
    


    else{
        // header('location:login.php');
        echo "no session";
    }
}
else{
    echo "no user selected to ban";

}


?>