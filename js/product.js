if(window.location.hash){
	if($(window.location.hash)){
		var target=window.location.hash.split('#')[1];
		$('main section').addClass('hidden');
		$('main section#'+target).removeClass('hidden');
		$('.cd-secondary-nav a').removeClass('active');
		$('[href="#'+target+'"]').addClass('active');
	}
}
$('.cd-secondary-nav').click(function(e){
	'use strict';
	var target=e.target.href.split('#')[1];
	$('main section').addClass('hidden');
	$('main section#'+target).removeClass('hidden');
	$('.cd-secondary-nav a').removeClass('active');
	$(e.target).addClass('active');
});