<?php
include('filter_post_array.php');
$lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
$post_array=json_decode(urldecode($_POST['to_work_with_wysiwyg']));
$post_array=check_filter_input($post_array);
if(isset($post_array)&&$post_array){
	$hash_sess_id=$_COOKIE['hash'];
	$ajax_response=array();
	unset($_POST['to_work_with_wysiwyg']);
	if(isset($_COOKIE['hash'])){
		$block=false;
		include('sql_lib.php');
		include('get_user_id.php');
		$s_w=new sql_work();
		if($NP=$s_w->connect()){
			if(!$block){
				if($user_id=get_user_id($NP,$s_w,$hash_sess_id,false,false)){
					if($user_id!=='not_able'){
						$ajax_response['wysiwyg']=($lang==='ru')?
						file_get_contents('wysiwyg_ru.php'):
						file_get_contents('wysiwyg_en.php');
						$ajax_response['wysiwyg_modals']=($lang==='ru')?
						file_get_contents('wysiwyg_modals_ru.php'):
						file_get_contents('wysiwyg_modals_en.php');
						$ajax_response['status']='good';
					}else{
						$ajax_response['status']='no_able';
				}}else{
					$ajax_response['status']='no_sess';
					setcookie('hash',$hash_sess_id, time()-3600, '/','.paper-blog.ru', false,true);
			}}else{
				$ajax_response['status']='error#'.__LINE__;
		}}else{
			$ajax_response['status']='error#'.__LINE__;
	}}else{
		$ajax_response['wysiwyg']=($lang==='ru')?
		file_get_contents('wysiwyg_ru.php'):
		file_get_contents('wysiwyg_en.php');
		$ajax_response['wysiwyg_modals']=($lang==='ru')?
		file_get_contents('wysiwyg_modals_ru.php'):
		file_get_contents('wysiwyg_modals_en.php');
		$ajax_response['status']='good';
	}
echo 'for(;;);'.json_encode($ajax_response);
}
?>