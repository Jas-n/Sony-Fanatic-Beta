var adsize,
	focused=true,
	footersize,
	delete_alert,
	browser_notifications=false;
$(document).ready(function() {
	'use strict';
	var render_time=$('#render_time')[0];
	/*if(render_time){
		var js_process=((Date.now ? Date.now() : new Date().getTime())-render_start)/1000+Number(render_time.innerHTML);
		$('#render_time').html('Rendered in '+js_process.toFixed(2)+'s').removeClass('hidden');
	}*/
});
// Make all tables responsive
function responsiveTables(){
	$('table').each(function(index,element){
		var table = $(this);
		if(!table.parent().hasClass('table-responsive')){
			table.wrap('<div class="table-responsive"></div>');
		}
		if(table.children('thead').length > 0){
			table.children('thead').find('th').each(function(index,element){
				index++;
				if($(this).children().length > 0){
					if($(this).find('fieldset[class*="_check"]').length > 0){
						$(this).attr('data-heading','Select All');
						$(this).addClass('show-heading');
						table.find('tbody').find('td:nth-child('+index+')').attr('data-heading','Select');
					}
				}else{
					var heading = $(this).text();
					if(heading != ''){ table.find('tbody').find('td:nth-child('+index+')').attr('data-heading',heading); }
				}
			});
		}
	});
}
$(document).ready(function(){
	responsiveTables();
});

// Assign titles to tab panels
$('.nav-tabs .nav-item').each(function(index,element){
	index++;
	var nav_text = $(this).find('a').text();
	var tab_content = $(this).closest('.nav-tabs').next('.tab-content');
	tab_content.find('.tab-pane:nth-child('+index+')').attr('data-tab-title',nav_text);
});

$('.trigger-load').click(function(e){
	$('#loading').removeClass('hidden');
});
$(window).focus(function(){
	focused=true;
}).blur(function() {
	focused=false;
});
// Start notifications
/*if(window.innerWidth>560 && user_id!=0){
	if(!("Notification" in window)){
		console.log('Notifications not supported');
	}else if(Notification.permission==="granted"){
		browser_notifications=true;
	}else if(Notification.permission!=='denied'){
		Notification.requestPermission();
	}
	var url="../ajax/notifications.php";
	if(!window.EventSource){
		setInterval(
			function(){
				$.ajax({
					url:url,
					context:this,
					success:function(data){
						data=JSON.parse(data);
						if(data){
							process_notifications(data);
						}
					},
					error:function(data){
						console.log('error');
					}
				});
			},
			3000
		);
	}else{
		searcher=new EventSource(url+"?method=stream");
		searcher.onmessage=function(e){
			process_notifications(JSON.parse(e.data));
		}
	}
}*/
function process_notifications(data){
	var notified='';
	if(localStorage.getItem('notified')){
		notified=JSON.parse(localStorage.getItem('notified'));
	}else{
		notified=[];
	}
	for(var i=0;i<data.length;i++){
		if(notified.indexOf(data[i].id)===-1){
			// Not notified
			if(browser_notifications && Notification.permission==="granted" && !focused){
				if(data[i].icon===undefined){
					data[i].icon='/images/icons/76.png';
				}
				// If has permissions and tab not focused
				var notification = new Notification(
					data[i].title,
					{
						body:data[i].clean_message,
						icon:data[i].icon
					}
				);
				setTimeout(
					function(){
						notification.close();
					},
					10000
				);
			}else{
				// Else show on site
				display_notification(data[i].title,data[i].message,data[i].formatted_date,data[i].icon)
			}
			notified[notified.length]=data[i].id;
			localStorage.setItem('notified',JSON.stringify(notified.slice(-25)));
		}
	}
}
$('#sn_notifications').on('click','.sn_close',function(e){
	$(this.parentNode.parentNode).remove();
});
// Give a warning for all delete items
$(document).on('click','.delete',function(e){
	var delete_message;
	if(e.target.dataset.delete_prompt){
		delete_message=e.target.dataset.delete_prompt;
	}else{
		delete_message='Are you sure you want to delete the selected item(s)?';
	}
	if(window.confirm(delete_message)===false){
		e.stopPropagation();
		$.event.trigger({
			status:false,
			target:e.target,
			type:'delete_confirm'
		});
		return false;
	}
	$.event.trigger({
		status:true,
		target:e.target,
		type:'delete_confirm'
	});
	return true;
});
// make .popup popup
$('a.popup').click(function(){
	var h=this.dataset.height||250,
		w=this.dataset.width||250,
		a=window.open(this.getAttribute('href'),'','height='+h+',width='+w);
	if(window.focus){
		a.focus();
	}
	return false;
});
function display_notification(title,message,date,icon){
	var ms=Math.round(microtime(true)*1000);
	if(!date){
		var date=new Date();
		date=date.getDate()+' '+date.getMonth()+' '+date.getYear();
	}
	if(!icon){
		var img=new Image();
		img.src='/images/icons/76.png';
		if(img.height){
			icon=img.src;
		}
	}
	html='<div class="sn_notification" id="sn_'+ms+'">';
		if(icon){
			html+='<img class="sn_icon" src="'+icon+'" height="40">';
		}
		html+='<p class="sn_message"><a class="sn_close">×</a><strong>'+title+'</strong>:<br>'+
		message+'<br>'+
		'<strong>Created</strong>: '+date+'</p>'+
	'</div">';
	$('#sn_notifications').prepend(html);
	$('#sn_'+ms).delay(5000).animate(
		{
			right:-400
		},
		400,
		function(){
			$(this).delay(300).animate(
				{
					height:0
				},
				300,
				function(){
					$(this).remove();
				}
			);
		}
	);
}
function error(message,id){
	var out='<div class="alert alert-danger"';
	if(id){
		out+=' id="'+id+'"';
	}
	out+=' role="alert"><p>'+message+'</p></div>';
	return out;
}
function info(message,id){
	var out='<div class="alert alert-info"';
	if(id){
		out+=' id="'+id+'"';
	}
	out+=' role="alert"><p>'+message+'</p></div>';
	return out;
}
function success(message,id){
	var out='<div class="alert alert-success"';
	if(id){
		out+=' id="'+id+'"';
	}
	out+=' role="alert"><p>'+message+'</p></div>';
	return out;
}
function warning(message,id){
	var out='<div class="alert alert-warning"';
	if(id){
		out+=' id="'+id+'"';
	}
	out+=' role="alert"><p>'+message+'</p></div>';
	return out;
}
// Remove item from array
Array.prototype.remove = function(from, to) {
  var rest = this.slice((to || from) + 1 || this.length);
  this.length = from < 0 ? this.length + from : from;
  return this.push.apply(this, rest);
};
// PHP FUNCTIONS
// - Base64url encode
function base64url_encode(data){return $.trim(strtr(base64_encode(data),'+/','-_'),'=');}
// - Base64url decode
function base64url_decode(data){return base64_decode(str_pad(strtr(data,'-_','+/'),strlen(data)%4,'=',STR_PAD_RIGHT));}
// - Base64 encode
function base64_encode(e){var t="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=";var n,r,i,s,o,u,a,f,l=0,c=0,h="",p=[];if(!e){return e}do{n=e.charCodeAt(l++);r=e.charCodeAt(l++);i=e.charCodeAt(l++);f=n<<16|r<<8|i;s=f>>18&63;o=f>>12&63;u=f>>6&63;a=f&63;p[c++]=t.charAt(s)+t.charAt(o)+t.charAt(u)+t.charAt(a)}while(l<e.length);h=p.join("");var d=e.length%3;return(d?h.slice(0,d-3):h)+"===".slice(d||3)}
// - Base64 decode */
function base64_decode(e){var t="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=";var n,r,i,s,o,u,a,f,l=0,c=0,h="",p=[];if(!e){return e}e+="";do{s=t.indexOf(e.charAt(l++));o=t.indexOf(e.charAt(l++));u=t.indexOf(e.charAt(l++));a=t.indexOf(e.charAt(l++));f=s<<18|o<<12|u<<6|a;n=f>>16&255;r=f>>8&255;i=f&255;if(u==64){p[c++]=String.fromCharCode(n)}else if(a==64){p[c++]=String.fromCharCode(n,r)}else{p[c++]=String.fromCharCode(n,r,i)}}while(l<e.length);h=p.join("");return h.replace(/\0+$/,"")}
// - Microtime
function microtime(e){var n,r;return"undefined"!=typeof performance&&performance.now?(r=(performance.now()+performance.timing.navigationStart)/1e3,e?r:(n=0|r,Math.round(1e6*(r-n))/1e6+" "+n)):(r=(Date.now?Date.now():(new Date).getTime())/1e3,e?r:(n=0|r,Math.round(1e3*(r-n))/1e3+" "+n))}
// - nl2br
function nl2br(r,n){var e=n||"undefined"==typeof n?"<br />":"<br>";return(r+"").replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g,"$1"+e+"$2")}
// - strpad
function str_pad(e,t,n,r){var i="",s;var o=function(e,t){var n="",r;while(n.length<t){n+=e}n=n.substr(0,t);return n};e+="";n=n!==undefined?n:" ";if(r!=="STR_PAD_LEFT"&&r!=="STR_PAD_RIGHT"&&r!=="STR_PAD_BOTH"){r="STR_PAD_RIGHT"}if((s=t-e.length)>0){if(r==="STR_PAD_LEFT"){e=o(n,s)+e}else if(r==="STR_PAD_RIGHT"){e=e+o(n,s)}else if(r==="STR_PAD_BOTH"){i=o(n,Math.ceil(s/2));e=i+e+i;e=e.substr(0,t)}}return e}
// - strtr
function strtr(e,t,n){var r="",i=0,s=0,o=0,u=0,a=false,f="",l="",c="";var h=[];var p=[];var d="";var v=false;if(typeof t==="object"){a=this.ini_set("phpjs.strictForIn",false);t=this.krsort(t);this.ini_set("phpjs.strictForIn",a);for(r in t){if(t.hasOwnProperty(r)){h.push(r);p.push(t[r])}}t=h;n=p}o=e.length;u=t.length;f=typeof t==="string";l=typeof n==="string";for(i=0;i<o;i++){v=false;if(f){c=e.charAt(i);for(s=0;s<u;s++){if(c==t.charAt(s)){v=true;break}}}else{for(s=0;s<u;s++){if(e.substr(i,t[s].length)==t[s]){v=true;i=i+t[s].length-1;break}}}if(v){d+=l?n.charAt(s):n[s]}else{d+=e.charAt(i)}}return d}
// URL Encode
function urlencode(str){str=(str+'').toString();return encodeURIComponent(str).replace(/!/g,'%21').replace(/'/g,'%27').replace(/\(/g,'%28').replace(/\)/g,'%29').replace(/\*/g,'%2A').replace(/%20/g,'+');}