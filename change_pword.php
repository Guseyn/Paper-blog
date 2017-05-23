<?php 
$post_array=json_decode(urldecode($_POST['to_change_fpword']));
$lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
if(isset($post_array)){
	session_start();
	$ajax_response=array();
	unset($_POST['to_change_fpword']);
	if(!isset($_COOKIE['hash'])){
			
		function remove_whitespace($string){
			$string = preg_replace('/ {2,}/',' ',$string) ; 
			$string = trim($string) ; 
			return $string;
		}
		
		$change_fpw_input=htmlspecialchars(remove_whitespace($post_array->change_fpw_input));
		$change_fpword=htmlspecialchars(remove_whitespace($post_array->change_fpword));
		$change_fpword2=htmlspecialchars(remove_whitespace($post_array->change_fpword2));
		$pword_reg='/^([a-zа-яё0-9]){8,}$/iu';
		$errors_input=array();
		
		if($change_fpw_input===''||$change_fpword===''||$change_fpword2===''){
			$ajax_response['status']=($lang==='ru')?'Заполните все поля':'Fill in all the fields';
			$e_count=0;
			if($change_fpw_input===''){$errors_input[$e_count]='change_fpw_input';$e_count++;}
			if($change_fpword===''){$errors_input[$e_count]='change_fpword';$e_count++;}
			if($change_fpword2===''){$errors_input[$e_count]='change_fpword2';$e_count++;}
		}else if(!preg_match($pword_reg,$change_fpword)){
			$ajax_response['status']=($lang==='ru')?
			'Недопустимые символы в пароле(aA-zZ,аА-яЯ,0-9,_-.), длина не менее 8 символов':
			'Invalid characters in the password (aA-zZ, AA Yaya, 0-9, _-.), Length of at least 8 characters';
			$errors_input[0]='change_fpword';
		}else if($change_fpword!==$change_fpword2){
			$ajax_response['status']=($lang==='ru')?
			'Введённые пароли не совпадают':
			'The entered passwords do not match';
			$errors_input[0]='change_fpword';
			$errors_input[1]='chagne_fpword2';
		}else{
			include('sql_lib.php');
			if(isset($_SESSION['code'],$_SESSION['ec'])){
				$email=base64_decode($_SESSION['email_for_code']);
				$md5=md5($email);
				if((($change_fpw_input+123456789)*2===$_SESSION['code'])&&($md5.$_SESSION['code'].$md5===$_SESSION['ec'])){
					$s_w=new sql_work();
					if($NP=$s_w->connect()){
						mysqli_autocommit($NP, false);
						$is_able_email_query='SELECT id FROM ussd USE INDEX (email_login) WHERE email=?  LIMIT 1';
						if($just_array=$s_w->get_info_in_new_array(
						$NP,
						$is_able_email_query,
						array($email),
						's'
						)){
							if($just_array['interval']>0){
								$query='UPDATE ussd SET password=? WHERE email=? LIMIT 1';
								$params=array(hash('sha256',$change_fpword,false),$email);
								$types='ss';
								if($s_w->manipulation($NP,$query,$params,$types)){
									$ajax_response['status']='good';
								}else{
									$ajax_response['status']='error#'.__LINE__;
							}}else{
								$ajax_response['status']=($lang==='ru')?
								'Неверный код->error':
								'The entered code is wrong';
						}}else{
							$ajax_response['status']='error#'.__LINE__;
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
                    	$ajax_response['status']=($lang==='ru')?'Неверный код':'The entered code is wrong';
               }}else{
               	$ajax_response['status']=($lang==='ru')?
               	'Действие кода прекращено.<br>Попробуйте снова получить код <br> доступа для осстановления пароля':
				'Try again to get access code. <br>Time of the entered code has passed';
			}}
			if(count($errors_input)!==0){
					$ajax_response['errors_input']=$errors_input;
		}}else{
			$ajax_response['status']='error#'.__LINE__;
		}
		echo 'for(;;);'.json_encode($ajax_response);
}
?>