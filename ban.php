<?php include('config.php');?>
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
                        
                        if ($userfetch['banstatus']==='yes') {
                            echo "user is already banned";
                            header('location:admin.php');
                        }
                        elseif($userfetch['banstatus']==='no'){
                            if (empty($userfetch['sessionID'])) {
                                if (mysqli_query($db,"UPDATE users SET banstatus = 'yes' WHERE username='$id'")) {
                                    echo "user banned";
                                    unset($_SESSION['token']);
                                    header('location:admin.php');
                                }
                            }
                            elseif(!empty($userfetch['sessionID'])){
                                $redirect=header('location:admin.php');
                                echo '
                                    <script>
                                        let isConfirmed= confirm("The user is logged in. Are you sure you want to ban user anyway?");
                                        if(isConfirmed){';
                                                
                                        $ban=mysqli_query($db,"UPDATE users SET banstatus = 'yes' WHERE username='$id'");
                                        $clearSession=mysqli_query($db,"UPDATE users SET sessionID = '' WHERE username='$id'");
                                        unset($_SESSION['token']);
                                        echo'
                                        }
                                        else{
                                            
                                            
                                        }
                                    </script>
                                ';
                            }
                        
                        }
                        }
                    }
                    else{
                        header('location:admin.php');
                        echo "admin not logged in yet";
                    }
    }
    else{
        header('location:admin.php');
        echo "You are not the admin";
    }
}
    


    else{
        // header('location:login.php');
        echo "no session";
    }
}
}
else{
    
    // header('location:admin.php');
    echo "no user selected to ban";

}


?>