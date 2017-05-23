<?php
if(isset($_COOKIE['cookie_test'])){
$post_array=json_decode(urldecode($_POST['to_profile']));
unset($_POST['to_profile']);
include('filter_post_array.php');
$post_array=check_filter_input($post_array);
if(isset($post_array) && is_object($post_array)){	
	include('sql_lib.php');
	$s_w=new sql_work();
	$ajax_response=array();
	if($NP=$s_w->connect()){
		mysqli_autocommit($NP, false);
		include('get_user_id.php');
		include('get_user_profile.php');
		$hash_sess_id=$_COOKIE['hash'];
		$content_status=$post_array->content_status;
		$need_fn=$post_array->need_fn;
		if(isset($hash_sess_id)){
			if($user_id=get_user_id($NP,$s_w,$hash_sess_id,false,false)){
				if($user_id!=='not_able'){
					$load_user_id=(($post_array->user_id)===0)?$user_id:$post_array->user_id;
					if(is_int($load_user_id)){
						if($content_status==='copies'&&$load_user_id!==$user_id)
						$content_status='pabs';
						$ajax_response['profile']=get_user_profile($NP,$s_w,$load_user_id,$content_status,$need_fn);
						if($ajax_response['profile']['status']==='good'){
							$ajax_response['status']='good';
							if($load_user_id===$user_id){
								$ajax_response['profile']['user']='you';
							}else{
								include('work_with_compressed_str.php');
								if($fans_compressed_str=get_compressed_str($NP,$s_w,$load_user_id,'fans','ussd')){
									$ajax_response['profile']['user']='not_you';
									if(is_elm_in_compressed_str($fans_compressed_str,$user_id)){
										$ajax_response['profile']['is_aut']=true;
                                    }else{
                                    	$ajax_response['profile']['is_aut']=false;
								}}else{
										$ajax_response['status']='error#'.__LINE__;
						}}}else if($ajax_response['profile']['status']==='not_able'){
								$ajax_response['status']='not_able';
						}else if($ajax_response['profile']['status']==='no_result'){
							$ajax_response['status']='good';
					}else{
						$ajax_response['status']='error#'.__LINE__;
				}}else{
					$ajax_response['status']='none';
			}}else{
				$ajax_response['status']='not_able';
				if($post_array->user_id===0)
				setcookie('hash',$hash_sess_id, time()-3600, '/','.paper-blog.ru', false,true);
		}}else{
			$ajax_response['status']='no_sess';
			setcookie('hash',$hash_sess_id, time()-3600, '/','.paper-blog.ru', false,true);
	}}else{
		$load_user_id=$post_array->user_id;
		if($load_user_id!==0){
			if($content_status==='copies')
			$content_status='pabs';
			$ajax_response['profile']=get_user_profile($NP,$s_w,$load_user_id,$content_status,$need_fn);
			if($ajax_response['profile']['status']==='good'){
					
				$ajax_response['profile']['user']='not_np_user';
				$ajax_response['status']='good';
			
			}else if($ajax_response['profile']['status']==='not_able'){
				$ajax_response['status']='not_able';
			}else if($ajax_response['profile']['status']==='no_result'){
				$ajax_response['status']='good';
			}else{
				$ajax_response['status']='error#'.__LINE__;
		}}else if($load_user_id===0){
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
} 	
}else{
	$ajax_response['status']='not_able_cookie';
}
if($ajax_response['status'])
  echo 'for(;;);'.json_encode($ajax_response);  
?>