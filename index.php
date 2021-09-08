<?php
	include('server.php');	
	include('captcha.php');
?>
<?php 

// $_SESSION['token'] = bin2hex(random_bytes(35));


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
<html>
<head>
	<style>
		
		table {
		font-family: arial, sans-serif;
		border-collapse: collapse;
		width: 100%;
		}

		td, th {
		border: 1px solid #dddddd;
		text-align: left;
		padding: 8px;
		}

		tr:nth-child(even) {
		background-color: #dddddd;
		}
	</style>
	<title>Home</title>
	<style>
	*{
		list-style: none;
		text-decoration: none;
		margin: 0;
		padding: 0;
		box-sizing: border-box;
		font-family: 'Open Sans', sans-serif;
	}

	body{
		background: #f5f6fa;
	}

	.wrapper .sidebar{
		background: #114254;
		position: fixed;
		top: 0;
		left: 0;
		width: 225px;
		height: 100%;
		padding: 20px 0;
		transition: all 0.5s ease;
	}
	.wrapper .sidebar .profile{
		margin-bottom: 30px;
		text-align: center;
	}

	.wrapper .sidebar .profile img{
		display: block;
		width: 100px;
		height: 100px;
		border-radius: 50%;
		margin: 0 auto;
	}

	.wrapper .sidebar .profile h3{
		color: #ffffff;
		margin: 10px 0 5px;
	}

	.wrapper .sidebar .profile p{
		color: rgb(206, 240, 253);
		font-size: 14px;
	}
	.wrapper .sidebar ul li a{
		display: block;
		padding: 13px 30px;
		border-bottom: 1px solid #5F9EA0;
		color: rgb(241, 237, 237);
		font-size: 16px;
		position: relative;
	}

	.wrapper .sidebar ul li a .icon{
		color: #dee4ec;
		width: 30px;
		display: inline-block;
	}


	.wrapper .sidebar ul li a:hover,
	.wrapper .sidebar ul li a.active{
		color: #5F9EA0;

		background:white;
		border-right: 2px solid rgb(5, 68, 104);
	}

	.wrapper .sidebar ul li a:hover .icon,
	.wrapper .sidebar ul li a.active .icon{
		color: #5F9EA0;
	}

	.wrapper .sidebar ul li a:hover:before,
	.wrapper .sidebar ul li a.active:before{
		display: block;
	}
	.wrapper .section{
		width: calc(100% - 225px);
		margin-left: 225px;
		transition: all 0.5s ease;
	}

	.wrapper .section .top_navbar{
		background: #5F9EA0;
		height: 50px;
		display: flex;
		align-items: center;
		padding: 0 30px;

	}

	.wrapper .section .top_navbar .hamburger a{
		font-size: 28px;
		color: #f4fbff;
	}

	.wrapper .section .top_navbar .hamburger a:hover{
		color: #a2ecff;
	}
	body.active .wrapper .sidebar{
		left: -225px;
	}

	body.active .wrapper .section{
		margin-left: 0;
		width: 100%;
	}
	</style>
	<title>Home</title>
	<link rel="stylesheet" type="text/css" href="/styles/sidebar.css">
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
			document.getElementById("listfeedbacks").classList.remove("active");
			document.getElementById("addfeedback").classList.add("active");
		}
		function displayList() { 
			// document.getElementById("listfeedbacks").style.display="none"; 
			document.getElementById("lists").style.display="block"; 
			document.getElementById("addfeedback").style.display="block"; 
			document.getElementById("form").style.display="none"; 
			document.getElementById("addfeedback").classList.remove("active");
			document.getElementById("listfeedbacks").classList.add("active");
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
				$_SESSION['token'] = bin2hex(random_bytes(35)); 
				$_SESSION['adminfeedtoken'] = bin2hex(random_bytes(35)); 
 
	
?>
<header>
    <div class="wrapper">
        <!--Top menu -->
        <div class="sidebar">
           <!--profile image & text-->
		   <div class="profile">
                <!-- <img src="" alt="profile_picture"> -->
                <h3>Welcome</h3>
                <p><strong><?php echo $_SESSION['username']; ?></p>
            </div>
			<ul>
                <li>
                    <a onclick="displayForm()" class="active" id="addfeedback">
                        <span class="icon"><i class="fas fa-home"></i></span>
                        <span class="item">Add Feedback</span>
                    </a>
                </li>
                <li>
                    <a onclick="displayList()" id="listfeedbacks">
                        <span class="icon"><i class="fas fa-desktop"></i></span>
                        <span class="item">Feedbacks</span>
                    </a>
				</li>
                <li>
                    <a href="index.php?logout='1'" style="color: red;">
                        <span class="icon"><i class="fas fa-cog"></i></span>
                        <span class="item">Logout</span>
                    </a>
                </li>
            </ul>
    
        </div>

    </div>
</header>

<section class="page-content">
		<div id="form" class="container">
            <div class="rows " style="margin-top: 50px">
                <div  class="col-md-6 col-md-offset-3 form-container">
                    <h2>Feedback</h2> 
                    <p> Please provide your feedback below: </p>
                    <form role="form" action="index.php" method="post" id="used_form"  >
						<?php include('errors.php');
						?>
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
						<input type="hidden" name="token" value="<?php echo $_SESSION['token']?>">
						<div class="rows">
                            <div class="col-sm-12 form-group">
                                <input onclick="uploadfile()" type="checkbox" name="largefile" id="largefile" class="largefile" value="file"/>
								<label for="largefile"> I want to upload a pdf file</label>
                            </div>
                        </div>
						<div class="rows">
                            <div class="col-sm-12 form-group">
                                <!-- <label for="captcha">Solve the captcha</label><br> -->
                                <img src="captcha.png" alt="CAPTCHA" class="captchaimage">
								<input required class="form-control" type="text" id="captcha" name="captcha_challenge" pattern="[A-Z]{6}">
							</div>
                        </div>
                        <div class="s">
                            <div class="col-sm-12 form-group">
                                <button type="submit" class="btn btn-lg btn-block postbtn" name="saveTEXT">Post </button>
                            </div>
                        </div>
                    </form>

					<form style="display: none;" role="form" action="index.php" method="post" id="reused_form"  enctype="multipart/form-data">
						<?php include('errors.php');
						?>
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
						<input type="hidden" name="token" value="<?php echo $_SESSION['token'] ?? '' ?>">
						<div id="fileupload" class="rows">
                            <div class="col-sm-12 form-group">
                            	<input name="file" type="file" id="file" class="feedback-input">
							</div>
                        </div>
						<div class="rows">
                            <div class="col-sm-12 form-group">
                                <!-- <label for="captcha"></label><br> -->
                                <img src="captcha.png" alt="CAPTCHA" class="captchaimage">
								<input required class="form-control" type="text" id="captcha" name="captcha_challenge" pattern="[A-Z]{6}">
							</div>
                        </div>
                        <div class="rows">
                            <div class="col-sm-12 form-group">
                                <button type="submit" class="btn btn-lg btn-block postbtn" name="savePDF">Post </button>
                            </div>
                        </div>
                    </form>
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
									$len=strlen($onefeed['feedback']);
									if ($len>100) {
										echo ('<p>'.substr($onefeed['feedback'],0,100).'...'.'<a href="readmore.php?id='.$onefeed['id']."&token=".$_SESSION['adminfeedtoken'].'" id="readmore" class="ban" name="readmore">Read More</a></p>');
									}
									else{
										echo ('<p>'.$onefeed['feedback'].'</p>');
									}
									
								}
							 ?>
				</td>
			<td>
				<a href="review.php?id=<?php echo $onefeed['id'].'&top='.$pdfortext.'&token='.$_SESSION['token']?>">Edit</a>
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
	unset($_SESSION['username']);
	$sessionuser="";
	header("location: login.php");

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