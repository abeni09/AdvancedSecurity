<?php include('server.php');?>
<?php
if (isset($_GET['id'])) {
    $link=$_GET['id'];
    $id=mysqli_real_escape_string($readDB,$link);
    if (isset($_SESSION['username'])) {
        $selFUQ=mysqli_query($readDB,"SELECT * from feedbacks Where id= $id");
        if(mysqli_num_rows($selFUQ)!=1){
            echo "No file with such id";
            header('location:index.php');
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
                        header('location:index.php');
                        echo "no session user";
                    }
                    else{
                        if ($userDetail['sessionID']===md5(session_id())) {
                            $delQ="DELETE from feedbacks where id='$id'";
                            // echo $userfetch['pdffile'];
                            $deletefiles = glob('uploads/'.$userfetch['pdffile']); // get file name
                            foreach($deletefiles as $dfile){ // iterate files
                                if(is_file($dfile)) {
                                    unlink($dfile); // delete file
                                }
                                mysqli_query($db,$delQ);
                                echo "success";
                                array_push($errors,"Feedback successfully deleted!");
                                header('location:index.php');
                            }
                        }
                        else{
                            header('location:index.php');
                            echo "session id does not match";
                            
                        }
                    }
                }
                else{
                    header('location:index.php');
                    echo "session user name do not match";
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
        header('location:login.php');
        echo "no session";
    }
}
else{
    echo "no file selected to delete";

}
    
?>