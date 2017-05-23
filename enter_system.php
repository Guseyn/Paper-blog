<?php 
$post_array=json_decode(urldecode($_POST['to_enter_system']));
$lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
if(isset($post_array)){
	$ajax_response=array();
	unset($_POST['to_enter_system']);
	if(!isset($_COOKIE['hash'])){
			
		function remove_whitespace($string){
			$string = preg_replace('/ {2,}/',' ',$string); 
			$string = trim($string) ; 
			return $string;
		}
		
		$enterloginemail=htmlspecialchars(remove_whitespace($post_array->enterloginemail));
		$enterpword=htmlspecialchars(remove_whitespace($post_array->enterpword));
		
		$errors_input=array();
		$hash_sess_id;
		
		if(
		
		$enterloginemail===''
		||$enterpword===''
		
		){
			$ajax_response['status']=($lang==='ru')?
			'Заполните все поля':'Fill in all the fields';
			$e_count=0;
			
			if($enterloginemail===''){
				$errors_input[$e_count]='enterloginemail';
				$e_count++;
			}
			if($enterpword===''){
				$errors_input[$e_count]='enterpword';
				$e_count++;
			}
			
		}else{
			
			include('sql_lib.php');
			$s_w=new sql_work();
			
			if($NP=$s_w->connect()){
				mysqli_autocommit($NP,false);
				$enterpword=hash('sha256',$enterpword,false);
				$is_user_query='SELECT hash_sess_id FROM ussd WHERE (login=? OR email=?) AND password=? LIMIT 1;';
				if($just_array=$s_w->get_info_in_new_array(
				$NP,
				$is_user_query,
				array($enterloginemail,$enterloginemail,$enterpword),
				'sss'
				)){
					if($just_array['interval']>0){
						$hash_sess_id=$just_array[0]['hash_sess_id'];
						include('get_user_id.php');
						include('get_user_profile.php');
						if($user_id=get_user_id($NP,$s_w,$hash_sess_id)){
							if($user_id!=='not_able'){
								$ajax_response['profile']=get_user_profile($NP,$s_w,$user_id,'pabs',false);
								if($ajax_response['profile']['status']==='good'){
									$ajax_response['profile']['user']='you';
									$ajax_response['status']='good';
								}else{
									$ajax_response['status']='error#'.__LINE__.'->'.$ajax_response['profile']['status'];
							}}else{
								$ajax_response['status']='not_able';
						}}else{
							$ajax_response['status']='error#'.__LINE__.'->'.$user_id;
					}}else{
						$errors_input[0]='enterloginemail';
						$errors_input[1]='enterpword';
						$ajax_response['status']=($lang==='ru')?
						'Неверный логин(email)/пароль':'The entered login(email)/password is wrong';
				}}else{
					$ajax_response['status']='error#'.__LINE__;
				}
				
				if($ajax_response['status']==='good'){
					mysqli_commit($NP);
					setcookie('hash',$hash_sess_id, time()+2678400, '/','.paper-blog.ru', false,true);
				}else{
					mysqli_rollback($NP);
				}
				$s_w->sql_close($NP);
        }else{
        	$ajax_response['status']='error#'.__LINE__;
	}}
	if(count($errors_input)!==0){
		$ajax_response['errors_input']=$errors_input;
}}else{
	$ajax_response['status']='error#'.__LINE__;
}
echo 'for(;;);'.json_encode($ajax_response);
}
?>