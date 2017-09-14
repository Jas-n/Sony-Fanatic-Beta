<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<?php $app->get_css();
		$app->get_head_js();
		$app->get_icons();?>
		<title><?=$app->page_title()?></title>
		<base href="<?=SERVER_NAME?>users/">
    </head>
    <body id="<?=str_replace('/','_',$page->slug)?>">
		<div class="menu-wrap">
			<nav>
				<ul>
					<li><a href="../"><i class="<?=$fa->icon('home')?>"></i>Home</a></li>
					<li><a href="./"><i class="fa fa-dashboard"></i>Dashboard</a></li>
					<li class="has_children">
						<a><i class="fa fa-television"></i> Products</a>
						<ul>
							<li><a href="products">Products</a></li>
							<li><a href="add_product">Add</a></li>
							<li><a href="brands">Brands</a></li>
							<li><a href="categories">Categories</a></li>
							<li><a href="feature_categories">Features</a></li>
							<li><a href="tags">Tags</a></li>
						</ul>
					</li>
					<li class="has_children">
						<a><i class="fa fa-newspaper-o"></i> Articles</a>
						<ul>
							<li><a href="articles">View</a></li>
							<li><a href="add_article">Add</a></li>
						</ul>
					</li>
					<li class="has_children">
						<a><span class="fa fa-users"></span> Users</a>
						<ul>
							<li><a href="users">View</a></li>
						</ul>
					</li>
					<li class="has_children">
						<a><i class="fa fa-wrench"></i> Management</a>
						<ul>
							<li><a href="logs">Logs</a></li>
							<li><a href="statistics">Statistics</a></li>
						</ul>
					</li>
					<li class="has_children">
						<a><i class="fa fa-cogs"></i> Admin</a>
						<ul>
							<li><a href="settings">Settings</a></li>
						</ul>
					</li>
					<li><a href="test"><i class="fa fa-exclamation-triangle"></i> Testing</a></li>
				</ul>
			</nav>
		</div>
		<header>
			<div class="header-right">
				<ul class="user_interactions">
					<li class="global_search">
						<a class="fa fa-search" data-placement="bottom" data-toggle="tooltip" title="Search"></a>
						<div class="global_search_box">
							<input class="form-control" placeholder="Search&hellip;" type="search">
						</div>
					</li>
					<?php if($html_help){ ?>
						<li><a class="help_draw_trigger fa fa-life-ring" data-placement="bottom" data-toggle="tooltip" title="Help"></a></li>
					<?php } ?>
					<li class="has_children profile-container">
						<a data-placement="bottom" data-toggle="tooltip" class="profile-picture-container" title="<?=$user->first_name?>"><img height="50" src="<?=$user->get_avatar(NULL,50)?>"></a>
						<ul>
							<li><a href="profile">Profile</a></li>
							<li><a href="../logout">Logout</a></li>
						</ul>
					</li>
				</ul>
				<?php if($buttons){ ?>
					<ul class="actions">
						<?php foreach($buttons as $button){ ?>
							<li><a class="fa fa-<?=$button['icon'].($button['class']?' '.$button['class']:'')?>" data-placement="bottom" data-toggle="tooltip" href="<?=$button['link']?>"<?=$button['target']?' target="_'.$button['target'].'"':''?> title="<?=$button['title']?>"></a></li>
						<?php } ?>
					</ul>
				<?php } ?>
			</div>
			<div class="header-left">
				<h1><?=$h1.($small?' <small class="text-muted">'.$small.'</small>':'')?></h1>
				<ul class="breadcrumb">
					<li class="breadcrumb-item<?=$page->slug=='users/index'?' active':''?>"><a href="./">Dashboard</a></li>
					<?php if($breadcrumb){
						end($breadcrumb);
						$last=key($breadcrumb);
						foreach($breadcrumb as $link=>$title){?>
							<li class="breadcrumb-item<?=$link==last?' active':''?>">
								<?php if(!is_numeric($link)){ ?>
									<a href="<?=$link?>">
								<?php }
								echo $title;
								if(!is_numeric($link)){ ?>
									</a>
								<?php }?>
							</li>
						<?php }
					} ?>
				</ul>
			</div>
		</header>
		<div class="header-spacer"></div>
		<main>