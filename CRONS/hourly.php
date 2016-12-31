<?php if(isset($_SERVER['REQUEST_METHOD'])){
	header('location: /');
	exit;
}
set_time_limit(0);
ini_set('memory_limit',-1);
$app_require=array('lib.zip');
require('../init.php');
$month=date('m');
$day=date('d');
$hour=date('G');
# Every 6 Hours
if(in_array($hour,array(0,6,12,18))){

	$folding=json_decode(file_get_contents('http://folding.stanford.edu/stats/api/team/88889'),1);
	if(!$data['error']){
		$data=array(
			'active'=>$folding['active_50'],
			'credit'=>$folding['credit'],
			'last'	=>$folding['last'],
			'rank'	=>$folding['rank'],
			'team'	=>$folding['team'],
			'teams'	=>$folding['total_teams'],
			'wus'	=>$folding['wus']
		);
		file_put_contents(ROOT.'folding.json',json_encode($data));
	}
	mkdir(ROOT.'backups/quarter_day/'.date('Y-m-d').'/'.date('H'),0777,1);
	$db->backup('all','backups/quarter_day/'.date('Y-m-d').'/'.date('H'));
	$backup = new zip(ROOT.'/backups/quarter_day/'.date('Y-m-d').'/'.date('H').'/files.zip');
	foreach(scandir('../') as $dir){
		if(!in_array($dir,array('.','..','backups'))){
			$backup->add($dir,PCLZIP_OPT_REMOVE_PATH,'../');
		}
	}
	$cron_messages[]="Backed up database and files.";
}
# Hour specific
switch($hour){
	case 0:
		# Clear old backups
		foreach(scandir(ROOT.'backups/quarter_day/') as $backup){
			if(!in_array($backup,array('.','..','index.php'))){
				if(strtotime($backup)<strtotime(DATE)){
					rrmdir(ROOT.'backups/quarter_day/'.$backup);
				}
			}
		}
		# Clear logs
		$db->query("DELETE FROM `logs` WHERE `date` < ?",date('Y-m-d H:i:s',strtotime("-".LOGS_AGE." days")));
		if($db->rows_updated()){
			$cron_messages[]="Deleted ".$db->rows_updated()." logs that are older than ".LOGS_AGE." days.";
		}
		# Delete Viewed notifications
		if($nofies=$db->query("SELECT `id` FROM `notifications` WHERE `dismissable`=1")){
			$deletes=array();
			foreach($nofies as $notify){
				if($notify_users=$db->query("SELECT * FROM `notification_users` WHERE `notification_id`=?",$notify['id'])){
					foreach($notify_users as $notify_user){
						if($notify_user['viewed']=='0000-00-00 00:00:00'){
							continue;
						}else{
							if(!$last_viewed || $notify_user['viewed']>$last_viewed){
								$last_viewed=$notify_user['viewed'];
							}
						}
					}
					if(strtotime($last_viewed)<strtotime(DATE_TIME.' -'.LOGS_AGE.' days')){
						$deletes[]=$notify['id'];
					}
				}else{
					$deletes[]=$notify['id'];
				}
			}
			if($deletes){
				$db->query("DELETE FROM `notifications` WHERE `id` IN(".implode(',',$deletes).")");
				$db->query("DELETE FROM `notification_users` WHERE `notification_id` IN(".implode(',',$deletes).")");
			}
		}
		$db->query("DELETE FROM `notifications` WHERE `end_date` <= ?",date('Y-m-d',strtotime(DATE_TIME.' -'.LOGS_AGE.' days')));
		# Once a month
		if($day==1){
			# Keep log IDs low
			if($logs=$db->query("SELECT `id` FROM `logs`")){
				foreach($logs as $i=>$log){
					$db->query("UPDATE `logs` SET `id`=? WHERE `id`=?",array($i+1,$log['id']));
				}
			}
			# Keep log IDs low
			if($logs=$db->query("SELECT `id` FROM `notification_users`")){
				foreach($logs as $i=>$log){
					$db->query("UPDATE `notification_users` SET `id`=? WHERE `id`=?",array($i+1,$log['id']));
				}
			}
		}
		break;
	case '16':
		# Daily emails
		if($noties=$db->query(
			"SELECT
				`users`.`email`,
				`notifications`.`title`, `notifications`.`message`, `notifications`.`added`, `notifications`.`type`
			FROM `notification_users`
			INNER JOIN `notifications`
			ON `notification_users`.`notification_id`=`notifications`.`id`
			INNER JOIN `users`
			ON `notification_users`.`user_id`=`users`.`id`
			WHERE
				`notifications`.`added`>? AND
				`users`.`id`<>0
			GROUP BY `notification_users`.`id`",
			array(
				date('Y-m-d H:i:s',strtotime('-24.25 hours'))
			)
		)){
			foreach($noties as $noty){
				$emails[$noty['email']]['notifications'][]=$noty;
			}
		}
		if($emails){
			foreach($emails as $email=>$data){
				$content='';
				if($data['notifications']){
					$content.='<h3>New Notifications</h3>';
					foreach($data['notifications'] as $notification){
						$content.='<p><strong>'.$notification['title'].'</strong> '.$notification['message'].'<br><strong>Added</strong> '.sql_datetime($notification['added']).'</p>';
					}
				}
				email($email,SITE_NAME.' Daily Update','Your daily update from '.SITE_NAME,$content);
			}
		}
		break;
}
# Once a month
if($hour==0 && $day==1){
	# Keep log IDs low
	if($logs=$db->query("SELECT `id` FROM `logs`")){
		foreach($logs as $i=>$log){
			$db->query("UPDATE `logs` SET `id`=? WHERE `id`=?",array($i+1,$log['id']));
		}
	}
	# Keep notification_user IDs low
	if($notification_users=$db->query("SELECT `id` FROM `notification_users`")){
		foreach($notification_users as $i=>$notification_user){
			$db->query("UPDATE `notification_users` SET `id`=? WHERE `id`=?",array($i+1,$notification_user['id']));
		}
	}
	# Keep user_role IDs low
	if($user_roles=$db->query("SELECT `id` FROM `user_roles`")){
		foreach($user_roles as $i=>$user_role){
			$db->query("UPDATE `user_roles` SET `id`=? WHERE `id`=?",array($i+1,$user_role['id']));
		}
	}
	$cron_messages[]="Tidied up disposable database items.";
}
# Log the cron run
if($cron_messages){
	$app->log_message(3,$hour.':00 Update',implode("<br>",$cron_messages));
}