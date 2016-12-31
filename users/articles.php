<?php $app_require[]='php.articles';
require('../init.php');
require('header.php');
$article_list=$articles->get_articles(-1);?>
<h1>Articles</h1>
<ol class="breadcrumb">
	<li class="breadcrumb-item"><a href="../">Home</a></li>
	<li class="breadcrumb-item"><a href="./">Dashboard</a></li>
	<li class="breadcrumb-item active">Articles</li>
</ol>
<table class="table table-hover table-sm table-striped">
	<thead>
		<tr>
			<th>Title</th>
			<th>Products</th>
			<th>Category</th>
			<th>Status</th>
			<th>Published</th>
			<th>Actions</th>
		</tr>
	</thead>
	<tbody>
		<?php if($article_list['count']){
			foreach($article_list['data'] as $article){?>
				<tr>
					<td><?=$article['title']?></td>
					<td><?=$article['products']?></td>
					<td><?=$article['category']?></td>
					<td><?=$article['status']?></td>
					<td><?=sql_datetime($article['published'])?></td>
					<td><a class="btn btn-sm btn-primary" href="article/<?=$article['id']?>">View</a></td>
				</tr>
			<?php }
		}else{?>
			<tr class="danger"><td colspan="6">No news articles found</td></tr>
		<?php }?>
	</tbody>
</table>
<?php pagination($article_list['count']);
require('footer.php');