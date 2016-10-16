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
    	<?php /*if($page->has_feature_permission('search')){ ?>
			<div class="full_page_search_container" id="full-page-search">
				<div class="row">
					<div class="col-xl-10 offset-xl-1">
						<div class="full_page_search_close" id="full-search-close-trigger">&times;</div>
						<h2 class="h1" id="search-results-title"><span id="search-results-status">Search</span> <small class="text-muted" id="search-results-text"></small></h2>	
						<input type="search" id="full-page-search-term" class="form-control full_page_search_page_bar" placeholder="Start Searching&hellip;">
						<div class="alert alert-warning hidden" id="search-results-warning">No Results Found</div>
						<div id="searching" class="loading_bar hidden"><div></div></div>
						<ul class="nav nav-tabs" id="full-search-results-nav" role="tablist"></ul>
						<div class="tab-content" id="full-results"></div>
					</div>
				</div>
			</div>
		<?php }*/ ?>
   		<nav>
   			<div class="nav_head">
				<a href="./">Sony Fanatic</a>
			</div>
			<ul>
				<li class="has_children">
					<a><span class="fa fa-fw fa-television"></span> Products</a>
					<ul>
						<li><a href="products"><span class="fa fa-fw fa-list"></span> Products</a></li>
						<li><a href="add"><span class="fa fa-fw fa-plus"></span> Add</a></li>
						<li class="has_children">
							<a><span class="fa fa-fw fa-th"></span> Sorting</a>
							<ul>
								<li><a href="brands"><span class="fa fa-fw fa-list"></span> Brands</a></li>
								<li><a href="brands"><span class="fa fa-fw fa-th"></span> Categories</a></li>
								<li><a href="features"><span class="fa fa-fw fa-th"></span> Features</a></li>
								<li><a href="tags"><span class="fa fa-fw fa-th"></span> Tags</a></li>
							</ul>
						</li>
					</ul>
				</li>
				<li class="has_children">
					<a><span class="fa fa-fw fa-users"></span> Users</a>
					<ul>
						<li><a href="users"><span class="fa fa-fw fa-list"></span> View</a></li>
					</ul>
				</li>
				<li class="has_children">
					<a><span class="fa fa-fw fa-wrench"></span> Management</a>
					<ul>
						<li><a href="logs"><span class="fa fa-fw fa-list"></span> Logs</a></li>
						<li><a href="statistics"><span class="fa fa-fw fa-bar-chart"></span> Statistics</a></li>
					</ul>
				</li>
				<li class="has_children">
					<a><span class="fa fa-fw fa-cogs"></span> Administration</a>
					<ul>
						<li><a href="settings"><span class="fa fa-fw fa-cogs"></span> Settings</a></li>
					</ul>
				</li>
				<li class="has_children">
					<a><span class="fa fa-fw fa-user"></span> <?=$user->first_name?></a>
					<ul>
						<li><a href="profile"><span class="fa fa-fw fa-user"></span> Profile</a></li>
						<li><a href="../logout"><span class="fa fa-fw fa-sign-out"></span> Logout</a></li>
					</ul>
				</li>
			</ul>
		</nav>
		<div id="body">
			<div class="container-fluid">
				<div class="row">
					<div class="col-xl-10 offset-xl-1">