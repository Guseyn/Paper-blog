<?php
include('filter_post_array.php');
$post_array=json_decode(urldecode($_POST['to_complain']));
	$ajax_response=array();
	$post_array=check_filter_input($post_array);
if(isset($post_array)&&$post_array){
	if(isset($_COOKIE['hash'])){
		unset($_POST['to_complain']);
		$hash_sess_id=$_COOKIE['hash'];
		include('sql_lib.php');
		$s_w=new sql_work();
		if($NP=$s_w->connect()){
			mysqli_autocommit($NP,false);
			include('get_user_id.php');
			if($user_id=get_user_id($NP,$s_w,$hash_sess_id,false,false)){
				if($user_id!=='not_able'){
						
					function remove_whitespace($string){
						$string = preg_replace('/ {2,}/',' ',$string);
						$string = trim($string);
						return $string;
					}
					
					if(remove_whitespace($post_array->reason)!==''){
						include('work_with_compressed_str.php');
						$c_compr_str=get_compressed_str($NP,$s_w,$post_array->pab_id,'complains','pablications');
						if($c_compr_str===null||$c_compr_str){
							$unc_str=gzuncompress($c_compr_str);
							if($unc_str){
								$complains=json_decode($unc_str);
								if(isset($complains->$user_id)){
									$complains->$user_id=$post_array->reason;
									unset($complains->$user_id);
									$complains=gzcompress(json_encode($complains));
									if(update_compressed_str($NP,$s_w,$post_array->pab_id,'complains','pablications',$complains,'',0)){
										$ajax_response['status']='good';
									}else{
										$ajax_response['status']='error#'.__LINE__;
									}
								}else{
									$complains->$user_id=$post_array->reason;
									$complains=gzcompress(json_encode($complains));
									if(update_compressed_str($NP,$s_w,$post_array->pab_id,'complains','pablications',$complains,'cq','+')){
										$ajax_response['status']='good';
									}else{
										$ajax_response['status']='error#'.__LINE__;
									}
								}
							}else{
								$complains=array();
								$complains->$user_id=$post_array->reason;
								$complains=gzcompress(json_encode($complains));
								if(update_compressed_str($NP,$s_w,$post_array->pab_id,'complains','pablications',$complains,'cq','+')){
									$ajax_response['status']='good';
								}else{
									$ajax_response['status']='error#'.__LINE__;	
							}}}else{
								$ajax_response['status']='error#'.__LINE__;	
						}}else{
							$ajax_response['status']='none';
							$ajax_response['error_inputs']=array('reason');
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