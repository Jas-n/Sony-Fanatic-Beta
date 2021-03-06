<?php require_once('init.php');
$status=$_SERVER['REDIRECT_STATUS'];
$codes=array(
	403	=>array('403 Forbidden','The server has refused to fulfill your request.'),
	404	=>array('404 Not Found','The document/file requested was not found on this server.'),
	405	=>array('405 Method Not Allowed','The method specified in the Request-Line is not allowed for the specified resource.'),
	408	=>array('408 Request Timeout','Your browser failed to send a request in the time allowed by the server.'),
	500	=>array('500 Internal Server Error','The request was unsuccessful due to an unexpected condition encountered by the server.'),
	502	=>array('502 Bad Gateway','The server received an invalid response from the upstream server while trying to fulfill the request.'),
	504	=>array('504 Gateway Timeout','The upstream server failed to send a request in the time allowed by the server.'),
);
$title=$codes[$status][0];
$message=$codes[$status][1];
if($_GET['slug']){
	$title=$codes[404][0];
	$message=$codes[404][1];
}elseif($title==false || strlen($status)!=3){
	$message = 'Please supply a valid status code.';
}
require('header.php'); ?>
<div class="row last">
	<div class="page-header title-bar">
		<h1><?=$title?></h1>
	</div>
	<div class="clearfix"></div>
	<p class='h4 m-y-2'>
		<?=$message?>
	</p>
</div>
<?php require('footer.php');