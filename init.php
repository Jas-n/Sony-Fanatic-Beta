<?php $definitions=array(
	'site_name'=>'Sony Fanatic'
);
foreach($definitions as $key=>$definition){
	define(strtoupper($key),$definition);
}