<?php	include('server.php');?>
<?php 
if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 120)) {
	// last request was more than 2 minutes ago
	$sessionExpire=$_SESSION['username'];
	$insertSessionID="UPDATE dbadmin SET sessionID = '' WHERE username='$sessionExpire'";
	mysqli_query($db,$insertSessionID);
	session_unset();     // unset $_SESSION variable for the run-time 
	session_destroy();   // destroy session data in storage
	array_push($errors,"You have been inactive for a while now. Please log in again");
	header('Location:adminlogin.php');
  }
  $_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp
  
	if (!isset($_SESSION['username'])) {
		// $_SESSION['msg'] = "You must log in first";
		array_push($errors,"You must log in first");
		header('location: adminlogin.php');
	}
	if (isset($_GET['logout'])) {
		$sessionuser=$_SESSION['username'];
		$reset="UPDATE dbadmin SET sessionID = '' WHERE username='$sessionuser'";
		$queryq = "SELECT * FROM dbadmin WHERE username='$sessionuser'";
		$results = mysqli_query($readDB, $queryq);
		$user = mysqli_fetch_assoc($results);
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
				header("location: adminlogin.php");
			}
			else{
				
			}
	
		}
		else{
			header("location: 403.php");
		}
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Home</title>
	<link rel="stylesheet" type="text/css" href="/styles/userHome.css">
	<link rel="stylesheet" type="text/css" href="/styles/form.css">
	<link rel="stylesheet" type="text/css" href="/styles/admintable.css">
	<link rel="stylesheet" href="/styles/bootstrap.css" >
	<script src="scripts/main.js"></script>
	<script>
		function displayUsers() { 
			document.getElementById("listallusers").style.display="block"; 
			document.getElementById("listallfeedbacks").style.display="none"; 
		}
		function displayFeeds() { 
			document.getElementById("listallusers").style.display="none"; 
			document.getElementById("listallfeedbacks").style.display="block"; 
		}
		function uploadfile() { 
			if(document.getElementById("largefile").checked){
				document.getElementById("fileupload").style.display="block"; 
				document.getElementById("comments").style.display="none";
			}
			else{
				document.getElementById("fileupload").style.display="none"; 
				document.getElementById("comments").style.display="block";
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
		// echo $_SESSION['success']; 
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
	$query = "SELECT * FROM dbadmin WHERE username='$sessionuser'";
	$results = mysqli_query($readDB, $query);
	if (mysqli_num_rows($results) == 1) :
		$user = mysqli_fetch_assoc($results);
		if($user['sessionID']===$sessionIDCrypted):
 

?>

<header  class="nav-page-header">
  <nav>
    <ul class="admin-menu">
      <li class="menu-heading">
		
        <h3>Welcome <strong><?php echo $_SESSION['username']; ?></strong></h3>
      </li>
	  <li>
		  <a onclick="displayUsers()" class="listusers" id="listusers" name="listusers"><h3>LIST USERS</h3></a>
	  </li>
	  <li>
		  <a onclick="displayFeeds()" class="listfeedbacks" id="listfeedbacks" name="listFeedback"><h3>LIST FEEDBACKS</h3></a>
	  </li>
	  <li>
	  	<a href="admin.php?logout='1'" style="color: red;"><h3>Logout</h3></a>
	  </li>
    </ul>
  </nav>
</header>


<section class="page-content">
	<section id="listallusers">
	<div class="limiter">
		<div class="container-table100">
			<div class="wrap-table100">
					<div class="table">

						<div class="row header">
							<div class="cell">
								Username
							</div>
							<div class="cell">
								Email
							</div>
						</div>


		<?php
		$users = "SELECT * FROM users";
		$userresults = mysqli_query($readDB, $users);
		$allusers=array();
		while($oneuser = mysqli_fetch_assoc($userresults)){
			$allusers[]=$oneuser;
		}
		foreach($allusers as $oneuser){
			?>
			

			<div class="row">
							<div class="cell" data-title="Full Name">
								<?php echo $oneuser['username'] ?>
							</div>
							<div class="cell" data-title="Job Title">
								<?php echo $oneuser['email'] ?>
							</div>
							<?php
							if ($oneuser['banstatus']=='yes') {?>
								<div class="cell" data-title="">
									<a href="unban.php?name=<?php echo $oneuser['username'];?>" id="ban" class="ban">Unban</a>
								</div>
								<?php
							}
							elseif($oneuser['banstatus']=='no'){?>
							<div class="cell" data-title="">
								<a href="ban.php?name=<?php echo $oneuser['username'];?>" id="ban" class="ban">Ban</a>
							</div>
							<?php
							}
							?>
						</div>
		<?php
		}
		?>
					</div>
				</div>
			</div>
		</div>
	</section>
	<section id="listallfeedbacks" style="display: none;" >
	<div class="limiter" >
		<div class="container-table100">
			<div class="wrap-table100">
					<div class="table">

						<div class="row header">
							<div class="cell">
								Title
							</div>
							<div class="cell">
								Feedback
							</div>
							<div class="cell">
								Feedback From
							</div>
						</div>


		<?php
		$feeds = "SELECT * FROM feedbacks";
		$feedresults = mysqli_query($readDB, $feeds);
		$allfeeds=array();
		while($onefeed = mysqli_fetch_assoc($feedresults)){
			$allfeeds[]=$onefeed;
		}
		foreach($allfeeds as $onefeed){
			?>
			

			<div class="row">
							<div class="cell" data-title="Full Name">
								<?php echo $onefeed['title'] ?>
							</div>
							<div class="cell" data-title="Job Title">
								<?php
								if(empty($onefeed['feedback'])){
									$file=$onefeed['pdffile']
									?>
									<a href='/uploads/<?php echo $onefeed['pdffile']?>' download><?php echo $onefeed['pdffile'] ;?></a> 
								<?php
								}else{
								echo substr($onefeed['feedback'],0,30);
								echo'<br><button type="submit" name="ban" id="readmore" class="ban">Read more</button>';
								}
							// endif;
							 ?>
							</div>
							<div class="cell" data-title="Job Title">
								<?php echo $onefeed['feedbackfrom'] ?>
							</div>
							<!-- <div class="cell" data-title="">
								<button type="submit" name="ban" id="ban" class="ban">Ban</button>
							</div> -->
						</div>
		<?php
		}
		?>
					</div>
				</div>
			</div>
		</div>
	</section>

</section>
<?php 

// else:
// 	header("location: 403.php");


else:
	header("location: 403.php");

endif;
else:
	header("location: index.php");

endif;
else:
	header("location: adminlogin.php");

endif;

?>
  </body>
</html>