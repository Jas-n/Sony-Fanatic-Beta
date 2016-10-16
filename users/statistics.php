<?php $app_require=array(
	'php.users',
	'php.statistics'
);
$html_help='<p>Statistics shows various bits of information from around Glowt.</p>';
require('../init.php');
include('header.php');?>
<h1>Statistics</h1>
<ol class="breadcrumb">
	<li class="breadcrumb-item"><a href="../">Home</a></li>
	<li class="breadcrumb-item"><a href="./">Dashboard</a></li>
	<li class="breadcrumb-item">Management</li>
	<li class="breadcrumb-item active">Statistics</li>
</ol>
<div class="row">
	<?=$statistics->get_totals()?>
</div>
<div class="row">
	<?=$statistics->get_orphans().
	$statistics->get_users()?>
</div>
<?php include('footer.php');