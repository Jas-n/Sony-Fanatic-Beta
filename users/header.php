<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
		<?php $app->get_css();
		$app->get_head_js();
		$app->get_icons();?>
		<title><?=$app->page_title()?></title>
		<base href="<?=SERVER_NAME?>users/">
    </head>
    <body id="<?=strtolower(str_replace(' ','-','users-'.$page->slug))?>">
   		<nav>
   			<div class="nav_head">
				<a href="../"><?=SITE_NAME?></a>
			</div>
			<ul>
				<li><a href="./"><i class="fa fa-fw fa-dashboard"></i> Dashboard</a></li>
				<li class="has_children">
					<a><i class="fa fa-fw fa-television"></i> Products</a>
					<ul>
						<li><a href="products"><i class="fa fa-fw fa-television"></i> Products</a></li>
						<li><a href="add_product"><i class="fa fa-fw fa-plus"></i> Add</a></li>
						<li><a href="brands"><i class="fa fa-fw fa-list"></i> Brands</a></li>
						<li><a href="categories"><i class="fa fa-fw fa-th"></i> Categories</a></li>
						<li><a href="feature_categories"><i class="fa fa-fw fa-list-ol"></i> Features</a></li>
						<li><a href="tags"><i class="fa fa-fw fa-tags"></i> Tags</a></li>
					</ul>
				</li>
				<li class="has_children">
					<a><i class="fa fa-fw fa-newspaper-o"></i> Articles</a>
					<ul>
						<li><a href="articles"><i class="fa fa-fw fa-newspaper-o"></i> View</a></li>
						<li><a href="add_article"><i class="fa fa-fw fa-pencil"></i> Add</a></li>
					</ul>
				</li>
				<li class="has_children">
					<a><span class="fa fa-fw fa-users"></span> Users</a>
					<ul>
						<li><a href="users"><i class="fa fa-fw fa-list"></i> View</a></li>
					</ul>
				</li>
				<li class="has_children">
					<a><i class="fa fa-fw fa-wrench"></i> Management</a>
					<ul>
						<li><a href="logs"><i class="fa fa-fw fa-list"></i> Logs</a></li>
						<li><a href="statistics"><i class="fa fa-fw fa-bar-chart"></i> Statistics</a></li>
					</ul>
				</li>
				<li class="has_children">
					<a><i class="fa fa-fw fa-cogs"></i> Administration</a>
					<ul>
						<li><a href="settings"><i class="fa fa-fw fa-cogs"></i> Settings</a></li>
					</ul>
				</li>
				<li class="has_children">
					<a><i class="fa fa-fw fa-user"></i> <?=$user->first_name?></a>
					<ul>
						<li><a href="profile"><i class="fa fa-fw fa-user"></i> Profile</a></li>
						<li><a href="../logout"><i class="fa fa-fw fa-sign-out"></i> Logout</a></li>
					</ul>
				</li>
			</ul>
		</nav>
		<div id="body">
			<div class="container-fluid">
				<div class="row">
					<div class="col-xl-10 offset-xl-1">