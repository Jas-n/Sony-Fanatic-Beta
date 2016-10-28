<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<?php $app->get_css();
		$app->get_head_js();
		$app->get_icons();?>
		<title><?=$app->page_title()?></title>
    </head>
    <body id="<?=$page->slug?>">
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
							<a class="nav-link" href="/login" id="login_link">Login</a>
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