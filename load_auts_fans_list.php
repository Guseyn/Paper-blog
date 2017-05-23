<?php
include('filter_post_array.php');
$post_array=json_decode(urldecode($_POST['to_load_auts_fans_list']));
$post_array=check_filter_input($post_array);
if(isset($post_array)&&$post_array){
	unset($_POST['to_load_auts_fans_list']);
	$ajax_response=array();
	$hash_sess_id=$_COOKIE['hash'];
	include('sql_lib.php');
	$s_w=new sql_work();
	if($NP=$s_w->connect()){
		mysqli_autocommit($NP, false);
		include('get_user_id.php');
		include('work_with_compressed_str.php');
		include('get_auts_fans_list.php');
		if(isset($_COOKIE['hash'])){
			if($user_id=get_user_id($NP,$s_w,$hash_sess_id,false,false)){
				if($user_id!=='not_able'){

					$type_of_rects=htmlspecialchars($post_array->type_of_rects);
					$next_load_step=$post_array->next_load_step;
					
					$load_user_id=(($post_array->user_id)===0)?$user_id:$post_array->user_id;
					
					if(isset($type_of_rects,$next_load_step)&&is_int($next_load_step)&&is_int($user_id)){
						if(($list_compressed_str=get_compressed_str($NP,$s_w,$load_user_id,$type_of_rects,'ussd'))){
							$list_data=get_auts_fans_list($NP,$s_w,$type_of_rects,$load_user_id,$list_compressed_str,$next_load_step);
							if(($list_data['status']==='good')){
								$ajax_response['list_data']=$list_data;
								$ajax_response['status']='good';
							}else{
								$ajax_response['list_data']['status']='error#'.__LINE__.'->'.$list_data['status'];
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
			}}else{
				$user_id=($post_array->user_id)*1;
				$type_of_rects=htmlspecialchars($post_array->type_of_rects);
				$next_load_step=$post_array->next_load_step;
				
				if(isset($type_of_rects,$next_load_step)&&is_int($next_load_step)&&is_int($user_id)&&($user_id!==0)){
					if(($list_compressed_str=get_compressed_str($NP,$s_w,$user_id,$type_of_rects,'ussd'))){
						$list_data=get_auts_fans_list($NP,$s_w,$type_of_rects,$user_id,$list_compressed_str,$next_load_step);
						if(($list_data['status']==='good')){
							$ajax_response['list_data']=$list_data;
							$ajax_response['list_data']['user']='no_np_status';
							$ajax_response['status']='good';
						}else{
							$ajax_response['list_data']['status']='error#'.__LINE__.'->'.$list_data['status'];
							$ajax_response['status']='error#'.__LINE__;
					}}else{
						$ajax_response['status']='error#'.__LINE__;
				}}else{
					$ajax_response['status']='no_sess';
				}
			}
			if($ajax_response['status']==='good'){
				mysqli_commit($NP);
			}else{
				mysqli_rollback($NP);
			}
			$s_w->sql_close($NP);
}else{
   $ajax_response['status']='error#'.__LINE__;
}
echo 'for(;;);'.json_encode($ajax_response);
}
?>