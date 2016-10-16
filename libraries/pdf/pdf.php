<?php # Version 2
define('FPDF_FONTPATH',ROOT.'libraries/pdf/font/');
include(ROOT.'libraries/pdf/fpdf.php');
include(ROOT.'libraries/pdf/fpdi.php');
class pdf extends FPDI{
	private $list;
	private $list_id;
	public $lineheight=4;
	public function __construct($orientation='P',$unit='mm',$size='A4'){
		parent::__construct($orientation,$unit,$size);
		$this->AddPage($orientation,$size='A4');
		$this->SetFont('Helvetica','',14);
		$this->SetCompression(true);
	}
	# HTML
		public $tab=0;
		public function html($html){
			if($html){
				# Convert from XHTML to HTML
				$str=array(
					'<br />'	=>"\r\n",
					'<br/>'		=>"\r\n",
					'<hr />'	=>'<hr>',
					'<hr/>'		=>'<hr>',
					'&#8220;'	=>'"',
					'&#8221;'	=>'"',
					'&#8222;'	=>'"',
					'&#8230;'	=>'...',
					'&#8217;'	=>'\'',
					'&trade;'	=>'™',
					'&copy;'	=>'©',
					'&nbsp;'	=>' ',
					'&euro;'	=>'€'
				);
				$html=str_replace(array_keys($str),$str,$html);
				#echo htmlentities($html);
				$doc=new DOMDocument();
				$doc->loadHTML($html);
				$doc=$doc->getElementsByTagName('body');
				$this->FontStyle='';
				$this->html_tag($doc->item(0)->childNodes);
			}
		}
		private function html_tag($tags){
			foreach($tags as $tag){
				if($tag->nodeType==1){
					# Tag
					#echo $tag->tagName.'<br>';
					switch($tag->tagName){
						case 'a':
							$old_colour=$this->TextColor;
							$colour=hex2rgb(COLOUR);
							$this->SetTextColor($colour['r'],$colour['g'],$colour['b']);
							$style=$this->FontStyle;
							$this->SetFont('','U');
							$this->Write($this->lineheight,$tag->nodeValue,$tag->getAttribute('href'));
							$this->SetTextColor($old_colour);
							$this->SetFont('',$style);
							break;
						case 'b':
						case 'strong':
							$style=$this->FontStyle;
							$this->SetFont('','B');
							$this->html_tag($tag->childNodes);
							$this->SetFont('',$style);
							break;
						case 'br':
							break;
						case 'code':
							break;
						case 'em':
							$style=$this->FontStyle;
							$this->SetFont('','I');
							$this->html_tag($tag->childNodes);
							$this->SetFont('',$style);
							break;
						case 'h3':
							$style=$this->FontStyle;
							$this->SetFont('','B');
							$this->html_tag($tag->childNodes);
							$this->SetFont('',$style);
							$this->Ln($this->lineheight*2);
							break;
						case 'li':
							$this->Ln();
							$list_type=key($this->list[$this->list_id]);
							$this->Cell($this->lMargin,$this->lineheight,$list_type=='ol'?$this->list[$this->list_id][$list_type]++:chr(127),0,0);
							$this->lMargin+=15;
							$this->html_tag($tag->childNodes);
							$this->lMargin-=15;
							break;
						case 'ol':
						case 'ul':
							$list_id=microtime(true);
							$this->list[$list_id][$tag->tagName]=1;
							$this->list_id=$list_id;
							$this->html_tag($tag->childNodes);
							$this->Ln();
							break;
						case 'p':
							$this->html_tag($tag->childNodes);
							$this->Ln($this->lineheight);
							break;
						case 'var':
							$style=$this->FontStyle;
							$this->SetFont('','BI');
							$this->html_tag($tag->childNodes);
							$this->SetFont('',$style);
							break;
						default:
							echo $tag->tagName.'<br>';
							print_pre($tag);
							echo '<hr>';
					}
				}elseif($tag->nodeType==3){
					# Text
					$this->Write($this->lineheight,$tag->nodeValue);
				}
			}
		}
	# Table
		public $widths;
		public $aligns;
		function SetWidths($w){
			//Set the array of column widths
			$this->widths=$w;
		}
		function SetAligns($a){
			//Set the array of column alignments
			$this->aligns=$a;
		}
		function Row($data,$fill=false){
			//Calculate the height of the row
			$nb=0;
			for($i=0;$i<count($data);$i++){
				$nb=max($nb,$this->NbLines($this->widths[$i],$data[$i]));
			}
			$h=5*$nb;
			//Issue a page break first if needed
			$this->CheckPageBreak($h);
			//Draw the cells of the row
			for($i=0;$i<count($data);$i++){
				$w=$this->widths[$i];
				$a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
				//Save the current position
				$x=$this->GetX();
				$y=$this->GetY();
				//Draw the border
				$this->Rect($x,$y,$w,$h);
				//Print the text
				$this->MultiCell($w,5,$data[$i],0,$a,$fill);
				//Put the position to the right of the cell
				$this->SetXY($x+$w,$y);
			}
			//Go to the next line
			$this->Ln($h);
		}
		function CheckPageBreak($h){
			//If the height h would cause an overflow, add a new page immediately
			if($this->GetY()+$h>$this->PageBreakTrigger){
				$this->AddPage($this->CurOrientation);
			}
		}
		function NbLines($w,$txt){
			//Computes the number of lines a MultiCell of width w will take
			$cw=&$this->CurrentFont['cw'];
			if($w==0){
				$w=$this->w-$this->rMargin-$this->x;
			}
			$wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
			$s=str_replace("\r",'',$txt);
			$nb=strlen($s);
			if($nb>0 and $s[$nb-1]=="\n"){
				$nb--;
			}
			$sep=-1;
			$i=0;
			$j=0;
			$l=0;
			$nl=1;
			while($i<$nb){
				$c=$s[$i];
				if($c=="\n"){
					$i++;
					$sep=-1;
					$j=$i;
					$l=0;
					$nl++;
					continue;
				}
				if($c==' '){
					$sep=$i;
				}
				$l+=$cw[$c];
				if($l>$wmax){
					if($sep==-1){
						if($i==$j){
							$i++;
						}
					}else{
						$i=$sep+1;
					}
					$sep=-1;
					$j=$i;
					$l=0;
					$nl++;
				}else{
					$i++;
				}
			}
			return $nl;
		}
	# Helpers
		# Pixels to mm
		public function px2mm($px){
			return $px*25.4/72;
		}
		public function centreImage($img,$y=0,$max_width=210,$max_height=100){
			if($max_width==210){
				$max_width=$max_width-$this->lMargin-$this->rMargin;
			}
			list($width, $height) = getimagesize($img);
			$width	=$this->px2mm($width);
			$height	=$this->px2mm($height);
			if($height>$max_height){
				$ratio	=$max_height/$height;
				$height	=$height*$ratio;
				$width	=$width*$ratio;
			}
			if($width>$max_width){
				$ratio	=$max_width/$width;
				$width	=$width*$ratio;
				$height	=$height*$ratio;
			}
			
			$padding=(210-$max_width)/2;
			$this->Image($img,$padding,$y,$width,$height);
			
			return $this->getY()+$height;
			
			
			// Build 127
			if($max_width==210){
				$max_width=$max_width-$this->lMargin-$this->rMargin;
			}
			list($width, $height) = getimagesize($img);
			$width	=$this->px2mm($width);
			$height	=$this->px2mm($height);
			if($width>$max_width){
				$ratio	=$max_width/$width;
				$width	=$width*$ratio;
				$height	=$height*$ratio;
			}
			$padding=(210-$max_width)/2;
			$this->Image($img,$padding,$y,$width);
			return $this->getY()+$height;
		}
}