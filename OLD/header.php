<?php if($_POST['login']){
	if($_POST['email']=='jas-n@outlook.com' && $_POST['password']=='4ThePlayers'){
		$_SESSION['user_id']=1;
	}
} ?>
<!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    	<meta http-equiv="x-ua-compatible" content="ie=edge">
    	<base href="http://beta.sonyfanatic.com/">
		<link href="css/bootstrap-reboot.css" rel="stylesheet">
		<link href="css/bootstrap-flex.css" rel="stylesheet">
		<link href="css/bootstrap-grid.css" rel="stylesheet">
		<link href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.6.3/css/font-awesome.min.css" rel="stylesheet">
		<link href="/css/core.css" rel="stylesheet">
		<?php if(is_file(ROOT.'css/'.basename($_SERVER['PHP_SELF'],'.php').'.css')){ ?>
			<link href="/css/<?=basename($_SERVER['PHP_SELF'],'.php')?>.css" rel="stylesheet">
		<?php } ?>
		<title><?=SITE_NAME?></title>
	</head>
	<body id="<?=basename($_SERVER['PHP_SELF'],'.php')?>">
		<nav class="navbar navbar-fixed-top navbar-dark bg-primary">
			<button class="navbar-toggler hidden-sm-up" type="button" data-toggle="collapse" data-target="#exCollapsingNavbar2" aria-controls="exCollapsingNavbar2" aria-expanded="false" aria-label="Toggle navigation">
 				&#9776;
  			</button>
  			<div class="collapse navbar-toggleable-xs" id="exCollapsingNavbar2">
				<a class="navbar-brand" href="/"><?=SITE_NAME?></a>
				<ul class="nav navbar-nav">
					<li class="nav-item">
						<a class="nav-link" href="/">Home <span class="sr-only">(current)</span></a>
					</li>
					<?php if(!is_logged_in()){ ?>
						<li class="nav-item pull-xs-right">
							<a class="nav-link" href="#login" id="login_link">Login</a>
							<form class="hidden" id="login_drop" method="post">
								<div class="form-group">
									<input type="email" class="form-control" id="email" name="email" placeholder="Email">
								</div>
								<div class="form-group">
									<input type="password" class="form-control" id="password" name="password" placeholder="Password">
								</div>
								<p class="text-xs-center"><input class="btn btn-sm btn-success" name="login" type="submit" value="Login"></p>
							</form>
						</li>
					<?php }else{ ?>
						<li class="nav-item pull-xs-right">
							<a class="nav-link" href="/users">Account</a>
						</li>
					<?php } ?>
				</ul>
			</div>
		</nav>
		<main>