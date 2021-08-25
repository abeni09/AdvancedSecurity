<?php	include('server.php');?>
<?php 


	if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 600)) {
		// last request was more than 10 minutes ago	
		$sessionExpire=$_SESSION['username'];
		$insertSessionID="UPDATE users SET sessionID = '' WHERE username='$sessionExpire'";
		mysqli_query($db,$insertSessionID);			
		$_SESSION = array();
		if (ini_get("session.use_cookies")) {
			$params = session_get_cookie_params();
			setcookie(session_name(), '', time() - 42000);
		}
		session_unset();     // unset $_SESSION variable for the run-time 
		session_destroy();   // destroy session data in storage
		array_push($errors,"You have been inactive for a while now. Please log in again");
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
	<link rel="stylesheet" type="text/css" href="styles/userHome.css">
	<link rel="stylesheet" type="text/css" href="styles/form.css">
	<link rel="stylesheet" href="styles/bootstrap.css" >
	<script src="scripts/main.js"></script>
	<script>
		function displayUsers() { 
			// document.getElementById("addfeedback").style.display="none"; 
			document.getElementById("listusers").style.display="block"; 
			document.getElementById("listfeedbacks").style.display="none"; 
		}
		function displayList() { 
			// document.getElementById("listfeedbacks").style.display="none"; 
			document.getElementById("listfeedbacs").style.display="block"; 
			document.getElementById("listusers").style.display="none";
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
$sessionuser=$_SESSION['username'];
$query = "SELECT * FROM dbadmin WHERE username='$sessionuser'";
$results = mysqli_query($readDB, $query);
if (mysqli_num_rows($results) == 1) :
	$user = mysqli_fetch_assoc($results);
	if (isset($_SESSION['username'])) :
		if(md5($user['sessionID'])===$sessionIDCrypted);
 

?>

<header  class="nav-page-header">
  <nav>
    <ul class="admin-menu">
      <li class="menu-heading">
		
        <h3>Welcome <strong><?php echo $_SESSION['username']; ?></strong></h3>
      </li>
	  <li>
		  <a onclick="displayForm()" class="listusers" id="listusers" name="listusers"><h3>LIST USERS</h3></a>
	  </li>
	  <li>
		  <a onclick="displayList()" class="listfeedbacks" id="listfeedbacks" name="listFeedback"><h3>LIST FEEDBACKS</h3></a>
	  </li>
	  <li>
	  	<a href="admin.php?logout='1'" style="color: red;"><h3>Logout</h3></a>
	  </li>
    </ul>
  </nav>
</header>


<section class="page-content">

	<section id="listusers" class="grid">
		<article></article>
		<article></article>
		<article></article>
		<article></article>
		<article></article>
		<article></article>
	</section>

	<section id="listfeedbacks" class="grid" style="display: none;">
		<article></article>
		<article></article>
		<article></article>
		<article></article>
		<article></article>
		<article></article>
	</section>

</section>
<?php 

// else:
// 	header("location: 403.php");

endif;

// else:
// 	header("location: 403.php");

endif;

// else:
// 	header("location: 403.php");

?>
  </body>
</html>