var root={
	compare:{},
	init:function(){
		'use strict';
		var compare;
		if(compare=sessionStorage.getItem('compare')){
			root.compare=JSON.parse(compare);
			for(var id in root.compare){
				if(id==_GET.id){
					$('.js-toggle-compare').append(' <i class="fa fa-check"></i>');
					break;
				}
			}
		}
		root.build_compare();
		$('.js-toggle-compare').click(function(e){
			if(root.compare[_GET.id]===undefined){
				root.compare[_GET.id]={
					id:_GET.id,
					slug:_GET.slug,
					title:$('h1').text()
				};
				console.log(root.compare);
				console.log(JSON.stringify(root.compare));
				sessionStorage.setItem('compare',JSON.stringify(root.compare));
				root.build_compare();
				$('.js-toggle-compare').append(' <i class="fa fa-check"></i>');
			}else{
				delete root.compare[_GET.id];
				sessionStorage.setItem('compare',root.compare);
				root.build_compare();
				$('.js-toggle-compare .fa-check').remove();
			}
		});
	},
	build_compare:function(){
		var compares=Object.keys(root.compare).length;
		var ids=[];
		if(compares){
			if(!$('.compare-link').length){
				var menu='<li class="mega compare">'+
					'<a class="compare-link" href="/vs/"></a>'+
					'<ul>';
						var i=0;
						for(var id in root.compare){
							if(compares-1<i){
								break;
							}else{
								ids.push(id);
								menu+='<li>'+
									'<a href="/p/'+id+'-'+root.compare[id].slug+'" style="background-image:url(/uploads/p/'+str_split(id,1).join('/')+'/0_thumb.png)">'+
										'<span class="title">'+root.compare[id].title+'</span>'+
									'</a>'+
								'</li>';
							}
							i++;
						}
					menu+='</ul>'+
				'</li>';
				$('#cd-navigation').prepend(menu);
			}
			$('.compare-link').attr('href','/vs/'+ids.join('/'));
			$('.compare-link').html('Compare <span class="badge badge-info">'+compares+'</span>');
		}else{
			$('.compare-link').parent().remove();
		}
	}
}
$(document).ready(function(){
	root.init();
	var mainHeader = $('.cd-auto-hide-header'),
		secondaryNavigation = $('.cd-secondary-nav'),
		//this applies only if secondary nav is below intro section
		belowNavHeroContent = $('.sub-nav-hero'),
		headerHeight = mainHeader.height();
	
	//set scrolling variables
	var scrolling = false,
		previousTop = 0,
		currentTop = 0,
		scrollDelta = 10,
		scrollOffset = 150;

	mainHeader.on('click', '.nav-trigger', function(event){
		// open primary navigation on mobile
		event.preventDefault();
		mainHeader.toggleClass('nav-open');
	});

	$(window).on('scroll', function(){
		if( !scrolling ) {
			scrolling = true;
			(!window.requestAnimationFrame)
				? setTimeout(autoHideHeader, 250)
				: requestAnimationFrame(autoHideHeader);
		}
	});

	$(window).on('resize', function(){
		headerHeight = mainHeader.height();
	});

	function autoHideHeader() {
		var currentTop = $(window).scrollTop();

		( belowNavHeroContent.length > 0 ) 
			? checkStickyNavigation(currentTop) // secondary navigation below intro
			: checkSimpleNavigation(currentTop);

	   	previousTop = currentTop;
		scrolling = false;
	}

	function checkSimpleNavigation(currentTop) {
		//there's no secondary nav or secondary nav is below primary nav
	    if (previousTop - currentTop > scrollDelta) {
	    	//if scrolling up...
	    	mainHeader.removeClass('is-hidden');
	    } else if( currentTop - previousTop > scrollDelta && currentTop > scrollOffset) {
	    	//if scrolling down...
	    	mainHeader.addClass('is-hidden');
	    }
	}

	function checkStickyNavigation(currentTop) {
		//secondary nav below intro section - sticky secondary nav
		var secondaryNavOffsetTop = belowNavHeroContent.offset().top - secondaryNavigation.height() - mainHeader.height();
		
		if (previousTop >= currentTop ) {
	    	//if scrolling up... 
	    	if( currentTop < secondaryNavOffsetTop ) {
	    		//secondary nav is not fixed
	    		mainHeader.removeClass('is-hidden');
	    		secondaryNavigation.removeClass('fixed slide-up');
	    		belowNavHeroContent.removeClass('secondary-nav-fixed');
	    	} else if( previousTop - currentTop > scrollDelta ) {
	    		//secondary nav is fixed
	    		mainHeader.removeClass('is-hidden');
	    		secondaryNavigation.removeClass('slide-up').addClass('fixed'); 
	    		belowNavHeroContent.addClass('secondary-nav-fixed');
	    	}
	    	
	    } else {
	    	//if scrolling down...	
	 	  	if( currentTop > secondaryNavOffsetTop + scrollOffset ) {
	 	  		//hide primary nav
	    		mainHeader.addClass('is-hidden');
	    		secondaryNavigation.addClass('fixed slide-up');
	    		belowNavHeroContent.addClass('secondary-nav-fixed');
	    	} else if( currentTop > secondaryNavOffsetTop ) {
	    		//once the secondary nav is fixed, do not hide primary nav if you haven't scrolled more than scrollOffset 
	    		mainHeader.removeClass('is-hidden');
	    		secondaryNavigation.addClass('fixed').removeClass('slide-up');
	    		belowNavHeroContent.addClass('secondary-nav-fixed');
	    	}

	    }
	}
});