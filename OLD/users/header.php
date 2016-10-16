<?php if($_POST['login']){
	if($_POST['email']=='jas-n@outlook.com' && $_POST['password']=='4ThePlayers'){
		$_SESSION['user_id']=1;
		header('Location: /users');
		exit;
	}
} ?>
<!doctype html>
<html lang="en">
	<head>
		<link href="//fonts.googleapis.com/css?family=Noto+Sans:400,400i,700,700i" rel="stylesheet">
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    	<meta http-equiv="x-ua-compatible" content="ie=edge">
    	<base href="http://beta.sonyfanatic.com/users/">
		<link href="../css/bootstrap-reboot.css" rel="stylesheet">
		<link href="../css/bootstrap-flex.css" rel="stylesheet">
		<link href="../css/bootstrap-grid.css" rel="stylesheet">
		<link href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.6.3/css/font-awesome.min.css" rel="stylesheet">
		<link href="../css/core.css" rel="stylesheet">
		<link href="../css/users.css" rel="stylesheet">
		<?php if(is_file(ROOT.'css/'.basename($_SERVER['PHP_SELF'],'.php').'.css')){ ?>
			<link href="../css/<?=basename($_SERVER['PHP_SELF'],'.php')?>.css" rel="stylesheet">
		<?php } ?>
		<title><?=SITE_NAME?></title>
	</head>
	<body id="<?=basename($_SERVER['PHP_SELF'],'.php')?>">
		<nav class="navbar navbar-fixed-top navbar-dark bg-primary">
			<button class="navbar-toggler hidden-sm-up" type="button" data-toggle="collapse" data-target="#exCollapsingNavbar2" aria-controls="exCollapsingNavbar2" aria-expanded="false" aria-label="Toggle navigation">
 				&#9776;
  			</button>
  			<div class="collapse navbar-toggleable-xs" id="exCollapsingNavbar2">
				<ul class="nav navbar-nav">
					<li class="nav-item"><a class="nav-link" href="/">Dashboard</a></li>
					<li class="nav-item"><a class="nav-link" href="/">Products</a></li>
				</ul>
			</div>
		</nav>
		<main>
			<div class="container">