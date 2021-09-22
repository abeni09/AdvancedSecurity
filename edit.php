<?php include('server.php');?>
<?php
if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 300)) {
    // last request was more than 10 minutes ago
    $sessionExpire=$_SESSION['username'];
    $insertSessionID="UPDATE users SET sessionID = '' WHERE username='$sessionExpire'";
    mysqli_query($db,$insertSessionID);
    unset($_SESSION['username']);
    session_unset();     // unset $_SESSION variable for the run-time 
    session_destroy();   // destroy session data in storage
    array_push($errors,"You have been inactive for a while now. Please log in again");
    header('Location:login.php');
  }
  $_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp
  
if (isset($_GET['id']) & isset($_GET['top']) & isset($_GET['token'])) {
    $link=$_GET['id'];
    $link2=mysqli_real_escape_string($readDB,$_GET['top']);
    $tok=$_GET['token'];
    $id=mysqli_real_escape_string($readDB,$link);
    $token=mysqli_real_escape_string($readDB,$tok);
    if (isset($_SESSION['token'])) {
            
    if (!$token || $token !== $_SESSION['token']) {
        // return 405 http status code
        header($_SERVER['SERVER_PROTOCOL'] . ' 405 Method Not Allowed');
        // exit;
    } 
    else {
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
                        echo "This is not yours to edit...Bad boy";
                    }
                    else{
                        if ($userDetail['sessionID']===md5(session_id())) {
                            // $delQ="DELETE from feedbacks where id='$id'";
                            // mysqli_query($db,$delQ);
                            // header('location:review.php?id='. $id . '&top=' .substr(md5($link2),1,7));
                            $updatedTitle=$userfetch['title'];
                            // echo $updatedTitle;
                            $updatedFeed=$userfetch['feedback'];
                            $updatedFile=$userfetch['pdffile'];
                            // echo $updatedFile;
                            if (isset($_POST['cancel'])) {
                                header('Location:index.php');
                                unset($_SESSION['token']);
                            }
                            if(isset($_POST['editFEED'])){
                                
                                $feedtitle = mysqli_real_escape_string($readDB, trim(htmlEntities($_POST['title'])));
                                $feedbackfrom = $_SESSION['username'];
                                $feedcomment = mysqli_real_escape_string($readDB, trim(htmlEntities($_POST['comments'])));
                                $edittoken=mysqli_real_escape_string($readDB, trim($_POST['token']));
                                if (!$edittoken || $edittoken !== $_SESSION['edittoken']) {
                                    // return 405 http status code
                                    header($_SERVER['SERVER_PROTOCOL'] . ' 405 Method Not Allowed');
                                    // exit;
                                }
                                else{
                                if (empty($feedtitle)) {
                                  array_push($errors, "Title is required");
                                  }
                                if (empty($feedcomment)) {
                                  array_push($errors, "Comment is required");
                                  }
                                if (count($errors) == 0) {
                                  $query ="UPDATE feedbacks set title='$feedtitle', feedback='$feedcomment' where id='$id'";
                                  if(mysqli_query($db, $query)){
                                    array_push($errors, "Your feedback has been successfully edited!");
                                    header('Location:index.php');
                                    unset($_SESSION['token']);
                                  }
                                  else{
                                    array_push($errors, "Unable to submit your feedback!");}
                                  }
                            }
                        }

                            // array_push($errors,"Feedback successfully edited!");
                        }
                        else{
                            // header('location:index.php');
                            echo "session id does not match";
                            
                        }
                        if (isset($_POST['editFILE'])) {
                            $feedtitle = mysqli_real_escape_string($readDB, trim(htmlEntities($_POST['title'])));
                            $edittoken=mysqli_real_escape_string($readDB, trim($_POST['token']));
                            if (!$edittoken || $edittoken !== $_SESSION['edittoken']) {
                                // return 405 http status code
                                header($_SERVER['SERVER_PROTOCOL'] . ' 405 Method Not Allowed');
                                // exit;
                            }
                            else{
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
                                      echo $id;
                                    $queryF = "UPDATE feedbacks set title='$feedtitle', pdffile='$fileName' WHERE id='$id'";
                                    if(mysqli_query($db, $queryF)){
                                      array_push($errors, "Your feedback has been successfully edited!");
                                      header('Location:index.php');                                      
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
                }
                else{
                    header('location:index.php');
                    echo "session user name do not match";
                }
            }
            else{
                header('location:index.php');
                echo "User trying to attack";
            }
        }
        else{
            header('location:index.php');
            echo "User trying to attack";
        }

    }
    else{
        header('location:login.php');
        echo "no session";
    }
}
}
else {
    
    header('location:index.php');
    echo "no token";

}

}else{
    header('location:index.php');
    echo "no file selected to be edited";
}


if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 600)) {
    // last request was more than 10 minutes ago
    $sessionuser=$_SESSION['username'];
    $reset="UPDATE users SET sessionID = '' WHERE username='$sessionuser'";
    $queryq = "SELECT * FROM users WHERE username='$sessionuser'";
    $results = mysqli_query($readDB, $queryq);
    $user = mysqli_fetch_assoc($results);
    // $sessionEmpty='';
    $userUpdated=mysqli_query($db,$reset);

    if(md5(session_id()) == $user['sessionID']){
        if($userUpdated){
            $_SESSION = array();
            if (ini_get("session.use_cookies")) {
                $params = session_get_cookie_params();
                setcookie(session_name(), '', time() - 42000);
            }
            // session_unset();
            session_destroy();
            // unset($_SESSION['username']);
            // unset(session_id());
            header("location: login.php");
        }
        else{

        }

    }  // destroy session data in storage
    array_push($errors,"You have been inactive for a while now. Please log in again");
}
$_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp

if (!isset($_SESSION['username'])) {
    header('location: login.php');
}
if (isset($_GET['logout'])) {
    $sessionuser=$_SESSION['username'];
    $reset="UPDATE users SET sessionID = '' WHERE username='$sessionuser'";
    $queryq = "SELECT * FROM users WHERE username='$sessionuser'";
    $results = mysqli_query($readDB, $queryq);
    $user = mysqli_fetch_assoc($results);
    // $sessionEmpty='';
    $userUpdated=mysqli_query($db,$reset);

    if(md5(session_id()) == $user['sessionID']){
        if($userUpdated){
            $_SESSION = array();
            if (ini_get("session.use_cookies")) {
                $params = session_get_cookie_params();
                setcookie(session_name(), '', time() - 42000);
            }
            // session_unset();
            session_destroy();
            // unset($_SESSION['username']);
            // unset(session_id());
            header("location: login.php");
        }
        else{

        }

    }
    // else{
    // 	header("location: 403.php");
    // }
}



    
?>