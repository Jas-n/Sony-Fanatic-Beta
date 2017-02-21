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
    <body id="<?=str_replace('/','_',$page->slug)?>">
    	<nav>
    		<ul>
				<li><a href="../"><i class="fa fa-home"></i>Home</a></li>
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
				<li><a href="profile"><i class="fa fa-user"></i>Profile</a></li>
				<li><a href="../logout"><i class="fa fa-sign-out"></i>Logout</a></li>
				<li><a href="test"><i class="fa fa-exclamation-triangle"></i> Testing</a></li>
			</ul>
		</nav>
		<header>
			<h1><?=$h1.($small?' <small class="text-muted">'.$small.'</small>':'')?></h1>
			<?php if($buttons){ ?>
				<div class="actions">
					<?php foreach($buttons as $button){ ?>
						<a class="fa fa-<?=$button['icon']?>" data-placement="left" data-toggle="tooltip" href="<?=$button['link']?>"<?=$button['target']?' target="_'.$button['target'].'"':''?> title="<?=$button['title']?>"></a>
					<?php } ?>
				</div>
			<?php } ?>
			<ul class="breadcrumb">
				<li class="breadcrumb-item"><a href="../">Home</a></li>
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
		</header>
		<main class="container-fluid">