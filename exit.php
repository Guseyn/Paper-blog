<?php 
include('filter_post_array.php');
$post_array=json_decode(urldecode($_POST['to_exit']));
$post_array=check_filter_input($post_array);
if(isset($post_array)){
	$ajax_response=array();
	$hash_sess_id=$_COOKIE['hash'];
	unset($_POST['to_exit']);
	if(isset($_COOKIE['hash'])){
		include('sql_lib.php');
		include('get_user_id.php');
		$s_w=new sql_work();
		if($NP=$s_w->connect()){
			mysqli_autocommit($NP,false);
			if($user_id=get_user_id($NP,$s_w,$hash_sess_id,false,true)){
				$ajax_response['user_id']=$user_id;	
				$ajax_response['status']='good';
			}else{
				$ajax_response['status']='error#'.__LINE__;
			}
			if($ajax_response['status']==='good'){
				mysqli_commit($NP);
				setcookie('hash',$hash_sess_id, time()-3600, '/','.paper-blog.ru', false,true);
			}else{
				mysqli_rollback($NP);
			}
			$s_w->sql_close($NP);
		}else{
			$ajax_response['status']='error#'.__LINE__;
	}}else{
		$ajax_response['status']='no_sess';
		setcookie('hash',$hash_sess_id, time()-3600, '/','.paper-blog.ru', false,true);
}
echo 'for(;;);'.json_encode($ajax_response); 
}
?>