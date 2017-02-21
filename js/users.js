$('.has_children a').click(function(){
	$('nav .has_children').removeClass('active');
	$(this.parentNode).addClass('active');
});
$('main').click(function(){
	$('nav .has_children').removeClass('active');
});