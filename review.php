<?php	include('edit.php');?>
<html>
    <head>
        <link rel="stylesheet" type="text/css" href="/styles/userHome.css">
        <link rel="stylesheet" type="text/css" href="/styles/form.css">
        <link rel="stylesheet" type="text/css" href="/styles/table.css">
        <link rel="stylesheet" href="/styles/bootstrap.css" >
        <script src="/scripts/main.js"></script>
    </head>
    <body>

        <!-- <header  class="nav-page-header">
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
        </header> -->


        <section class="page-content">
            <div id="form" class="container">
                <div class="rows " style="margin-top: 50px">
                    <div  class="col-md-6 col-md-offset-3 form-container">
                        <h2>Edit Feedback</h2> 
                            <p> Please Edit your feedback below: </p>
        <?php
            if ('text'==$link2) {
                echo '<form role="form" action="" method="post" id="used_form"  >';
                include('errors.php');
                echo $updatedTitle;
                echo'<div class="rows">
                        <div class="col-sm-12 form-group">
                            <label for="title">'; echo $updatedTitle;echo'</label>
                            <input required class="form-control" type="text" name="title" id="title" placeholder="Your Title" value='; echo $updatedTitle;''; echo'>
                        </div>
                    </div>
                    <div id="comments"  class="rows">
                        <div class="col-sm-12 form-group">
                            <label for="comments"> Comments</label>
                            <textarea style="resize: none;" required class="form-control" type="textarea" name="comments" id="comments" placeholder="Your Comments" maxlength="6000" rows="7">';echo $updatedFeed;'';echo'</textarea>
                        </div>
                    </div>
                    <div class="rows">
                        <div class="col-sm-12 form-group">
                            <input disabled type="checkbox" name="nolargefile" id="nolargefile" class="largefile" value='; echo $updatedFile;''; echo'>
                            <label for="nolargefile"> I want to upload a pdf file</label>
                        </div>
                    </div>
                    <div class="s">
                        <div class="col-sm-12 form-group">
                            <button type="submit" class="btn btn-lg btn-block postbtn" name="editFEED">Post </button>
                        </div>
                    </div>
                    <div class="rows">
                        <div class="col-sm-12 form-group">
                            <button type="submit" class="btn btn-lg btn-block postbtn" name="cancel">Cancel </button>
                        </div>
                    </div>
                </form>';
            }
            elseif('pdf'==$link2){
                echo '<form role="form" action="" method="post" id="reused_form"  enctype="multipart/form-data">';
                include('errors.php');
                echo'<div class="rows">
                        <div class="col-sm-12 form-group">
                            <label for="title"> Title</label>
                            <input required class="form-control" type="text" name="title" id="title" placeholder="Your title" value='; echo $updatedTitle;''; echo'>
                        </div>
                    </div>
                    <div class="rows">
                        <div class="col-sm-12 form-group">
                            <input disabled checked onclick="uploadtext()" type="checkbox" name="nolargefile" id="nolargefile" class="largefile" >
                            <label for="nolargefile"> I want to upload a pdf file</label>
                        </div>
                    </div>
                    <div id="fileupload" class="rows">
                        <div class="col-sm-12 form-group">
                            <input name="file" type="file" id="file" class="feedback-input" value='; echo $updatedFile;''; echo'>
                        </div>
                    </div>
                    <div class="rows">
                        <div class="col-sm-12 form-group">
                            <button type="submit" class="btn btn-lg btn-block postbtn" name="editFILE">Post </button>
                        </div>
                    </div>
                    <div class="rows">
                        <div class="col-sm-12 form-group">
                            <button type="submit" class="btn btn-lg btn-block postbtn" name="cancel">Cancel </button>
                        </div>
                    </div>
                </form>';
            }
        

        ?>
                        <div id="success_message" style="width:100%; height:100%; display:none; "> <h3>Posted your feedback successfully!</h3> </div>
                        <div id="error_message" style="width:100%; height:100%; display:none; "> <h3>Error</h3> Sorry there was an error sending your form. </div>
                    </div>
                </div>
            </div>
        </section>
    </body>
</html>