$(document).ready(function(e){
	$('.has_children a').click(function(e){
		if(!$(e.target).parents('.has_children.active').length){
			$('nav .has_children').removeClass('active');
		}
		$(e.target.parentNode).toggleClass('active');
	});
});