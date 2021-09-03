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
	 <link rel="stylesheet" type="text/css" href="/styles/userHome.css">
	<link rel="stylesheet" type="text/css" href="/styles/table.css">
	<link rel="stylesheet" href="/styles/bootstrap.css">
	<script src="scripts/main.js"></script>
	<script>
		function displayUsers() { 
			document.getElementById("listallusers").style.display="block"; 
			document.getElementById("listallfeedbacks").style.display="none";
			document.getElementById("listfeedbacks").classList.remove("active");
			document.getElementById("listusers").classList.add("active");
		}
		function displayFeeds() { 
			document.getElementById("listallusers").style.display="none"; 
			document.getElementById("listallfeedbacks").style.display="block"; 
			document.getElementById("listusers").classList.remove("active");
			document.getElementById("listfeedbacks").classList.add("active");
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
                    <a onclick="displayUsers()" class="active" id="listusers">
                        <span class="icon"><i class="fas fa-home"></i></span>
                        <span class="item">List Users</span>
                    </a>
                </li>
                <li>
                    <a onclick="displayFeeds()" id="listfeedbacks">
                        <span class="icon"><i class="fas fa-desktop"></i></span>
                        <span class="item">List Feedbacks</span>
                    </a>
				</li>
                <li>
                    <a href="admin.php?logout='1'" style="color: red;">
                        <span class="icon"><i class="fas fa-cog"></i></span>
                        <span class="item">Logout</span>
                    </a>
                </li>
            </ul>
    
        </div>

    </div>
</header>
<section class="page-content">
	<section id="listallusers">
	<table id="customers">
		<tr>
			<th>Title</th>
			<th>Feedback</th>
		</tr>


		<?php
		$users = "SELECT * FROM users";
		$userresults = mysqli_query($readDB, $users);
		$allusers=array();
		while($oneuser = mysqli_fetch_assoc($userresults)){
			$allusers[]=$oneuser;
		}
		foreach($allusers as $oneuser){
			?>
			

			<tr>
							<td data-title="Full Name">
								<?php echo $oneuser['username'] ?>
							</td>
							<td data-title="Job Title">
								<?php echo $oneuser['email'] ?>
							</td>
							<?php
							if ($oneuser['banstatus']=='yes') {?>
								<td data-title="">
									<a href="unban.php?name=<?php echo $oneuser['username'];?>" id="ban" class="ban">Unban</a>
								</td>
								<?php
							}
							elseif($oneuser['banstatus']=='no'){
								if (!empty($oneuser['sessionID'])) {?>
									
									<td data-title="">
										<a href="ban.php?name=<?php echo $oneuser['username'];?>" id="ban" class="ban">Ban(Active)</a>
									</td>
								<?php
								}
								elseif(empty($oneuser['sessionID'])){?>
									
									<td data-title="">
										<a href="ban.php?name=<?php echo $oneuser['username'];?>" id="ban" class="ban">Ban</a>
									</td>
								<?php
								}
							}
							?>
						</tr>
		<?php
		}
		?>
	</table>
	</section>
	<section id="listallfeedbacks" style="display: none;" >
					<table id="customers">

						<tr>
							<th >
								Title
							</th>
							<th>
								Feedback
							</th>
							<th>
								Feedback From
							</th>
						</tr>


		<?php
		$feeds = "SELECT * FROM feedbacks";
		$feedresults = mysqli_query($readDB, $feeds);
		$allfeeds=array();
		while($onefeed = mysqli_fetch_assoc($feedresults)){
			$allfeeds[]=$onefeed;
		}
		foreach($allfeeds as $onefeed){
			?>
			

			<tr>
							<td data-title="Full Name">
								<?php echo $onefeed['title'] ?>
							</td>
							<td data-title="Job Title">
								<?php
								if(empty($onefeed['feedback'])){
									$file=$onefeed['pdffile']
									?>
									<a href='/uploads/<?php echo $onefeed['pdffile']?>' download><?php echo $onefeed['pdffile'] ;?></a> 
								<?php
								}else{
									// $pdfortext='text';
									$len=strlen($onefeed['feedback']);
									if ($len>100) {
										echo ('<p>'.substr($onefeed['feedback'],0,100).'...'.'<a href="readmore.php?id='.$onefeed['id'].'" id="readmore" class="ban" name="readmore">Read More</a></p>');
									}
									else{
										echo ('<p>'.$onefeed['feedback'].'</p>');
									}
									}
							// endif;
							 ?>
							</td>
							<td data-title="Job Title">
								<?php echo $onefeed['feedbackfrom'] ?>
							</td>
						</tr>
		<?php
		}
		?>
					</table>
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