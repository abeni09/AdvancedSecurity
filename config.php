<?php
session_start();


// initializing variables
$username = "";
$email    = "";
$errors = array(); 
$code=md5(uniqid(true));

// connect to the database
$db = mysqli_connect('localhost', 'root', '', 'helpmycitydb');
$readDB=mysqli_connect('localhost','reader','Joq0sPVxv5vpPUSV','helpmycitydb');
$writeDB=mysqli_connect('localhost','writer','W2Z6Fv4dOTDGTTQj','helpmycitydb');
$deleteDB=mysqli_connect('localhost','deleter','9ePJhCa9o9QMBo09','helpmycitydb');
$updateDB=mysqli_connect('localhost','updater','Vs0RX9UJB9Itqvcb','helpmycitydb');

?>