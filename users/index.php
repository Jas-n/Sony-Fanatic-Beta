<?php require('../init.php');
$html_help='<p>The Dashboard is the central hub for Glowt.</p>
<p>At the top you will see your notifications. To close notifications, click dismiss to the right of the notification. (please note: only applicable to those that are dismissable)</p>';
require('header.php');?>
<h1>Dashboard</h1>
<ol class="breadcrumb">
	<li class="breadcrumb-item"><a href="../">Home</a></li>
	<li class="breadcrumb-item active">Dashboard</li>
</ol>
<ol>
	<li>Finish menu tree</li>
	<li>Replace font awesome icons with icons from Metro Studio</li>
	<li>Tidy up un-needed files</li>
	<li>Hide siblings in menu (E.g. the plus under <strong>Products > Sorting</strong>)</li>
</ol>
<?php require('footer.php');