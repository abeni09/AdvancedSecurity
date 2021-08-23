<?php	include('server.php');?>
<?php 


	if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 1800)) {
		// last request was more than 30 minutes ago
		session_unset();     // unset $_SESSION variable for the run-time 
		session_destroy();   // destroy session data in storage
	}
	$_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp
	if (!isset($_SESSION['username'])) {
		$_SESSION['msg'] = "You must log in first";
		header('location: login.php');
	}
	if (isset($_GET['logout'])) {
		session_destroy();
		unset($_SESSION['username']);
		header("location: login.php");
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Home</title>
	<link rel="stylesheet" type="text/css" href="styles/userHome.css">
	<link rel="stylesheet" type="text/css" href="styles/form.scss">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" >
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
		echo $_SESSION['success']; 
		unset($_SESSION['success']);
		?>
	</h3>
	</div>
<?php endif ?>

    <!-- logged in user information -->
<?php  if (isset($_SESSION['username'])) : ?>

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
	  	<a href="index.php?logout='1'" style="color: red;"><h3>Logout</h3></a>
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
<?php endif ?>
  </body>
</html>