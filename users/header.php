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
				<a href="../">Sony Fanatic</a>
			</div>
			<ul>
				<li><a href="./"><i class="fa fa-fw fa-dashboard"></i> Dashboard</a></li>
				<li class="has_children">
					<a><i class="fa fa-fw fa-television"></i> Products</a>
					<ul>
						<li><a href="products"><i class="fa fa-fw fa-list"></i> Products</a></li>
						<li><a href="add_product"><i class="fa fa-fw fa-plus"></i> Add</a></li>
						<li class="has_children">
							<a><i class="fa fa-fw fa-th"></i> Sorting</a>
							<ul>
								<li><a href="brands"><i class="fa fa-fw fa-list"></i> Brands</a></li>
								<li><a href="brands"><i class="fa fa-fw fa-th"></i> Categories</a></li>
								<li><a href="features"><i class="fa fa-fw fa-th"></i> Features</a></li>
								<li><a href="tags"><i class="fa fa-fw fa-th"></i> Tags</a></li>
							</ul>
						</li>
					</ul>
				</li>
				<li class="has_children">
					<a><i class="fa fa-fw fa-newspaper-o"></i> News</a>
					<ul>
						<li><a href="news"><i class="fa fa-fw fa-newspaper-o"></i> View</a></li>
						<li><a href="add_news"><i class="fa fa-fw fa-pencil"></i> Add</a></li>
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