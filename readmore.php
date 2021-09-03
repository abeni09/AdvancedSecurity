<style>
    .all-browsers {
        margin: 0;
        padding: 5px;
        background-color: lightgray;
    }
    
    .all-browsers > h1, .browser {
        margin: 10px;
        padding: 5px;
    }
    
    .browser {
        background: white;
    }
    
    .browser > h2, p {
        margin: 4px;
        font-size: 90%;
    }
</style>
<?php include('server.php');?>
<?php
if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 120)) {
    // last request was more than 2 minutes ago
    $sessionExpire=$_SESSION['username'];
    $sessID=session_id();
    $insertSessionID="UPDATE users SET sessionID = '' WHERE username='$sessionExpire'";
    mysqli_query($db,$insertSessionID);
    session_unset();     // unset $_SESSION variable for the run-time 
    session_destroy();   // destroy session data in storage
    array_push($errors,"You have been inactive for a while now. Please log in again");
    // header('Location:adminlogin.php');
  }
  $_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp
  
if (isset($_GET['id'])) {
    $link=$_GET['id'];
    $id=mysqli_real_escape_string($readDB,$link);
    if (isset($_SESSION['username'])) {
        $selFUQ=mysqli_query($readDB,"SELECT * from feedbacks Where id= $id");
        if(mysqli_num_rows($selFUQ)!=1){
            echo "No file with such id";
            // header('location:index.php');
        }
        elseif(mysqli_num_rows($selFUQ)==1){
            $userfetch = mysqli_fetch_assoc($selFUQ);
            $tryinguser=$userfetch['feedbackfrom'];
            $tuser=strval($tryinguser);
            // echo $tuser;
            
            $selUQ=mysqli_query($readDB,"SELECT * from users Where username= '$tuser'");
            if(mysqli_num_rows($selUQ)!=1){
                echo "Unauthorized access";
            }
            elseif(mysqli_num_rows($selUQ)==1){
                $userDetail = mysqli_fetch_assoc($selUQ);
                if ($tryinguser==$_SESSION['username']) {
                    $userSession=$userDetail['sessionID'];
                    if (empty($userSession)) {
                        // header('location:index.php');
                        echo "no session user";
                    }
                    else{
                        if ($userDetail['sessionID']===md5(session_id())) {
                            echo '                         
                            <article class="all-browsers">
                              <article class="browser">
                                <h2>'.$userfetch['title'].'</h2>
                                <p>'.$userfetch['feedback'].'</p>
                              </article>
                              
                            </article>
                            ';
                        }
                        else{
                            // header('location:index.php');
                            echo "session id does not match";
                            
                        }
                    }
                }
                else{
                    $sessionExpired=$_SESSION['username'];
                    $selAQ=mysqli_query($readDB,"SELECT * from dbadmin Where username= '$sessionExpired'");
                    if (mysqli_num_rows($selAQ)==1) {
                        $sID=session_id();
                        $adminfetch = mysqli_fetch_assoc($selAQ);
                        if ($adminfetch['sessionID']==md5($sID)) {
                                echo '
                                <article class="all-browsers">
                                    <article class="browser">
                                        <h2>'.$userfetch['title'].'</h2>
                                        <p>'.$userfetch['feedback'].'</p>
                                    </article>
                              
                                </article>
                                ';
                            }
                            else{
                                // header('location:admin.php');
                                echo "admin not logged in yet";
                            }
                    }
                }
            }
            else{
                echo "User trying to attack";
            }
        }
        else{
            echo "User trying to attack";
        }
    }
    else{
        // header('location:login.php');
        echo "no session";
    }
}
else{
    echo "no file selected to delete";

}
    
?>