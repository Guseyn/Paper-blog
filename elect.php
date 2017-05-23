<?php
include('filter_post_array.php');
$post_array=json_decode(urldecode($_POST['to_elect']));
	$ajax_response=array();
	$post_array=check_filter_input($post_array);
	session_start();
if(isset($post_array)&&$post_array){
	if(isset($_COOKIE['hash'])){
		unset($_POST['to_elect']);
		$hash_sess_id=$_COOKIE['hash'];
		include('sql_lib.php');
		$s_w=new sql_work();
		if($NP=$s_w->connect()){
			mysqli_autocommit($NP,false);
			include('get_user_id.php');
			if($user_id=get_user_id($NP,$s_w,$hash_sess_id,false,false)){
				if($user_id!=='not_able'){
					include('work_with_compressed_str.php');
					$type_str='faves';$q='fvq';$p_m='';
					if($faves_test=isset_elm_in_table($NP,$s_w,$post_array->pab_id,'pablications')){
						if($compr_str=get_compressed_str($NP,$s_w,$user_id,$type_str,'ussd')){
							if($new_compr_str=change_compressed_str($compr_str,$post_array->pab_id)){
								if($new_compr_str['act']==='added'){
									$p_m='+';
									$ajax_response['faved']=true;
								}else if($new_compr_str['act']==='deleted'){
									$p_m='-';
									$ajax_response['faved']=false;
								}
								if(update_compressed_str($NP,$s_w,$user_id,$type_str,'ussd',$new_compr_str['compr_str'],$q,$p_m)){
									$ajax_response['status']='good';
									$ajax_response['pab_id']=$post_array->pab_id;
							}else{
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