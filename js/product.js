$('.cd-secondary-nav').click(function(e){
	'use strict';
	var target=e.target.href.split('#')[1];
	$('main section').addClass('hidden');
	$('main section#'+target).removeClass('hidden');
	$('.cd-secondary-nav a').removeClass('active');
	$(e.target).addClass('active');
});