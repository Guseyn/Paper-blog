<?php
include('filter_post_array.php');
$post_array=json_decode(urldecode($_POST['to_remark']));
	$ajax_response=array();
	$post_array=check_filter_input($post_array);
if(isset($post_array)&&$post_array){
	if(isset($_COOKIE['hash'])){
		unset($_POST['to_remark']);
		$hash_sess_id=$_COOKIE['hash'];
		include('sql_lib.php');
		$s_w=new sql_work();
		if($NP=$s_w->connect()){
			mysqli_autocommit($NP,false);
			include('get_user_id.php');
			if($user_id=get_user_id($NP,$s_w,$hash_sess_id,false,false)){
				if($user_id!=='not_able'){
					include('work_with_compressed_str.php');
					if($corrects_str=get_compressed_str($NP,$s_w,$post_array->pab_id,'corrects','pablications')){
						$corrects=json_decode(gzuncompress($corrects_str));
						if(is_null($corrects)||!$corrects)$corrects=json_decode('{}');
						if($post_array->act==='write'){
								
							function remove_whitespace($string){
						    	$string = preg_replace('/ {2,}/',' ',$string);
						    	$string = trim($string);
								return $string;
							}
							
							$mark=remove_whitespace($post_array->mark);
							
							if($mark!==''){
								$corrects->$user_id=$post_array->mark;
								$corrects=gzcompress(json_encode($corrects));
								if(update_compressed_str($NP,$s_w,$post_array->pab_id,'corrects','pablications',$corrects,'',0)){
									$ajax_response['status']='good';
								}
							}else{
								$ajax_response['errors_input']=array('wrcr_text');
								status:'fail';
							}
														
						}else if($post_array->act==='open'){
							$ajax_response['remark']=$corrects->$user_id;
							$ajax_response['pab_id']=$post_array->pab_id;
							$ajax_response['status']='good';
						}else if($post_array->act==='delete_remark'){
							unset($corrects->$user_id);
							$corrects=gzcompress(json_encode($corrects));
							if(update_compressed_str($NP,$s_w,$post_array->pab_id,'corrects','pablications',$corrects,'',0)){
								$ajax_response['status']='good';
							}else{
								$ajax_response['status']='error#'.__LINE__;
							}
						}else if($post_array->act==='read'){
							$i=0;
							foreach($corrects as $key=>$value){
								$ajax_response['corrects'][$i]=$value;$i++;
							}
							if($i===0)$ajax_response['corrects']=false;
							$ajax_response['pab_id']=$post_array->pab_id;
							$ajax_response['status']='good';
						}else if($post_array->act='clear_all'){
							$corrects=gzcompress('{}');
							if(update_compressed_str($NP,$s_w,$post_array->pab_id,'corrects','pablications',$corrects,'',0)){
								$ajax_response['status']='good';
							}else{
								$ajax_response['status']='error#'.__LINE__;
							}
						}else{
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