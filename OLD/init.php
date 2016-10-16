<?php session_start();
function is_logged_in(){
	return $_SESSION['user_id'];
}
$definitions=array(
	'root'		=>__DIR__.'/',
	'site_name'	=>'Sony Fanatic'
);
foreach($definitions as $key=>$definition){
	define(strtoupper($key),$definition);
}