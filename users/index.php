<?php require('../init.php');
$html_help='<p>The Dashboard is the central hub for Glowt.</p>
<p>At the top you will see your notifications. To close notifications, click dismiss to the right of the notification. (please note: only applicable to those that are dismissable)</p>';
require('header.php');?>
<h1>Dashboard</h1>
<ol class="breadcrumb">
	<li class="breadcrumb-item"><a href="../">Home</a></li>
	<li class="breadcrumb-item active">Dashboard</li>
</ol>
<h2>To Do</h2>
<ol>
	<li>Finish menu tree</li>
	<li>Replace font awesome icons with icons from Metro Studio</li>
	<li>Tidy up un-needed files</li>
</ol>
<h2>Overview</h2>
<ol>
	<li>Product Database</li>
	<li>Cross-product comparisons</li>
	<li>My Catalogue
		<ol>
			<li>Products owned with reason
				<ol>
					<li>Now</li>
					<li>Past</li>
				</ol>
			</li>
			<li>Products wanted with reason</li>
		</ol>
	</li>
	<li>Product Reviews
		<ol>
			<li>SF</li>
			<li>User-submitted</li>
		</ol>
	</li>
	<li>Report product inaccuracies</li>
	<li>Per-product forum</li>
	<li>product gallery</li>
	<li>product purchase links</li>
	<li>Per-product ads if available, otherwise:
		<ol>
			<li>Category</li>
			<li>Brand</li>
			<li>Other</li>
		</ol>
	</li>
</ol>
<?php require('footer.php');