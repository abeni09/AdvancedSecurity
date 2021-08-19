<?php 
  session_start(); 

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
	<link rel="stylesheet" type="text/css" href="styles/userHome.scss">
	<link rel="stylesheet" type="text/css" href="styles/form.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" >
	<script src="scripts/main.js"></script>
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

<header class="nav-page-header">
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
            <div class="row " style="margin-top: 50px">
                <div class="col-md-6 col-md-offset-3 form-container">
                    <h2>Feedback</h2> 
                    <p> Please provide your feedback below: </p>
                    <form role="form" action="index.php" method="post" id="reused_form"  enctype=&quot;multipart/form-data&quot;>
						<div class="row">
                            <div class="col-sm-12 form-group">
                                <label for="comments"> Title</label>
                                <input class="form-control" type="text" name="title" id="title" placeholder="Your title"/>
                            </div>
                        </div>
						<div class="row">
                            <div class="col-sm-12 form-group">
                                <label for="comments"> Comments</label>
                                <textarea class="form-control" type="textarea" name="comments" id="comments" placeholder="Your Comments" maxlength="6000" rows="7"></textarea>
                            </div>
                        </div>
						<div class="row">
                            <div class="col-sm-12 form-group">
                            <input name="pdf" type="file" id="file" class="feedback-input">
						</div>
                        </div>
						
                        <p class="file">
                        </p>
                        <div class="row">
                            <div class="col-sm-12 form-group">
                                <button type="submit" class="btn btn-lg btn-block postbtn" name="save">Post </button>
                            </div>
                        </div>
                    </form>
                    <div id="success_message" style="width:100%; height:100%; display:none; "> <h3>Posted your feedback successfully!</h3> </div>
                    <div id="error_message" style="width:100%; height:100%; display:none; "> <h3>Error</h3> Sorry there was an error sending your form. </div>
                </div>
            </div>
        </div>
            
	<section id="lists" class="grid">
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