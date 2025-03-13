<?php
	session_start();
	if(isset($_SESSION["email_login"])){
		unset($_SESSION["email_login"]);
		unset($_SESSION['user_name']);
	}
	if(isset($_SESSION['user_name'])){
		unset($_SESSION['user_name']);
	}
	header("Location: index.php");
?>