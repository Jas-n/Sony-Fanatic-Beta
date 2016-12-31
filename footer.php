		</main>
		<footer class="container-fluid">
			<div class="row">
				<div class="col-xs-6">
					<p>&copy; <?=SITE_NAME?> 2010 - <?=date('Y')?></p>
				</div>
				<div class="col-xs-6">
					<?php $folding=json_decode(file_get_contents(ROOT.'folding.json'),1);?>
					<h5>Doing Our Bit</h5>
					<p><strong>Team</strong> <a href="http://folding.stanford.edu/stats/team/<?=$folding['team']?>" target="_blank"><?=$folding['name']?></a></p>
					<p><strong>Credit</strong> <?=number_format($folding['credit'])?></p>
					<p>Find out more about <a href="https://foldingathome.stanford.edu/" target="_blank">Folding@home</a></p>
				</div>
			</div>
		</footer>
		<?php $app->get_foot_js();?>
	</body>
</html>