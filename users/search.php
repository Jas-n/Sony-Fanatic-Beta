<?php if(!$_GET['term']){
	header('Location: /');
	exit;
}
require('../init.php');
$html_help='<p>This page shows a list of results based upon your search query. The results are split by Users, Settings, Employees, and jobs. These are easily switched by selecting the tabs along the top.</p>
<p>To the right of each result you have the option to view or email (if the search is an employee)</p>';
$app->add_to_head('<script>var term="'.$_GET['term'].'";</script>');
require('header.php');?>
<div class="page-header">
	<h1 id="title">Searching <small class="text-muted"><span id="location"></span> for "<?=$_GET['term']?>"</small></h1>
	<ol class="breadcrumb">
		<li class="breadcrumb-item"><a href="./">Dashboard</a></li>
		<li class="breadcrumb-item active">Search</li>
	</ol>
</div>
<div id="searching"><div></div></div>
<ul class="nav nav-tabs" id="results_nav" role="tablist"></ul>
<div class="tab-content" id="results"></div>
<?php require('footer.php');