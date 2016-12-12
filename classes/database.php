<?php # 2016-05-06 15-45
class database{
	private $user = "";
	private $pass = "";
	private $name = "";
	private $last_id;
	private $rows_updated;
	private $db;
	public function __construct(){
		if(is_file(ROOT.'classes/connect.php')){
			include(ROOT.'classes/connect.php');
			$this->host=$host;
			$this->name=$name;
			$this->user=$user;
			$this->pass=$pass;
		}else{
			echo 'No file: '.ROOT.'classes/connect.php';
		}
		$this->db=$this->con();
	}
	# Backup
	public function backup($table,$location=NULL){
		if(strtolower($table)!='all'){
			$tables=' --tables '.$table;
		}
		if($location==NULL){
			$location=ROOT.'backups';
		}else{
			$location=ROOT.$location;
		}
		if(!is_dir($location)){
			mkdir($location,0777,1);
		}
		$date=date('Y-m-d_H-i-s');
		$command='mysqldump -u '.$this->user.' -p'.$this->pass.' --databases '.$this->name.$tables.' --single-transaction > '.$location.'/'.$table.'__'.$date.'.sql';
		system($command);
		return $location.'/'.$table.'__'.$date.'.sql';
	}
	# Cleans out JavaScript and additional HTML
	private function cleanInput(&$input){
		if(!is_array($input)){
			$input=array($input);
		}
		foreach($input as $key=>$data){
			$search = array(
				'@<script[^>]*?>.*?</script>@si',   // Strip out javascript
				'@<[\/\!]*?[^<>]*?>@si',            // Strip out HTML tags
				'@<style[^>]*?>.*?</style>@siU',    // Strip style tags properly
				'@<![\s\S]*?--[ \t\n\r]*>@'         // Strip multi-line comments
			);
			$input[$key]=preg_replace($search,'',$data);
		}
		return $input;
	}
	# DB Connect
	private function con(){
		try{
			$db=new PDO('mysql:dbname='.$this->name.';host='.$this->host,$this->user,$this->pass);
			$db->query("SET NAMES UTF8");
			return $db;
		}catch(PDOException $e){
			echo '<h3>Error Connecting to Database!</h3>'.$e->getMessage();
			exit;
		}
	}
	# Stores Error
	public function error($type='PHP',$message,$file,$line,$severity=NULL,$data=NULL){
		global $app;
		$app->log_message(
			1,
			$type.' Error',
			'<strong>Error: </strong>'.$message,
			array(
				'trace'		=>$data,
				'user_history'=>$_SESSION['history']
			)
		);
	}
	# Get table columns
	public function get_columns($table){
		if($columns=$this->query("SHOW COLUMNS FROM `".$table."`")){
			foreach($columns as $column){
				$column=array_change_key_case($column);
				$return[$column['field']]=$column;
			}
			return $return;
		}
		return false;
	}
	# Get location from $location_id
	public function get_location($location_id,$as_string=false){
		if($location=$this->query("SELECT `town`,`county` FROM `locations` WHERE `id`=?",$location_id)){
			if($as_string){
				return $location[0]['town'].', '.$location[0]['county'];
			}
			return $location[0];
		}
		return false;
	}
	# Get Logs
	public function get_logs($level=NULL){
		if($level!=NULL){
			$where='WHERE `level`=?';
			$options[]=$level;
		}
		return array(
			'count'	=>$this->result_count("FROM `logs` $where"),
			'logs'	=>$this->query('
				SELECT
					`logs`.*,
					CONCAT(`users`.`first_name`," ",`users`.`last_name`) as `name`
				FROM `logs`
				LEFT JOIN `users`
				ON `logs`.`user_id`=`users`.`id`
				'.$where.'
				ORDER BY `id` DESC
				LIMIT '.($_GET['page']?(($_GET['page']-1)*ITEMS_PER_PAGE):0).','.ITEMS_PER_PAGE,
				$options
			)
		);
	}
	# Get row (LIMIT is automatically appended to $sql)
	public function get_row($sql,$values=NULL,$clense=true,$echo=NULL){
		if($result=$this->query($sql.' LIMIT 1',$values,$clense,$echo)){
			return $result[0];
		}
	}
	# Get Value
	public function get_value($sql,$values=NULL,$clense=true,$echo=NULL){
		if($result=$this->get_row($sql,$values,$clense,$echo)){
			return $result[key($result)];
		}
	}
	# Get last insert id
	public function insert_id(){
		return $this->last_id;
	}
	public function login(){
		return false;
	}
	# Next ID
	public function next_id($table){
		return $this->get_value(
			"SELECT AUTO_INCREMENT
			FROM `information_schema`.`tables`
			WHERE
				`table_name`=? AND
				`table_schema`=?",
			array(
				$table,
				$this->name
			)
		);
	}
	public function next_hex_id($table,$column='id'){
		$cols=array_keys($this->get_columns($table));
		if(
			!$cols ||
			!in_array($column,$cols)
		){
			return false;
		}	$hex=str_pad(base_convert(mt_rand(0,1048576),10,32),4,0,STR_PAD_LEFT);
		while($this->get_value("SELECT `".$column."` FROM `".$table."` WHERE `".$column."`=?",$hex)){
			$hex=str_pad(base_convert(mt_rand(0,1048576),10,32),4,0,STR_PAD_LEFT);
		}
		return strtoupper($hex);
	}
	# Perform Query
	public function query($sql,$values=NULL,$clense=true,$echo=NULL){
		$this->last_id=0;
		$this->rows_updated=0;
		$stmt=$this->db->prepare($sql);
		if(!$stmt){
			$e=$this->db->errorInfo();
			$this->error('SQL',$e[2],__FILE__,__LINE__,NULL,array('sql'=>$sql,'values'=>$values));
			echo "<strong>SQL ERROR</strong>: ".$e[2]."<br>";
			$echo=1;
		}else{
			if($clense){
				$this->cleanInput($values);
			}
			if(!is_array($values)){
				$values=array($values);
			}
			if($values){
				$values=array_values($values);
			}
			if(!$stmt->execute($values)){
				echo '<strong>PDO Excecute ERROR:</strong><br>';
				$error=1;
			}
			$status=$stmt->errorInfo();
			if($status[0]>0){
				$this->error('PDO',$status[2],__FILE__,__LINE__,NULL,array('sql'=>$sql,'values'=>$values));
				echo "<strong>SQL ERROR: <em>{$status[2]}</em></strong><br>";
				$error=1;
			}
			$this->last_id=$this->db->lastInsertId();
			$this->rows_updated=$stmt->rowCount();
			if($echo || $error){
				$sqle=$sql;
				if(strpos($sqle,'?')){
					for($i=0;$i<sizeof($values);$i++){
						$qm=strpos($sqle,'?',$i);
						$sqle=substr($sqle,0,$qm).'\''.$values[$i].'\''.substr($sqle,$qm+1);
					}
				}else{
					$sqle=$sql;
				}
				echo '<strong>Original SQL:</strong> '.$sql.'<br>
				<strong>Formed SQL:</strong> '.$sqle.'<br>';
				if($error){
					print_pre(debug());
				}
			}
			return $stmt->fetchAll(PDO::FETCH_ASSOC);
		}
	}
	#Restore
	public function restore($files){
		foreach((array) $files as $file){
			$this->query(file_get_contents($file));
		}
	}
	# Result Count
	public function result_count($from_where_sql,$values=NULL,$echo=false){
		if(!is_array($values)){
			$values=array($values);
		}
		$out=$this->query("SELECT COUNT(*) as `size` $from_where_sql",$values,0,$echo);
		return $out[0]['size'];
	}
	# Get rows affected by last query
	public function rows_updated(){
		return $this->rows_updated;
	}
	# Returns the setting value
	public function setting($name){
		$options[]=$name;
		$out=$this->query("SELECT `value` FROM `settings` WHERE `name`=? $sql",$options);
		return $out[0]['value'];
	}
}