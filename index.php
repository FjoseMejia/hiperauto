<?php
session_start();

if(!isset($_SESSION['state'])){
	header("location: app/view/login.php");
}else{
	header("location: app/view/dashboard.php");
}