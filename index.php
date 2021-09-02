<?php	include('server.php');?>
<?php 


	if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 300)) {
		// last request was more than 10 minutes ago
		$sessionExpire=$_SESSION['username'];
		$insertSessionID="UPDATE users SET sessionID = '' WHERE username='$sessionExpire'";
		mysqli_query($db,$insertSessionID);
		session_unset();     // unset $_SESSION variable for the run-time 
		session_destroy();   // destroy session data in storage
		array_push($errors,"You have been inactive for a while now. Please log in again");
		header('Location:login.php');
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
<!DOCTYPE html>
<html>
<head>
	<title>Home</title>
	<link rel="stylesheet" type="text/css" href="/styles/userHome.css">
	<link rel="stylesheet" type="text/css" href="/styles/form.css">
	<link rel="stylesheet" type="text/css" href="/styles/table.css">
	<link rel="stylesheet" href="/styles/bootstrap.css" >
	<script src="/scripts/main.js"></script>
	<script>
		function displayForm() { 
			// document.getElementById("addfeedback").style.display="none"; 
			document.getElementById("form").style.display="block"; 
			document.getElementById("listfeedbacks").style.display="block"; 
			document.getElementById("lists").style.display="none"; 
		}
		function displayList() { 
			// document.getElementById("listfeedbacks").style.display="none"; 
			document.getElementById("lists").style.display="block"; 
			document.getElementById("addfeedback").style.display="block"; 
			document.getElementById("form").style.display="none"; 
		}
		function uploadfile() { 
			if(document.getElementById("largefile").checked){
				document.getElementById("reused_form").style.display="block"; 
				document.getElementById("used_form").style.display="none";
				document.getElementById("nolargefile").checked= true;
			}
		} 
		function uploadtext() { 
			if(!document.getElementById("nolargefile").checked){
				document.getElementById("used_form").style.display="block"; 
				document.getElementById("reused_form").style.display="none";
				document.getElementById("largefile").checked= false;
			} 
			else{
			}
		} 

	</script>
</head>
<body>

<!-- notification message -->
<?php if (isset($_SESSION['success'])) : ?>
	<div class="error success" >
	<h3>
		<?php 
		echo $_SESSION['success']; 
		unset($_SESSION['success']);
		?>
	</h3>
	</div>
<?php endif ?>

    <!-- logged in user information -->
<?php
$sessionID=session_id();
$sessionIDCrypted =md5($sessionID);
if (isset($_SESSION['username'])) :
	$sessionuser=$_SESSION['username'];
	// $insertSessionID="UPDATE users SET sessionID = '$sessionIDCrypted' WHERE username='$sessionuser'";
	$queryq = "SELECT * FROM users WHERE username='$sessionuser'";
	$results = mysqli_query($readDB, $queryq);
	if(mysqli_num_rows($results)== 1):
	// if (mysqli_query($db,$insertSessionID)) :
		$user = mysqli_fetch_assoc($results);
			$userSID=$user['sessionID'];
			if($userSID===$sessionIDCrypted):
			array_push($errors,$sessionIDCrypted);
			array_push($errors,$userSID);
 
	
?>

<header  class="nav-page-header">
  <nav>
    <ul class="admin-menu">
      <li class="menu-heading">
		
        <h3>Welcome <strong><?php echo $_SESSION['username']; ?></strong></h3>
      </li>
	  <li>
		  <a onclick="displayForm()" class="addfeedback" id="addfeedback" name="newFeedback"><h3>ADD NEW FEEDBACK</h3></a>
	  </li>
	  <li>
		  <a onclick="displayList()" class="listfeedbacks" id="listfeedbacks" name="listFeedback"><h3>LIST FEEDBACKS</h3></a>
	  </li>
	  <li>
	  	<a href="index.php?logout='1'" style="color: red;"><h3>Logout</h3></a>
	  </li>
    </ul>
  </nav>
</header>


<section class="page-content">
		<div id="form" class="container">
            <div class="rows " style="margin-top: 50px">
                <div  class="col-md-6 col-md-offset-3 form-container">
                    <h2>Feedback</h2> 
                    <p> Please provide your feedback below: </p>
                    <form role="form" action="index.php" method="post" id="used_form"  >
						<?php include('errors.php'); ?>
						<div class="rows">
                            <div class="col-sm-12 form-group">
                                <label for="title"> Title</label>
                                <input required class="form-control" type="text" name="title" id="title" placeholder="Your title"/>
                            </div>
                        </div>
						<div id="comments"  class="rows">
                            <div class="col-sm-12 form-group">
                                <label for="comments"> Comments</label>
                                <textarea style="resize: none;" required class="form-control" type="textarea" name="comments" id="comments" placeholder="Your Comments" maxlength="6000" rows="7"></textarea>
                            </div>
                        </div>
						<div class="rows">
                            <div class="col-sm-12 form-group">
                                <input onclick="uploadfile()" type="checkbox" name="largefile" id="largefile" class="largefile" value="file"/>
								<label for="largefile"> I want to upload a pdf file</label>
                            </div>
                        </div>
                        <div class="s">
                            <div class="col-sm-12 form-group">
                                <button type="submit" class="btn btn-lg btn-block postbtn" name="saveTEXT">Post </button>
                            </div>
                        </div>
                    </form>

					<form style="display: none;" role="form" action="index.php" method="post" id="reused_form"  enctype="multipart/form-data">
						<?php include('errors.php'); ?>
						<div class="rows">
                            <div class="col-sm-12 form-group">
                                <label for="title"> Title</label>
                                <input required class="form-control" type="text" name="title" id="title" placeholder="Your title"/>
                            </div>
                        </div>
						<div class="rows">
                            <div class="col-sm-12 form-group">
                                <input checked onclick="uploadtext()" type="checkbox" name="nolargefile" id="nolargefile" class="largefile" value="file"/>
								<label for="nolargefile"> I want to upload a pdf file</label>
                            </div>
                        </div>
						<div id="fileupload" class="rows">
                            <div class="col-sm-12 form-group">
                            	<input name="file" type="file" id="file" class="feedback-input">
							</div>
                        </div>
                        <div class="rows">
                            <div class="col-sm-12 form-group">
                                <button type="submit" class="btn btn-lg btn-block postbtn" name="savePDF">Post </button>
                            </div>
                        </div>
                    </form>
                    
                    <div id="success_message" style="width:100%; height:100%; display:none; "> <h3>Posted your feedback successfully!</h3> </div>
                    <div id="error_message" style="width:100%; height:100%; display:none; "> <h3>Error</h3> Sorry there was an error sending your form. </div>
                </div>
            </div>
        </div>
<section id="lists" style="display: none;" >
<table id="customers">
  <tr>
    <th>Title</th>
    <th>Feedback</th>
  </tr>

		<?php
		$feeds = "SELECT * FROM feedbacks where feedbackfrom='$sessionuser'";
		$feedresults = mysqli_query($readDB, $feeds);
		$allfeeds=array();
		while($onefeed = mysqli_fetch_assoc($feedresults)){
			$allfeeds[]=$onefeed;
		}
		foreach($allfeeds as $onefeed){
			?>
			  <tr>
				<td><?php echo $onefeed['title'] ?></td>
				<td>
								<?php
								
								$pdfortext;
								if(empty($onefeed['feedback'])){
									$file=$onefeed['pdffile'];
									$pdfortext='pdf';
									?>
									<a download href="/uploads/<?php echo $onefeed['pdffile'] ;?>" name='download'><?php echo $onefeed['pdffile'] ;?></a>
								<?php
								}
								else{
									$pdfortext='text';
									echo (substr($onefeed['feedback'],0,100).'...<button type="submit" class="ban" name="readmore">Read More</button>');
							}
							 ?>
				</td>
			<td>
				<a href="review.php?id=<?php echo $onefeed['id'].'&top='.$pdfortext?>">Edit</a>
			</td>
			<td>
				<a href="delete.php?id=<?php echo $onefeed['id']?>">Delete</a>
			</td>
			</tr>
								
		<?php
		}
		?>
		</table>
	</section>

</section>
<?php 

else:
	header("location: 403.php");

endif;
else:
	header("location: admin.php");

endif;
else:
	header("location: login.php");

endif;
 ?>
  </body>
</html>