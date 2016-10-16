<?php session_start();
if(!$_SESSION['user_id']){
	header('Location: ../logout');
	exit;
}
include('../init.php');