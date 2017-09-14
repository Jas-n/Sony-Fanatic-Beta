var users={
	error_id:		0,
	hints:			'',
	hints_positioned:false,
	loading:		'',
	page_html:		'',
	prev_active:	$('nav .active'),
	result_count:	0,
	results:		'',
	searching:		0,
	tabs:			0,
	// Functions
	init:			function(){
		$("[data-toggle=tooltip]").tooltip();
		users.tab_select();
		$('.global_search .fa').click(function(){
			$(this.parentNode).toggleClass('active');
			if($(this.parentNode).hasClass('active')){
				setTimeout(function(){
					$('.global_search input')[0].focus();
				},250);
			}else{
				$('.global_search input').blur();
			}
		});
		$('.global_search input').keyup(function(e){
			if(!$('main.search_results').length){
				$('main').addClass('hidden').before('<main class="container-fluid search_results">'+core.info('Searching <strong id="search_location">Everywhere</strong> for <strong id="search_term">'+e.target.value+'</strong>&hellip;','search_info')+'<div class="results"></div></main>');
			}
			if(e.target.value.length){
				clearTimeout(users.searching);
				if(e.which == 27){
					return false;
				}
				$('#search_term').text(e.target.value);
				$('.search_results .results').html('');
				users.searching=setTimeout(
					function(){
						var url="../ajax/search.php?term="+e.target.value;
						if(!window.EventSource){
							$.ajax({
								context	:this,
								dataType:'json',
								url		:url,
								success:function(data){
									for(var i=0;i<data.length;i++){
										users.process(data[i]);
									}
								},
								error	:function(){
									$(this.parentNode).append("<div class='alert alert-danger' role='alert'>Error getting search results.</div>");
									setTimeout(function(){
										$('#'+ms).hide();
									},3000);
								}
							});
						}else{
							users.searcher=new EventSource(url+"&method=stream");
							users.searcher.onmessage=function(e){
								users.process(JSON.parse(e.data));
								users.tab_select();
							};
						}
					},500
				);
			}else{
				clearTimeout(users.searching);
				$('main.search_results').remove();
				$('main.hidden').removeClass('hidden');
			}
		});
		$('nav .has_children a').click(function(){
			var this_list_item = $(this).closest('.has_children');
			var is_active = this_list_item.hasClass('active');
			$('nav .has_children').removeClass('active');
			$('.user_interactions .has_children').removeClass('active');
			if(!is_active){
				this_list_item.addClass('active');
			}
		});
		$('.user_interactions .has_children a').click(function(){
			$('nav .has_children').removeClass('active');
			$('.user_interactions .has_children').removeClass('active');
			$(this.parentNode).addClass('active');
		});
		$('main').on('click',function(){
			$('.user_interactions .has_children').removeClass('active');
			$('nav .has_children').removeClass('active');
		});
		$('header').click(function(e){
			e.stopPropagation();
			$('nav .has_children').removeClass('active');
		});
		$('nav').click(function(e){
			e.stopPropagation();
			$('.user_interactions .has_children').removeClass('active');
		});
	},
	process:		function(jsondata){
		console.log(jsondata);
		if(jsondata.status=='close'){
			users.searcher.close();
			$('#search_info').remove();
			return false;
		}
		$('#search_location').text(jsondata.name);
		if(jsondata.count>0){
			console.log(jsondata);
			var search_html='<div class="card card-body card-shadow"><h2>'+jsondata.name+'</h2>'+
				'<div class="table-responsive"><table class="table table-hover table-striped"><thead><tr>';
					var keys=Object.keys(jsondata.data[0]);
					for(var i=0;i<keys.length;i++){
						search_html+="<th>"+keys[i]+"</th>";
					}
				search_html+='<tr></thead><tbody>';
					for(i=0;i<jsondata.data.length;i++){
						search_html+="<tr>";
						for(j=0;j<keys.length;j++){
							search_html+="<td>"+jsondata.data[i][keys[j]]+"</td>";
						}
						search_html+="</tr>";
					}
				search_html+='</table>'+
			'</div>';
			$('.search_results .results').append(search_html);
		}
		return false;
	},
	tab_select:		function(){
		var hashings=window.location.hash.substr(1).split('&');
		if(hashings){
			for(var i=0;i<hashings.length;i++){
				var pair=hashings[i].split('=');
				if(pair[0]==='tab'){
					$('ul[role="tablist"] a[href="#'+pair[1]+'"]').tab('show');
				}
			}
		}
	}
};
$(document).ready(function(){
	users.init();
});
// Help draw
$('.help_draw_trigger').click(function(){
	$('main').toggleClass('open_help');
	$('.help_draw').toggleClass('open');
});