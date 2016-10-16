<?php # Version 160412-1606
class app{
	protected $addtofoot=array();
	protected $addtohead=array();
	protected $errors=array();
	protected $information=array();
	protected $success=array();
	protected $warnings=array();
	
	public $page_title;
	public $require=array();
	
	public function add_to_foot($code){
		$this->addtofoot[]=$code;
	}
	public function add_to_head($code){
		$this->addtohead[]=$code;
	}
	public function get_css(){
		global $db,$form_included;
		if(is_array($this->require)){
			$require=array_map('strtolower',$this->require);
		}
		$css_files[]='//fonts.googleapis.com/css?family=Heebo:300,400,500';
		$css_files[]='/css/bootstrap-reboot.css" rel="stylesheet';
		$css_files[]='/css/bootstrap-flex.css" rel="stylesheet';
		$css_files[]='/css/bootstrap-grid.css" rel="stylesheet';
		if($form_included){
			$css_files[]='//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/css/bootstrap-datepicker.min.css';
		}
		$css_files[]='/css/core.css';
		if($require && in_array('js.lightbox',$require)){
			$css_files[]='//cdnjs.cloudflare.com/ajax/libs/bootstrap-lightbox/0.7.0/bootstrap-lightbox.min.css';
		}
		#Include folder specific CSS
		if(get_dir() && is_file(ROOT.'css/'.get_dir().'.css')){
			$css_files[]='/css/'.get_dir().'.css';
		}
		if(!get_dir()){
			$css_files[]='/css/root.css';
		}
		$css_files[]='/css/print.css';
		# File specific CSS
		if(is_file(ROOT.'css/'.substr($_SERVER['PHP_SELF'],1,-3).'css')){
			$css_files[]='/css/'.substr($_SERVER['PHP_SELF'],1,-3).'css';
  		}
		foreach($css_files as $css_file){
			$out.='<link rel="stylesheet" href="'.$css_file.'">';
		}
		$out.='<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.6.3/css/font-awesome.css">';
		echo $out;
	}
	public function get_head_js(){
		if($this->addtohead){
			foreach($this->addtohead as $head){
				$out.=htmlspecialchars_decode($head);
			}
		}
		echo $out;
	}
	public function get_icons(){
		if(is_dir(ROOT.'images/icons')){
			echo '<link rel="apple-touch-icon"	href="/images/icons/57.png"		sizes="57x57">
			<link rel="apple-touch-icon"		href="/images/icons/60.png" 	sizes="60x60">
			<link rel="apple-touch-icon"		href="/images/icons/72.png" 	sizes="72x72">
			<link rel="apple-touch-icon"		href="/images/icons/76.png" 	sizes="76x76">
			<link rel="icon"					href="/images/icons96.png"		sizes="96x96"	type="image/png">
			<link rel="apple-touch-icon"		href="/images/icons/114.png"	sizes="114x114">
			<link rel="apple-touch-icon"		href="/images/icons/120.png"	sizes="120x120">
			<link rel="apple-touch-icon"		href="/images/icons/144.png"	sizes="144x144">
			<link rel="apple-touch-icon"		href="/images/icons/152.png"	sizes="152x152">
			<link rel="apple-touch-icon"		href="/images/icons/180.png"	sizes="180x180">
			<link rel="icon"					href="/images/icons/192.png"	sizes="192x192"	type="image/png">
			<link rel="shortcut icon"			href="/images/icons/favicon.ico">
			<link rel="manifest"				href="/images/icons/manifest.json">
			<meta name="msapplication-TileColor"	content="'.COLOUR.'">
			<meta name="msapplication-TileImage"	content="/144.png">
			<meta name="theme-color"				content="'.COLOUR.'">';
		}
	}
	public function get_foot_js(){
		global $db,$form_included,$page,$user,$render_start;
		$require=array_map('strtolower',$this->require);
		$out='<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
		<script src="//cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.0/jquery-ui.min.js"></script>';
		if(in_array('js.tooltip',$require) || in_array('php.calendar',$require)){
			$out.='<script src="//cdnjs.cloudflare.com/ajax/libs/tether/1.3.7/js/tether.min.js"></script>';
		}
		$out.='<script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-alpha.4/js/bootstrap.min.js"></script>';
		if($require && in_array('js.lightbox',$require)){
			$out.='<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-lightbox/0.7.0/bootstrap-lightbox.min.js"></script>';
		}
		if($form_included){
			$out.='<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/js/bootstrap-datepicker.min.js"></script>';
		}
		if(in_array('js.tinymce',$require)){
			$out.='<script src="//cdnjs.cloudflare.com/ajax/libs/tinymce/4.4.3/tinymce.min.js"></script>';
		}
		if(in_array('php.calendar',$require)){
			$out.='<script src="/js/calendar.js"></script>';
		}
		if(in_array('php.clients',$require)){
			$out.='<script src="/js/clients.js"></script>';
		}
		$out.='<script>';
			$out.='var _GET={';
				if($_GET){
					foreach($_GET as $key=>$value){
						$out.=$key.':"'.$value.'",';
					}
				}
			$out.='};
			var is_logged_in='.(is_logged_in()?'true':'false').';
			var page="'.$page->slug.'";
			var user_id='.($user->id?$user->id:0).';';
			if(in_array('js.sortable',$require) || in_array('js.tinymce',$require) || in_array('js.tooltip',$require) || $form_included){
				$out.='$(document).ready(function(){';
					if(in_array('js.tooltip',$require)){
						$out.='$("[data-toggle=tooltip]").tooltip();';
					}
					if(in_array('js.tinymce',$require)){
						$out.='tinymce.init({
							browser_spellcheck:true,
							content_css:"/css/tinymce.css",
							menubar:false,
							min_height:200,
							plugins:"link,paste,code",
							paste_auto_cleanup_on_paste:true,
							selector:".tinymce",
							statusbar:false,
							style_formats:[
								{title: "Headers",items:[
									{title:"Header 2",format:"h2"},
									{title:"Header 3",format:"h3"},
									{title:"Header 4",format:"h4"},
									{title:"Header 5",format:"h5"},
									{title:"Header 6",format:"h6"}
								]},
								{title:"Inline",items:[
									{title:"Underline",icon:"underline",format:"underline"},
									{title:"Strikethrough",icon:"strikethrough",format:"strikethrough"},
									{title:"Superscript",icon:"superscript",format:"superscript"},
									{title:"Subscript",icon:"subscript",format:"subscript"}
								]},
								{title:"Blocks",items:[
									{title:"Paragraph",format:"p"},
									{title:"Blockquote",format:"blockquote"}
								]},
								{title:"Alignment",items:[
									{title:"Left",icon:"alignleft",format:"alignleft"},
									{title:"Center",icon:"aligncenter",format:"aligncenter"},
									{title:"Right",icon:"alignright",format:"alignright"},
									{title:"Justify",icon:"alignjustify",format:"alignjustify"}
								]}
							],
							toolbar1:"undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link code",
						});';
					}
					if(in_array('js.sortable',$require)){
						$out.='$(".sortable").sortable();
						$(".sortable").disableSelection();';
					}
				$out.='});';
			}
		$out.='</script>
		<script src="/js/core.js"></script>';
		if(!get_dir()){
			$out.='<script src="/js/root.js"></script>';
		}
		if($GLOBALS['form_included']){
			$out.='<script src="/js/form.js"></script>';
		}
		$out.='<script src="/js/locations.js"></script>';
		if(in_array('js.searcher',$require)){
			$out.='<script src="/js/searcher.js"></script>';
		}
		#Include folder specific JS
		if(is_file(ROOT.'js/'.get_dir().'.js')){
			$out.='<script src="/js/'.get_dir().'.js"></script>';
		}
		if(is_file(ROOT.'js/'.substr($_SERVER['PHP_SELF'],1,-3).'js')){
			# New
			$out.='<script src="/js/'.substr($_SERVER['PHP_SELF'],1,-3).'js"></script>';
  		}
		echo $out;
	}
	public function get_messages(){
		if($this->errors){
			$out='<div class="alert alert-danger" role="alert">';
				foreach($this->errors as $error){
					$out.='<p>'.$error.'</p>';
				}
			$out.='</div>';
		}
		if($this->warnings){
			$out.='<div class="alert alert-warning" role="alert">';
				foreach($this->warnings as $warning){
					$out.='<p>'.$warning.'</p>';
				}
			$out.='</div>';
		}
		if($this->information){
			$out.='<div class="alert alert-info" role="alert">';
				foreach($this->information as $information){
					$out.='<p>'.$information.'</p>';
				}
			$out.='</div>';
		}
		if($this->success){
			$out.='<div class="alert alert-success" role="alert">';
				foreach($this->success as $success){
					$out.='<p>'.$success.'</p>';
				}
			$out.='</div>';
		}
		echo $out;
	}
	# Render message now
	public function show_message($type,$message){
		switch(strtolower($type)){
			case 'error':
			case 1:
				$class="danger";
				break;
			case 'info':
			case 'information':
				$class="info";
				break;
			case 'success':
			case 3:
				$class="success";
				break;
			case 'warning':
			case 2:
				$class="warning";
				break;
		}
		$out='<div class="alert alert-'.$class.'" role="alert">'.
			$message.
		'</div>';
		echo $out;
	}
	# Log something - 2016-08-03 09:10
	public function log_message($level,$title,$message,$data=''){
		global $db;
		switch($level){
			case 'danger':
			case 'error':
				$level=1;
				break;
			case 'warning':
				$level=2;
				break;
			case 'info':
			case 'information':
			case 'success':
				$level=3;
				break;
		}
		if($data){
			$data=print_pre($data,true);
		}
		$db->query(
			'INSERT INTO `logs` (`level`,`user_id`,`title`,`message`,`date`,`data`) VALUES (?,?,?,?,?,?)',
			array(
				$level,
				$_SESSION['user_id']?$_SESSION['user_id']:-1,
				$title,
				$message,
				DATE_TIME,
				$data
			),0
		);
		return $db->insert_id();
	}
	# Get page title
	public function page_title(){
		if($this->page_title && strtolower($this->page_title)!='index'){
			$out=crop($this->page_title,25).' | ';
		}else{
			$page=basename($_SERVER['PHP_SELF'],'.php');
			if($page=='index' && get_dir()){
				$page='Dashboard';
			}
			$page=ucwords(str_replace(array('-','_'),' ',$page));
			$this->page_title=$page;
			$out.=crop($page,25).' | ';
		}
		echo $out.(defined('SITE_NAME')?SITE_NAME:'glowt');
	}
	# Set message for visual output
	public function set_message($type,$message){
		switch(strtolower($type)){
			case 'error':
				$this->errors[]=$message;
				break;
			case 'info':
			case 'information':
				$this->information[]=$message;
				break;
			case 'success':
				$this->success[]=$message;
				break;
			case 'warning':
				$this->warnings[]=$message;
				break;
		}
	}
	# Cashing Methods
	private function minify_css($css){
		# Strips Comments
		$css = preg_replace('!/\*.*?\*/!s','', $css);
		$css = preg_replace('/\n\s*\n/',"\n", $css);
		# Minifies
		$css = preg_replace('/[\n\r \t]/',' ', $css);
		$css = preg_replace('/ +/',' ', $css);
		$css = preg_replace('/ ?([,:;{}]) ?/','$1',$css);
		# Kill Trailing Semicolon
		$css = preg_replace('/;}/','}',$css);
		# Return Minified CSS
		return $css;
	}
}