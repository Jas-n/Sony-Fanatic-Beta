<?php $app_require=array(
	'js.tooltip',
	'php.articles',
	'php.product'
);
include('init.php');
if(!$_GET['products']){
	header('Location: /');
	exit;
}
$prods=explode('/',$_GET['products']);
foreach($prods as $i=>$product){
	$temp=new product($product);
	if($temp->id){
		$product_list[]=$temp;
		$names[]=$temp->name;
		if($temp->features){
			foreach($temp->features as $feature){
				$feature_categories[$feature['category']][$feature['feature']][$i][]=$feature['value'];
				ksort($feature_categories);
				ksort($feature_categories[$feature['category']]);
			}
		}
	}
	unset($temp);
}
if(!$product_list){
	header('Location: /');
	exit;
}
$app->page_title=implode(' vs ',$names);
include('header.php');?>
<h1><?=implode(' <small class="text-muted">vs</small> ',$names)?></h1>
<div class="vs_banner row">
	<?php foreach($product_list as $i=>$product){
		if($product->images){ ?>
			<div class="product col-xs" style="background-image:url(<?=$product->images['full'][0]?>)">
				<a href="/p/<?=$product->id?>-<?=$product->slug?>"><span><?=$product->name?></span></a>
			</div>
		<?php }
	} ?>
</div>
<h2>Freatures</h2>
<table class="table table-border table-hover">
	<tbody>
		<tr>
			<th colspan="2"></th>
			<?php foreach($product_list as $i=>$product){ ?>
				<th><?=$product->name?></th>
			<?php } ?>
		</tr>
		<?php foreach($feature_categories as $feature_category=>$features){?>
			<tr>
				<th rowspan="<?=sizeof($features)?>"><?=$feature_category?></th>
				<?php $first_feature=key($features);
				foreach($features as $feature=>$values){
					if($first_feature!=$feature){?>
						<tr>
					<?php } ?>
					<td><?=$feature?></td>
					<?php foreach($product_list as $i=>$product){ ?>
						<td>
							<?php if($values[$i]){?>
								<?=implode('<br>',$values[$i])?>
							<?php } ?>
						</td>
					<?php }
					if($first_feature!=$feature){?>
						</tr>
					<?php }
				}?>
			</tr>
		<?php } ?>
	</tbody>
</table>
<?php include('footer.php');