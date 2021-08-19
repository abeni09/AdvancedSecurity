<?php
session_start();

// initializing variables
$username = "";
$email    = "";
$errors = array(); 
$code=md5(uniqid(true));

// connect to the database
$db = mysqli_connect('localhost', 'root', '', 'helpmycitydb');

?>