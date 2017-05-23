<?php
include('filter_post_array.php');

$post_array=json_decode(urldecode($_POST['to_add_to_auts']));
$post_array=check_filter_input($post_array);

if(isset($post_array)){
	
	unset($_POST['to_add_to_auts']);
	$ajax_response=array();
	
	$hash_sess_id=$_COOKIE['hash'];
	if(isset($_COOKIE['hash'])){
		include('sql_lib.php');
		$s_w=new sql_work();
		if($NP=$s_w->connect()){
			
			mysqli_autocommit($NP,false);
			include('get_user_id.php');
			
			if($user_id=get_user_id($NP,$s_w,$hash_sess_id,false,false)){
				if($user_id!=='not_able'){
					
					include('work_with_compressed_str.php');
					
					$test=isset_elm_in_table($NP,$s_w,$post_array->aut_id,'ussd');
					if($test&&!empty($test)&&is_int($post_array->aut_id)){
						if(($my_auts_compressed_str=get_compressed_str($NP,$s_w,$user_id,'auts','ussd'))
						&&($fans_compressed_str=get_compressed_str($NP,$s_w,$post_array->aut_id,'fans','ussd'))){
							$new_my_auts_compressed_str=change_compressed_str($my_auts_compressed_str,$post_array->aut_id);
							$new_fans_compressed_str=change_compressed_str($fans_compressed_str,$user_id);
							
							if(($new_my_auts_compressed_str['act']==='added')&&($new_fans_compressed_str['act']==='added')){
								if(update_compressed_str($NP,$s_w,$user_id,'auts','ussd',$new_my_auts_compressed_str['compr_str'],'aq','+')){
									if(update_compressed_str($NP,$s_w,$post_array->aut_id,'fans','ussd',$new_fans_compressed_str['compr_str'],'fq','+')){
										$ajax_response['added']=true;
										$ajax_response['status']='good';
                                    }else{
                                    	$ajax_response['status']='error#'.__LINE__;
								}}else{
									$ajax_response['status']='error#'.__LINE__;
							}}else if(($new_my_auts_compressed_str['act']==='deleted')&&($new_fans_compressed_str['act']==='deleted')){
								if(update_compressed_str($NP,$s_w,$user_id,'auts','ussd',$new_my_auts_compressed_str['compr_str'],'aq','-')){
									if(update_compressed_str($NP,$s_w,$post_array->aut_id,'fans','ussd',$new_fans_compressed_str['compr_str'],'fq','-')){
										$ajax_response['added']=false;
										$ajax_response['status']='good';
								}else{
									$ajax_response['status']='error#'.__LINE__;	
							}}else{
								$ajax_response['status']='error#'.__LINE__;
						}}else{
							$ajax_response['status']='error#'.__LINE__;
					}}else{
						$ajax_response['status']='error#'.__LINE__;
				}}else{
					$ajax_response['status']='error#'.__LINE__;
			}}else{
				$ajax_response['status']='not_able';
		}}else{
			$ajax_response['status']='no_sess';
			setcookie('hash',$hash_sess_id, time()-3600, '/','.paper-blog.ru', false,true);
		}
		if($ajax_response['status']==='good'){
			mysqli_commit($NP);
		}else{
			mysqli_rollback($NP);
		}
		$s_w->sql_close($NP);
     }else{
       $ajax_response['status']='error#'.__LINE__;
}}else{
		$ajax_response['status']='no_sess';
}
echo 'for(;;);'.json_encode($ajax_response);	
}
?>