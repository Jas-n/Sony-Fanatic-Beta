<?php # Transfers errors from log files to log
# Updated 16/12/2016 09:24
$error_files=0;
function scan_errors($dir,$level=0){
	global $db,$error_files;
	if(is_dir($dir)){
		foreach(scandir($dir) as $file){
			if(!in_array($file,array('.','..'))){
				if(is_file($dir.$file) && strpos($dir.$file,'error_log')!==false){
					$handle = fopen($dir.$file, "r");
					$lines=0;
					while(!feof($handle)){
						$line=trim(fgets($handle));
						if($line && strpos($line,'/init.php on line 2')===false){
							if(strpos($line,'[')===0){
								$date=date('Y-m-d H:i:s',strtotime(substr($line,1,strpos($line,']')-1)));
								$line=substr($line,strpos($line,']')+2);
							}
							$db->query(
								"INSERT INTO `logs` (
									`user_id`,`level`,`title`,`message`,`data`,`date`
								) VALUES (?,?,?,?,?,?)",
								array(
									-1,
									1,
									'Error Log',
									$dir.$file,
									trim($line),
									$date
								)
							);
						}
						$lines++;
					}
					if($lines>0){
						$error_files++;
					}
					fclose($handle);
					unlink($dir.$file);
				}elseif(is_dir($dir.$file)){
					scan_errors($dir.$file.'/',$level++);
				}
			}
		}
	}
}