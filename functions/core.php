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
function curl($url,$method='GET',$data=false,$headers=false,$returnInfo=false){
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
	if(strtotime($date_from_sql)<=0){
		return 'N/a';
	}
	return date(DATE_FORMAT,strtotime($date_from_sql));
}
# Returns a date and time reformated fron SQL
# Updated 13-05-2016 09:31
function sql_datetime($datetime_from_sql){
	if(strtotime($datetime_from_sql)<=0){
		return 'N/a';
	}
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
# Current Directory
# Updated 13-05-2016 09:31
function get_dir($level=0){
	$dir=explode('/',str_replace(ROOT,'',getcwd().'/'));
	array_pop($dir);
	return $dir[sizeof($dir)-1-$level];
}
# Checks if a user is logged in
# Updated 13-05-2016 09:31
function is_logged_in(){
	if($_SESSION['user_id']){
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
	return strtolower(str_replace(' ','-',str_replace(array("<",">","#","%","'",'"',"{","}","|","\\","^","[","]","`",";","/","?",":","@","&","=","+","$",",","(",")"),'',$text)));
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