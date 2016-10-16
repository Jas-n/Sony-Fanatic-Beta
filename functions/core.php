<?php # Built 2016-09-26 17:21:41
# Creates a custom error handler
# Updated 18-05-2016 16:19
set_error_handler('log_errors',~E_NOTICE);
register_shutdown_function(function(){
	$error = error_get_last();
	if($error["type"]==E_ERROR){
		log_errors($error["type"],$error["message"],$error["file"],$error["line"]);
	}
});
# Stores the eror in the databse if exists, otherwise uses the default error handler
# Updated 18-05-2016 16:19
function log_errors($severity,$message,$file,$line,array $context=NULL){
	global $db;
	$debug=debug();
	if(isset($db)){
		$db->error('PHP',$message,$file,$line,$severity,$debug);
	}else{
		restore_error_handler();
		print_pre($debug);
	}
	return true;
}
# Recursively add files in $dir to $zip
# $zip must be declared and opened before and closed after.
# http://php.net/manual/en/book.zip.php
# Updated 13-05-2016 09:31
function add_to_zip($dir,$zip){
	if(strpos($dir,'/')!==false && strlen($dir)!=(strpos($dir,'/')+1)){
		$dir=$dir.'/';
	}
	if(is_dir($dir)){
		foreach(scandir($dir) as $file){
			if(!in_array($file,array('.','..','backups','error_log','error_log.txt'))){
				if(is_dir($dir.$file)){
					add_to_zip($dir.$file,$zip);
				}else{
					$zip->addFile($dir.$file,str_replace('../','',$dir.$file));
				}
			}
		}
	}
}
# Base64url encode
# Updated 13-05-2016 09:31
function base64url_encode($string){
	return rtrim(strtr(base64_encode($string),'+/','-_'),'=');
}
# Base64url decode
# Updated 13-05-2016 09:31
function base64url_decode($string){
	return base64_decode(str_pad(strtr($string,'-_','+/'),strlen($string)%4,'=',STR_PAD_RIGHT));
}
# <br> to \r\n
# Updated 13-05-2016 09:31
function br2nl($text){
	return preg_replace('/<br\\s*?\/??>/i','',$text);
}
# Cleans new lines between html tags
# Updated 23-06-2016 15:47
function clean_html($html){
	return preg_replace('/\n?<(.*?)>\n/','<$1>',str_replace("\r\n","\n",$html));
}
# Limit $text to $length (Default 50)
# Updated 13-05-2016 09:31
function crop($text,$length=50){
	if(strlen($text)>$length){
		$text=strip_tags(substr($text,0,$length-1)).'&hellip;';
	}
	return $text;
}
# Easy cURL integration
# Updated 13-05-2016 09:31
function curl($url, $method = 'GET', $data = false, $headers = false, $returnInfo = false){
    $ch = curl_init();
    if($method == 'POST') {
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        if($data !== false) {
            curl_setopt($ch, CURLOPT_POSTFIELDS,http_build_query($data));
        }
    } else {
        if($data !== false) {
            if(is_array($data)) {
                $dataTokens = array();
                foreach($data as $key => $value) {
                    array_push($dataTokens, urlencode($key).'='.urlencode($value));
                }
                $data = implode('&', $dataTokens);
            }
            curl_setopt($ch, CURLOPT_URL, $url.'?'.$data);
        } else {
            curl_setopt($ch, CURLOPT_URL, $url);
        }
    }
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    if($headers !== false) {
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    }

    $contents = curl_exec($ch);
    
    if($returnInfo) {
        $info = curl_getinfo($ch);
    }
    curl_close($ch);
    if($returnInfo) {
        return array('contents' => $contents, 'info' => $info);
    } else {
        return $contents;
    }
}
# Returns a date reformated fron SQL
# Updated 13-05-2016 09:31
function sql_date($date_from_sql){
	return date(DATE_FORMAT,strtotime($date_from_sql));
}
# Returns a date and time reformated fron SQL
# Updated 13-05-2016 09:31
function sql_datetime($datetime_from_sql){
	return sql_date($datetime_from_sql).' at '.date(TIME_FORMAT,strtotime($datetime_from_sql));
}
# Trim backtrace
# Updated 13-05-2016 09:31
function debug(){
	$traces=debug_backtrace(FALSE);
	if(is_array($traces)){
		$traces=array_slice($traces,1);
		$key=sizeof($traces);
		foreach($traces as &$t){
			$trace[$key]=array(
				'file'		=>$t['file'],
				'line'		=>$t['line'],
				'function'	=>$t['function'].'()'
			);
			if($t['args']){
				$trace[$key]['args']=$t['args'];
			}
			$key--;
		}
		return $traces;
	}
	return $traces;
}
# Sends out HTML Email (Requires PHPMailer: https://github.com/PHPMailer/PHPMailer)
# Updated 19-05-2016 16:29
function email($to,$title,$description,$content,$attachments=NULL,$from=NULL){
	global $app;
	if(is_file(ROOT.'images/logos/150.png')){
		$logo='<img alt="'.SITE_NAME.'" src="'.SERVER_NAME.'/images/logos/150.png" style="max-width:600px;text-align:center;" id="headerImage">';
	}else{
		$logo=SITE_NAME;
	}
	$fields=array(
		'{{{TITLE}}}',
		'{{{DESCRIPTION}}}',
		'{{{LOGO}}}',
		'{{{CONTENT}}}',
		'{{{IF TWITTER}}}',
		'{{{TWITTER}}}',
		'{{{ENDIF TWITTER}}}',
		'{{{IF FACEBOOK}}}',
		'{{{FACEBOOK}}}',
		'{{{ENDIF FACEBOOK}}}',
		'{{{YEAR}}}',
		'{{{SITE NAME}}}',
		'{{{COMPANY ADDRESS}}}',
		'{{{LOGIN URL}}}'
	);
	$data=array(
		$title,
		$description,
		$logo,
		$content,
		defined('TWITTER')?'':'<!--',
		TWITTER,
		defined('TWITTER')?'':'-->',
		defined('FACEBOOK')?'':'<!--',
		FACEBOOK,
		defined('FACEBOOK')?'':'-->',
		date('Y'),
		SITE_NAME,
		defined('COMPANY_ADDRESS')?COMPANY_ADDRESS:'',
		SERVER_NAME.'/login'
	);
	$template=ROOT.'themes/'.THEME.'/emails/base.html';
	if(!is_file($template)){
		$template=ROOT.'/emails/base.html';
	}
	$html=str_replace($fields,$data,file_get_contents($template));
	$template=ROOT.'themes/'.THEME.'/emails/base.txt';
	if(!is_file($template)){
		$template=ROOT.'/emails/base.txt';
	}
	$txt=strip_tags(str_replace($fields,$data,file_get_contents($template)));
	# Include PHPMailer
	include_once(ROOT.'libraries/phpmailer.php');
	$emailer=new PHPMailer();
	$emailer->isHTML(true);
	$emailer->MsgHTML($html);
	$emailer->AltBody=$txt;
	if($from){
		$from=$emailer->parseAddresses($from);
		if($from[0]){
			$emailer->setFrom($from[0]['address'],$from[0]['name']);
		}
	}else{
		$emailer->setFrom(SITE_EMAIL,SITE_NAME);
	}
	$emailer->Subject=$title;
	if(!is_array($to)){
		if(strpos($to,',')!==false){
			$to=explode(',',$to);
		}else{
			$to=array($to);
		}
	}
	foreach($to as $t){
		$emailer->addAddress($t);
	}
	if($attachments!==NULL){
		if(!is_array($attachments)){
			$attachments=array($attachments);
		}
		foreach($attachments as &$attachment){
			if(is_file($attachment)){
				$emailer->addAttachment($attachment);
			}elseif(is_file(ROOT.$attachment)){
				$emailer->addAttachment(ROOT.$attachment);
			}
		}
	}
	if($emailer->Send()){
		return array(
			'status'=>true,
			'data'	=>array(
				'to'			=>$to,
				'title'			=>$title,
				'description'	=>$description,
				'content'		=>$content,
				'from'			=>$from
			)
		);
	}else{
		$app->set_message('error',"Error sending Email");
		$app->log_message(1,'Error Sending Email','Error Sending Email to \''.$to.'\'',func_get_args());
		return array(
			'status'=>false,
			'message'=>$emailer->ErrorInfo,
			'data'	=>array(
				'to'			=>$to,
				'title'			=>$title,
				'description'	=>$description,
				'content'		=>$content,
				'from'			=>$from
			)
		);
	}
}
# Font Awesome File icon (requires font awesome: http://fontawesome.io/)
# Updated 13-05-2016 09:31
function font_awesome_file_icon($ext){
	switch($ext){
		case 'doc':
		case 'docx':
			$fa='fa fa-file-word-o';
			break;
		case 'flac':
		case 'mp3':
		case 'wav':
			$fa='fa fa-file-audio-o';
			break;
		case 'pdf':
			$fa='fa fa-file-pdf-o';
			break;
		case 'xls':
		case 'xlsx':
			$fa='fa fa-file-excel-o';
			break;
		case 'png':
			$fa='fa fa-file-image-o';
			break;
		case 'zip':
			$fa='fa fa-file-archive-o';
			break;
		default:
			$fa='fa fa-file-o';
			break;
	}
	return $fa;
}
# Combines PHP's floor function with the precision functionality of PHP's round function
# Updated 13-05-2016 09:31
function floor_precision($value,$precision=NULL){
	if($precision<0){
		$precision=$precision*-1;
		return floor($value/pow(10,$precision))*pow(10,$precision);
	}elseif($precision==0 || $precision==NULL){
		return floor($value);
	}else{
		$dec=strpos($value,'.');
		$poi=substr($value,$dec+1,$precision);
		if($precision>=strlen(substr($value,strpos($value,'.')))){
			return floor($value).'.'.$poi.substr(pow(10,$precision-strlen(substr($value,strpos($value,'.')))+1),1);
		}else{
			return floor($value).'.'.$poi;
		}
	}
}
# Get browser
# Updated 13-05-2016 09:31
function getBrowser(){
	$u_agent=$_SERVER['HTTP_USER_AGENT'];
	$bname='Unknown';
	$platform='Unknown';
	$version="";
	if(preg_match('/linux/i',$u_agent)){
		$platform='linux';
	}elseif(preg_match('/macintosh|mac os x/i',$u_agent)){
		$platform='mac';
	}elseif(preg_match('/windows|win32/i',$u_agent)){
		$platform='windows';
	}
	if(preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent)){
		$bname='Internet Explorer';
		$ub="MSIE";
	}elseif(preg_match('/Edge/i',$u_agent)){
		$bname='Edge';
		$ub="Edge";
	}elseif(preg_match('/Firefox/i',$u_agent)){
		$bname='Mozilla Firefox';
		$ub="Firefox";
	}elseif(preg_match('/Chrome/i',$u_agent)){
		$bname='Google Chrome';
		$ub="Chrome";
	}elseif(preg_match('/Safari/i',$u_agent)){
		$bname='Apple Safari';$ub="Safari";
	}elseif(preg_match('/Opera/i',$u_agent)){
		$bname='Opera';
		$ub="Opera";
	}elseif(preg_match('/Netscape/i',$u_agent)){
		$bname='Netscape';
		$ub="Netscape";
	}
	$known=array('Version',$ub,'other');
	$pattern='#(?<browser>'.join('|',$known).')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
	if(!preg_match_all($pattern,$u_agent,$matches)){
	}
	$i=count($matches['browser']);
	if($i!=1){
		if(strripos($u_agent,"Version") < strripos($u_agent,$ub)){
			$version=$matches['version'][0];
		}else{
			$version=$matches['version'][1];
		}
	}else{
		$version=$matches['version'][0];
	}
	if($version==null || $version==""){
		$version="?";
	}
	return array(
		'userAgent'=>$u_agent,
		'name'=>$bname,
		'version'=>$version,
		'platform'=>$platform,
		'pattern'=>$pattern
	);
}
# Current Directory
# Updated 13-05-2016 09:31
function get_dir($level=0){
	$dir=explode('/',str_replace(ROOT,'',getcwd().'/'));
	array_pop($dir);
	return $dir[sizeof($dir)-1-$level];
}
# Convert a Hex colour to RGB
# Updated 23-06-2016 14:35
function hex2rgb($hex){
	$hex=str_replace("#","",$hex);
	if(strlen($hex)==3){
		$r=hexdec(substr($hex,0,1).substr($hex,0,1));
		$g=hexdec(substr($hex,1,1).substr($hex,1,1));
		$b=hexdec(substr($hex,2,1).substr($hex,2,1));
	}else{
		$r=hexdec(substr($hex,0,2));
		$g=hexdec(substr($hex,2,2));
		$b=hexdec(substr($hex,4,2));
	}
	$rgb=array('r'=>$r,'g'=>$g,'b'=>$b);
	return $rgb;
}
# Checks whether is formation IP
# Updated 13-05-2016 09:31
function is_formation(){
	return (bool) $_SERVER['REMOTE_ADDR']=='81.149.227.245';
}
# Is JSON
# Updated 13-05-2016 09:31
function is_json($string){
	@json_decode($string);
	return (json_last_error()==JSON_ERROR_NONE);
}
# Checks if a user is logged in
# Updated 13-05-2016 09:31
function is_logged_in(){
	if($_SESSION['user_id']){
		return true;
	}
	return false;
}
# Checks if $postcode is a valid UK one
# Updated 13-05-2016 09:31
function is_postcode($postcode){
	$postcode=strtoupper(str_replace(' ','',$postcode));
	if(preg_match("/^[A-Z]{1,2}[0-9]{2,3}[A-Z]{2}$/",$postcode) || preg_match("/^[A-Z]{1,2}[0-9]{1}[A-Z]{1}[0-9]{1}[A-Z]{2}$/",$postcode) || preg_match("/^GIR0[A-Z]{2}$/",$postcode)){
		return true;
	}
	return false;
}
# Return formatted __LINE__
# Updated 13-05-2016 09:31
function line(){
	$stack=debug_backtrace();
	echo $stack[0]['line'].'<br>';
}
# log level
# Updated 13-05-2016 09:31
function log_level($level){
	$l=array(
		1=>'Error',
		2=>'Warning',
		3=>'Information'
	);
	return $l[$level];
}
# Returns the textual represenation for $number
# Updated 13-05-2016 09:31
function number_to_words($number){
	$hyphen='-';
	$conjunction=' and ';
	$separator=', ';
	$negative='negative ';
	$decimal=' point ';
	$dictionary=array(
		0	=>'zero',
		1	=>'one',
		2	=>'two',
		3	=>'three',
		4	=>'four',
		5	=>'five',
		6	=>'six',
		7	=>'seven',
		8	=>'eight',
		9	=>'nine',
		10	=>'ten',
		11	=>'eleven',
		12	=>'twelve',
		13	=>'thirteen',
		14	=>'fourteen',
		15	=>'fifteen',
		16	=>'sixteen',
		17	=>'seventeen',
		18	=>'eighteen',
		19	=>'nineteen',
		20	=>'twenty',
		30	=>'thirty',
		40	=>'fourty',
		50	=>'fifty',
		60	=>'sixty',
		70	=>'seventy',
		80	=>'eighty',
		90	=>'ninety',
		100	=>'hundred',
		1000=>'thousand',
		1000000=>'million',
		1000000000=>'billion',
		1000000000000=>'trillion',
		1000000000000000=>'quadrillion'
	);
	if(!is_numeric($number)){
		return false;
	}
	if(($number>=0&&(int)$number<0)||(int)$number<0-PHP_INT_MAX){
		trigger_error('convert_number_to_words only accepts numbers between -'.PHP_INT_MAX.' and '.PHP_INT_MAX,E_USER_WARNING);
		return false;
	}
	if($number<0){
		return $negative.number_to_words(abs($number));
	}
	$string=$fraction=null;
	if(strpos($number,'.')!==false){
		list($number,$fraction)=explode('.',$number);
	}
	switch(true){
		case $number<21:
			$string=$dictionary[$number];
			break;
		case $number<100:
			$tens=((int)($number/10))*10;
			$units=$number%10;
			$string=$dictionary[$tens];
			if($units){
				$string.=$hyphen.$dictionary[$units];
			}
			break;
		case $number<1000:
			$hundreds=$number/100;
			$remainder=$number%100;
			$string=$dictionary[$hundreds].' '.$dictionary[100];
			if($remainder){
				$string.=$conjunction.number_to_words($remainder);
			}
			break;
		default:
			$baseUnit=pow(1000,floor(log($number,1000)));
			$numBaseUnits=(int)($number/$baseUnit);
			$remainder=$number%$baseUnit;
			$string=number_to_words($numBaseUnits).' '.$dictionary[$baseUnit];
			if($remainder){
				$string.=$remainder<100?$conjunction:$separator;
				$string.=number_to_words($remainder);
			}
			break;
	}
	if(null!==$fraction&&is_numeric($fraction)){
		$string.=$decimal;
		$words=array();
		foreach(str_split((string)$fraction) as $number){
			$words[]=$dictionary[$number];
		}
		$string.=implode(' ',$words);
	}
	return $string;
}
# Pagination
# Updated 02-08-2016 08:45
function pagination($result_count,$echo=true){
	$pages=ceil($result_count/ITEMS_PER_PAGE);
	if($pages>1){
		$page=$_GET['page'];
		$out.='<ul class="pagination pagination-sm">';
		if($pages<=10){
			for($i=1;$i<=$pages;$i++){
				$out.='<li class="page-item'.($page==$i || ($i==1 && !$page)?' active':'').'">'.pagination_link($i).'</li>';
			}
		}else{
			/*First Page*/
			$out.='<li class="page-item'.($page==1 || !$page?' active':'').'">'.pagination_link(1).'</li>';
			/*Page= 1-4*/
			if($page<5){
				if($page<4){
					$toout=6;
				}else{
					$toout=7;
				}
				for($i=2;$i<$toout;$i++){
					$out.='<li class="page-item'.($page==$i?' active':'').'">'.pagination_link($i).'</li>';
				}
				$out.='<li class="page-item disabled"><a class="page-link"><span aria-hidden="true">&hellip;</span></a></li>';
			}
			/*Page>=5*/
			elseif($page<$pages-5){
				$out.='<li class="page-item disabled"><a class="page-link"><span aria-hidden="true">&hellip;</span></a></li>';
				for($i=$page-2;$i<$page;$i++){
					$out.='<li class="page-item'.($page==$i?' active':'').'">'.pagination_link($i).'</li>';
				}
				$out.='<li class="page-item active">'.pagination_link($page).'</li>';
				for($i=$page+1;$i<=$page+2;$i++){
					$out.='<li class="page-item'.($page==$i?' active':'').'">'.pagination_link($i).'</li>';
				}
				$out.='<li class="page-item disabled"><a class="page-link"><span aria-hidden="true">&hellip;</span></a></li>';
			}
			/*If page last 5*/
			elseif($page>$pages-4){
				$out.='<li class="page-item disabled"><a class="page-link"><span aria-hidden="true">&hellip;</span></a></li>';
				if($pages-$page==3){
					$toout=5;
				}else{
					$toout=4;
				}
				for($i=$pages-$toout;$i<$pages;$i++){
					$out.='<li class="page-item'.($page==$i?' active':'').'">'.pagination_link($i).'</li>';
				}
			}else{
				$out.='<li class="page-item disabled"><a class="page-link"><span aria-hidden="true">&hellip;</span></a></li>';
				for($i=$page-2;$i<$page;$i++){
					$out.='<li class="page-item'.($page==$i?' active':'').'">'.pagination_link($i).'</li>';
				}
				
				$out.='<li class="page-item active">'.pagination_link($page).'</li>';
				for($i=$page+1;$i<=$page+2;$i++){
					$out.='<li class="page-item'.($page==$i?' active':'').'">'.pagination_link($i).'</li>';
				}
				$out.='<li class="page-item disabled"><a class="page-link"><span aria-hidden="true">&hellip;</span></a></li>';
			}
			/*Last Page*/
			$out.='<li class="page-item'.($page==$pages?' active':'').'">'.pagination_link($pages).'</li>';
		}
		$out.='</ul>';
		if($echo){
			echo $out;
		}else{
			return $out;
		}
	}
}
# Recreate pagination link
# Updated 13-09-2016 12:18
function pagination_link($page){
	$request_uri=explode('?',$_SERVER['REQUEST_URI']);
	parse_str($request_uri[1],$query_string);
	if($page==1){
		unset($query_string['page']);
	}else{
		$query_string['page']=$page;
	}
	return '<a class="page-link" href="'.$request_uri[0].(sizeof($query_string)>0?'?'.http_build_query($query_string):'').'">'.$page.'</a>';
}
# Print Preformated
# Updated 08-09-2016 17:22
function print_pre($expression,$return=false){
	$history=debug_backtrace();
	$history=$history[0];
	$out='<div class="print_pre">
		Debug<br><small><em>'.$history['file'].': '.$history['line'].'</em></small>
		<pre>'.htmlspecialchars(print_r($expression,true)).'</pre>
	</div>';
	if($return){
		return $out;
	}else{
		echo $out;
	}
}
# Generates Randon text
# Updated 13-05-2016 09:31
function random_text($length=10){
	return substr(str_shuffle(md5(microtime())),0,(int)$length);
}
# Recursively remove directory
# Updated 20/09/2016 13:00
function rrmdir($dirname){
	if(!is_dir($dirname)){
		return;
	}
	$files=array_diff(scandir($dirname),array('.','..'));
	foreach($files as $file){
		if(is_dir("$dirname/$file")){
			rrmdir("$dirname/$file");
		}else{
			unlink("$dirname/$file");
		}
	}
	return rmdir($dirname);
}
# Regenerate .ICO file with various resolutions
# Updated 13-05-2016 09:31
function generate_icons($source=NULL){
	$generated=0;
	$sizes=icon_sizes();
	if(!$source || !is_file($source)){
		$source=ROOT.'images/logos/1000.png';
	}
	$include=ROOT.'libraries/ico.php';
	$destination=ROOT.'images/icons/';
	rrmdir($destination);
	mkdir($destination,0777,1);
	copy(ROOT.'images/index.php',$destination.'index.php');
	# PNG's
	foreach($sizes as $size){
		if(smart_resize_image($source,NULL,$size,$size,0,$destination.$size.'.png',0,'png')){
			$generated++;
		}
	}
	# ICO
	if(is_file($source) && is_file($include)){
		include_once($include);
		$ico=new PHP_ICO(
			$source,
			array(
				array(16,16),
				array(32,32),
				array(48,48),
				array(64,64)
			)
		);
		$ico->save_ico($destination.'favicon.ico');
		$generated++;
	}
	file_put_contents(
		ROOT.'images/icons/manifest.json',
		json_encode((object) array(
			'name'	=>SITE_NAME,
			'icons'	=>array(
				array(
					'src'		=>'/images/icons/36.png',
					'sizes'		=>'36x36',
					'type'		=>'image/png',
					'density'	=>'0.75'
				),
				array(
					'src'		=>'/images/icons/48.png',
					'sizes'		=>'48x48',
					'type'		=>'image/png',
					'density'	=>'1.0'
				),
				array(
					'src'		=>'/images/icons/72.png',
					'sizes'		=>'72x72',
					'type'		=>'image/png',
					'density'	=>'1.5'
				),
				array(
					'src'		=>'/images/icons/96.png',
					'sizes'		=>'96x96',
					'type'		=>'image/png',
					'density'	=>'2.0'
				),
				array(
					'src'		=>'/images/icons/144.png',
					'sizes'		=>'144x144',
					'type'		=>'image/png',
					'density'	=>'3.0'
				),
				array(
					'src'		=>'/images/icons/192.png',
					'sizes'		=>'192x192',
					'type'		=>'image/png',
					'density'	=>'4.0'
				)
			)
		))
	);
	$xml=new DOMDocument('1.0','UTF-8');
	$xml_root=$xml->createElement("browserconfig");
	$xml_root=$xml->appendChild($xml_root);
	$msapplication=$xml->createElement('msapplication');
	$msapplication=$xml_root->appendChild($msapplication);
	$tile=$xml->createElement('tile');
	$tile=$msapplication->appendChild($tile);
	$s70=$xml->createElement('square70x70logo');
	$s70=$tile->appendChild($s70);
	$s70->setAttribute('src','/images/icons/70.png');
	$s150=$xml->createElement('square150x150logo');
	$s150=$tile->appendChild($s150);
	$s150->setAttribute('src','/images/icons/150.png');
	$s310=$xml->createElement('square310x310logo');
	$s310=$tile->appendChild($s310);
	$s310->setAttribute('src','/images/icons/310.png');
	$tc=$xml->createElement('TileColor');
	$tc->nodeValue=COLOUR;
	$tc=$tile->appendChild($tc);
	file_put_contents(ROOT.'browserconfig.xml',$xml->saveXML());
	if($generated){
		return $generated;
	}
	return false;
}
# Common icon sizes for different devices
# Updated 13-05-2016 09:31
function icon_sizes(){
	return array(
		310,
		192,
		180,
		152,
		150,
		144,
		120,
		114,
		96,
		76,
		72,
		70,
		60,
		57,
		32,
		16
	);
}
# Calculates days Hours:Minutes
# Updated 13-05-2016 09:31
function seconds_to_time($seconds){
	if($seconds){
	    $dtF = new DateTime("@0");
	    $dtT = new DateTime("@".$seconds);
		$date=$dtF->diff($dtT);
		$days=$date->format('%a');
		$hours=$date->format('%h');
		$minutes=$date->format('%i');
		return ($seconds<0?'-':'').sprintf('%02d',$hours+($days*24)).':'.sprintf('%02d',$minutes);
	}
	return '00:00';
}
# Removes all the bad from a string
# Updated 13-05-2016 09:31
function slug($text){
	return strtolower(str_replace(' ','_',str_replace(array("<",">","#","%","'",'"',"{","}","|","\\","^","[","]","`",";","/","?",":","@","&","=","+","$",",","(",")"),'',$text)));
}
# Smart Image resize
# Updated 13-05-2016 09:31
/**
* Image resize
* @param  $file - file name to resize
* @param  $string - The image data, as a string
* @param  $width - new image width
* @param  $height - new image height
* @param  $proportional - keep image proportional, default is no
* @param  $output - name of the new file (include path if needed)
* @param  $delete_original - if true the original image will be deleted
* @param  $use_linux_commands - if set to true will use "rm" to delete the image, if false will use PHP unlink
* @param  $quality - enter 1-100 (100 is best quality) default is 100
* @return boolean|resource
*/
function smart_resize_image($file,$string=NULL,$width=0,$height=0,$proportional=false,$output='file',$delete_original=true,$export=NULL,$use_linux_commands=false,$quality=100){
	if($height<=0 && $width<=0){
		return false;
	}
	if($file===NULL && $string===NULL){
		return false;
	}
	$info=$file!==NULL?getimagesize($file):
	getimagesizefromstring($string);
	$image='';
	$final_width=0;
	$final_height=0;
	list($width_old,$height_old)=$info;
	$cropHeight=$cropWidth=0;
	if($proportional){
		if($width==0){
			$factor=$height/$height_old;
		}elseif($height==0){
			$factor=$width/$width_old;
		}else{
			$factor=min($width/$width_old,$height/$height_old);
		}
		$final_width=round($width_old*$factor);
		$final_height=round($height_old*$factor);
	}else{
		$final_width=($width<=0)?$width_old:$width;
		$final_height=($height<=0)?$height_old:$height;
		$widthX=$width_old/$width;
		$heightX=$height_old/$height;
		$x=min($widthX,$heightX);
		$cropWidth=($width_old-$width*$x)/2;
		$cropHeight=($height_old-$height*$x)/2;
	}
	switch($info[2]){
		case IMAGETYPE_JPEG:
		$file!==null?$image=imagecreatefromjpeg($file):$image=imagecreatefromstring($string);
		break;
	case IMAGETYPE_GIF:
		$file!==null?$image=imagecreatefromgif($file):$image=imagecreatefromstring($string);
		break;
	case IMAGETYPE_PNG:
		$file!==null?$image=imagecreatefrompng($file):$image=imagecreatefromstring($string);
		break;
	default:
		return false;
	}
	$image_resized=imagecreatetruecolor($final_width,$final_height);
	if(($info[2]==IMAGETYPE_GIF) || ($info[2]==IMAGETYPE_PNG)){
		$transparency=imagecolortransparent($image);
		$palletsize=imagecolorstotal($image);
		if($transparency>=0 && $transparency<$palletsize){
			$transparent_color=imagecolorsforindex($image,$transparency);
			$transparency=imagecolorallocate($image_resized,$transparent_color['red'],$transparent_color['green'],$transparent_color['blue']);
			imagefill($image_resized,0,0,$transparency);
			imagecolortransparent($image_resized,$transparency);
		}elseif($info[2]==IMAGETYPE_PNG){
			imagealphablending($image_resized,false);
			$color=imagecolorallocatealpha($image_resized,0,0,0,127);
			imagefill($image_resized,0,0,$color);
			imagesavealpha($image_resized,true);
		}
	}
	imagecopyresampled($image_resized,$image,0,0,$cropWidth,$cropHeight,$final_width,$final_height,$width_old-2*$cropWidth,$height_old-2*$cropHeight);
	if($delete_original){
		if($use_linux_commands){
			exec('rm '.$file);
		}else{
			@unlink($file);
		}
	}
	switch(strtolower($output)){
		case 'browser':
			$mime=image_type_to_mime_type($info[2]);
			header("Content-type: $mime");
			$output=NULL;
			break;
		case 'file':
			$output=$file;
			break;
		case 'return':
			return $image_resized;
			break;
		default:
			break;
	}
	if($export){
		$info[2]=strtolower($export);
	}
	switch($info[2]){
		case IMAGETYPE_GIF:
		case 'gif':
			imagegif($image_resized,$output);
			break;
		case IMAGETYPE_JPEG:
		case 'jpg':
		case 'jpeg':
			imagejpeg($image_resized,$output,$quality);
			break;
		case IMAGETYPE_PNG:
		case 'png':
			$quality=9-(int)((0.9*$quality)/10.0);
			imagepng($image_resized,$output,$quality);
			break;
		default:
			return false;
	}
	return true;
}
# Software Info
# Updated 30-08-2016 09:28
function software_info(){
	if(is_file(ROOT.'build.txt')){
		if($build=@file_get_contents(ROOT.'build.txt')){
			$info['build']=$build;
		}
	}elseif(is_file(ROOT.'version.txt')){
		if($version=@file_get_contents(ROOT.'version.txt')){
			$info['version']=$version;
		}
	}
	return $info;
}
# Software version
# Updated 30-08-2016 09:28
function software_version(){
	$temp=software_info();
	if($temp['build']){
		return $temp['build'];
	}
	return $temp['version'];
}
# Strpos of needle array
# Updated 13-05-2016 09:31
function strposa($haystack,$needle,$offset=0) {
    if(!is_array($needle)){
		$needle=array($needle);
	}
	foreach($needle as $query){
		if(strpos($haystack,$query,$offset)!==false){
			return true;
		}
	}
    return false;
}
# Get Template
# Updated 13-05-2016 09:31
function get_template($template_name,$args=array()){
	if($args && is_array($args)){
		extract($args);
	}
	$template=ROOT."themes/".THEME."/".$template_name.".php";
	if(!is_file($template)){
		if(DEBUG){
			echo "Template (".$template.") does not exist.";
		}
		return false;
	}else{
		include($template);
	}
}
# Template exists or not
# Updated 13-05-2016 09:31
function template_exists($template){
	$template=ROOT."themes/".THEME."/".$template.".php";
	if(is_file($template)){
		return true;
	}
	return false;
}
# Converts string to an array of RGB values
# Updated 13-05-2016 09:31
function text2rgba($text,$opacity=1){
	$hex=substr(md5($text),0,6);
	list($r,$g,$b)=array($hex[0].$hex[1],$hex[2].$hex[3],$hex[4].$hex[5]);
	$r=hexdec($r);
	$g=hexdec($g);
	$b=hexdec($b);
	return array(
		'r'=>$r,
		'g'=>$g,
		'b'=>$b,
		'a'=>min(array($opacity,1))
	);
}
# Time ago
# Updated 13-05-2016 09:31
function time_ago($sql_date){
	$time=time()-strtotime($sql_date);
	if($time<60){
		return number_format($time,0)." seconds ago";
	}elseif($time/60<60){
		return number_format($time/60)." minutes ago";
	}elseif($time/60/60<60){
		return number_format($time/60/60)." hours ago";
	}elseif($time/60/60/24<24){
		return number_format($time/60/60/24)." days ago";
	}elseif($time/60/60/24/7<30.4375){
		return number_format($time/60/60/24/7)." weeks ago";
	}elseif($time/60/60/24/30.4375<365.25){
		return number_format($time/60/60/24/30.4375)." months ago";
	}else{
		return number_format($time/60/60/24/365.25)." years ago";
	}
}
# Returns a structured tree of values.
# Updated 13-05-2016 09:31
function tree(array &$elements,$parent_id=0){
	$branch=[];
	foreach($elements as $key=>$element){
		if($element['parent_id']==$parent_id){
			$children=tree($elements,$key);
			if($children){
				$element['children']=$children;
			}
			$branch[$key]=$element;
			unset($elements[$key]);
		}
	}
	return $branch;
}
# Upload a file
# Updated 19/09/2016 10:28
function upload($file,$to,$name=NULL){
	upload_folders($to);
	if(strpos($file['type'],'image')!==false){
		list($width,$height)=getimagesize($file['tmp_name']);
		smart_resize_image($file['tmp_name'],NULL,$width>=150?150:$width,0,1,ROOT.'uploads/'.$to.'/thumb.png',0,'png');
		if($width > 1000 || $height > 1000){
			smart_resize_image($file['tmp_name'],NULL,$width,$height>=1000?1000:$height,1,ROOT.'uploads/'.$to.'/full.png',0,'png');
		}else{
			smart_resize_image($file['tmp_name'],NULL,$width,$height,1,ROOT.'uploads/'.$to.'/full.png',0,'png');
		}
	}else{
		move_uploaded_file($file['tmp_name'],ROOT.'uploads/'.$to.'/'.($name?$name.'.'.$file['extension']:$file['name']));
	}
}
# Create upload folders and copy index files to redirect user to prevent them from seeing file list.
# Updated 17-05-2016 17:14
function upload_folders($folders){
	if(!is_array($folders)){
		$folders=array($folders);
	}
	foreach($folders as $folder){
		if(is_array($folder)){
			upload_folders($folder);
		}else{
			if(!is_dir(ROOT.'uploads/'.$folder)){
				mkdir(ROOT.'uploads/'.$folder,0777,1);
			}
			$folds=explode('/',$folder);
			foreach($folds as $i=>$fold){
				$fldr='';
				for($j=0;$j<=$i;$j++){
					$fldr.='/'.$folds[$j];
				}
				copy(ROOT.'uploads/index.php',ROOT.'uploads/'.substr($fldr,1).'/index.php');
			}
		}
	}
}
# Validates an email according to RFC5321
# Updated 09/09/2016 16:01
function validate_email($email){
	return preg_match('/^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/',$email)===1;
}
# Make a hex colour lighter or darker
# If a positive integer is passed, the returning RGB will be lighter.
# If a positive number is passed, the returning RGB will be darker
# Updated 27/09/2016 15:52
function colour_lighten($hex_colour,$percent=0){
	$colour=hex2rgb($hex_colour);
	if($percent>1 || $percent<-1){
		$percent=$percent/100;
	}
	if($percent>0){
		$colour['r']=floor($colour['r']+(255-$colour['r'])*$percent);
		$colour['g']=floor($colour['g']+(255-$colour['g'])*$percent);
		$colour['b']=floor($colour['b']+(255-$colour['b'])*$percent);
	}elseif($percent<0){
		$percent=$percent*-1;
		$colour['r']=floor($colour['r']*(1-$percent));
		$colour['g']=floor($colour['g']*(1-$percent));
		$colour['b']=floor($colour['b']*(1-$percent));
	}
	return $colour;
}