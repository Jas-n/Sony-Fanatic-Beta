<?php $permissions='*';
include('init.php');
include('header.php');?>
<h1>About <?=SITE_NAME?></h1>
<h2>Staff</h2>
	<div class="media">
		<a class="media-left" href="u/Jas-n">
			<img class="media-object" src="<?=$user->get_avatar(1)?>" alt="Jas-n">
		</a>
		<div class="media-body">
			<h4 class="media-heading"><a href="u/Jas-n">Jas-n</a></h4>
			Owner &amp; Editor<br>
			Lover of all technologies, mostly new and cutting edge, and not just within the Sony family. Hater of all things ITV and Apple powered.
		</div>
	</div>
	<br>
	<p class="small">&hellip;and the people who have helped us out along the way:<br>Adam G (adamguest1985), Jon B (Yogdog), Phillip C (nemesisND1derboy), Gemma H (GemzH), Michael H (theshockwave), Mark L (HoboCastro), Joe N (JoeNickols), Chris P (McProley) and Ash W (ashw92).</p>
<h2>Doing Our Bit</h2>
	<p>We'd all like the world to be a better, disease-free place. Unfortunately, it's a daunting task that most of us feel like there's nothing we can do about it as individuals.</p>
	<p>But there is something we can all do; use the spare computing power on machines of all types to run <a href="https://foldingathome.stanford.edu/" target="_blank">Folding@home</a>. It's a program that will use that spare power to help find a cure from diseases including cancer, Parkinson’s and Huntington's.</p>
	<p>As well as feeling great about making the world a better place, it also allows us to be competitive with other <?=SITE_NAME?> members to fight up the <a href="http://folding.stanford.edu/stats/team/<?=$folding['team']?>" target="_blank">team</a> ranks and pushing us all up the team leader boards.</p>
	<?php $folding=json_decode(file_get_contents(ROOT.'folding.json'),1);?>
	<dl class="row">
		<dt class="col-xs-3">Team</dt>
		<dd class="col-xs-9"><a href="http://folding.stanford.edu/stats/team/<?=$folding['team']?>" target="_blank"><?=$folding['name']?> (<?=$folding['team']?>)</a></dd>
		<dt class="col-xs-3">Credit</dt>
		<dd class="col-xs-9"><?=number_format($folding['credit'])?></dd>
		<?php if($folding['rank']){ ?>
			<dt class="col-xs-3">Rank</dt>
			<dd class="col-xs-9"><?=number_format($folding['rank'])?></dd>
		<?php } ?>
	</dl>
	<p>Find out more about <a href="https://foldingathome.stanford.edu/" target="_blank">Folding@home</a></p>
<?php include('footer.php');