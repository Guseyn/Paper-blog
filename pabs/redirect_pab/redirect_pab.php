<?php
$post_array=json_decode(urldecode($_POST['to_redirect_pab']));
include('filter_post_array.php');
if(isset($post_array)&&$post_array){
	unset($_POST['to_open_pab']);
	$ajax_response=array();
	include('sql_lib.php');
	$s_w=new sql_work();
	if($NP=$s_w->connect()){
		mysqli_autocommit($NP, false);
		$url=$post_array->path;
		$query='SELECT id,path FROM pablications WHERE path=? LIMIT 1;';
		if($redirect_params=$s_w->get_info_in_new_array(
		$NP,
		$query,
		array($url),
		's'
		)){
			if($redirect_params['interval']!==0){
				$ajax_response['status']='good';	
				$ajax_response['url']='http://paper-blog.ru/?pab_id='.$redirect_params[0]['id'].'&path='.$redirect_params[0]['path'].'&content_status=pabs';
			}else{
				$ajax_response['status']='no_result';
			}
		}else{
			$ajax_response['status']='error#'.__LINE__;
		}
		if($res==='good'||$res==='no_result'){
			mysqli_commit($NP);
		}else{
			mysqli_rollback($NP);
		}
	}
	echo 'for(;;);'.json_encode($ajax_response);
}
?>