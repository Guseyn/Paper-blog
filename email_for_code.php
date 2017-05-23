<?php 
$post_array=json_decode(urldecode($_POST['to_email_for_code']));
$lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
if(isset($post_array)){
	session_start();
	$ajax_response=array();
	unset($_POST['to_email_for_code']);
	if(!isset($_COOKIE['hash'])){
			
		function remove_whitespace($string){
			$string = preg_replace('/ {2,}/',' ',$string) ; 
			$string = trim($string) ; 
			return $string;
		}
		
		$email_for_code=strip_tags(remove_whitespace($post_array->email_for_code_input));
		$to_reg=is_int($post_array->to_reg*1)?$post_array->to_reg*1:null;
		$email_reg='/^([a-z0-9_\.-]+)@([a-z0-9_\.-]+)\.([a-z\.]{2,6})$/iu';
		$errors_input=array();
		if($email_for_code===''){
			$ajax_response['status']=($lang==='ru')?
			'Заполните все поля':'Fill in all the fields';
			$errors_input[0]='email_for_code_input';
		}else if(!preg_match($email_reg, $email_for_code)){
			$ajax_response['status']=($lang==='ru')?'Неверный формат email':'The entered email has wrong format';
			$errors_input[0]='email_for_code_input';
		}else{
			include('sql_lib.php');
			$s_w=new sql_work();
			if($NP=$s_w->connect()){
				$is_able_email_query='SELECT id FROM ussd USE INDEX (email_login) WHERE email=?  LIMIT 1';
				if($just_array=$s_w->get_info_in_new_array(
				$NP,
				$is_able_email_query,
				array($email_for_code),
				's'
				)){
					if($to_reg===1){
						if($just_array['interval']>0){
							$ajax_response['status']=($lang==='ru')?
							'Пользователь с таким email уже зарегистрирован':
							'User with this email already registered';
							$errors_input[0]='email_for_code_input';
						}else{
							$to=$email_for_code;
							$code=rand(1000, 10000)+rand(2,4)+rand(500, 700);
							$subject = ($lang==='ru')?
							"Код доступа для регистрации на Paper Blog!":
							'Register code for Paper Blog';
							$message = "Register code for Paper Blog: ".$code;
							$headers ="Content-type: text/plain; charset=UTF-8 \r\n".
							'From: http://paperblog.ru' . "\r\n" .
							'Reply-To: http://paperblog.ru' . "\r\n" ;
							if(mail($to, $subject, $message,$headers)){
								$_SESSION['code']=($code*1+123456789)*2;
								$md5=md5($email_for_code);
								$_SESSION['ec']=$md5.$_SESSION['code'].$md5;
								$ajax_response['email_for_code']=$email_for_code;
								$ajax_response['status']='good';
							}else{
								$ajax_response['errors_input']=$errors_input;
								$ajax_response['status']=($lang==='ru')?
								'Отправка не удалась<br>Попробуйте ещё раз':
								'Sending failed <br> Try again';
							}
						}
					}else if($to_reg===0){
						if($just_array['interval']>0){
							$to=$email_for_code;
							$code=rand(1000, 10000)+rand(2,4)+rand(500, 700);
							$subject = ($lang==='ru')?
							"Код доступа для регистрации на Paper Blog!":
							'Register code for Paper Blog';
							$message = " Paper Blog: ".$code;
							$headers ="Content-type: text/plain; charset=windows-1251 \r\n".
							'From: http://paperblog.ru' . "\r\n" .
							'Reply-To: http://paperblog.ru' . "\r\n" ;
							if(mail($to, $subject, $message,$headers)){
								$_SESSION['code']=($code*1+123456789)*2;
								$md5=md5($email_for_code);
								$_SESSION['ec']=$md5.$_SESSION['code'].$md5;
								$_SESSION['email_for_code']=base64_encode($email_for_code);
								$ajax_response['status']='good';
							}else{
								$ajax_response['errors_input']=$errors_input;
								$ajax_response['status']=($lang==='ru')?
								'Отправка не удалась<br>Попробуйте ещё раз':
								'Sending failed <br> Try again';
							}
						}else{
							$ajax_response['errors_input']=$errors_input;
							$ajax_response['status']='The entered email is wrong';
						}
					}
				}else{
					$ajax_response['status']='error#'.__LINE__;
			}}else{
				$ajax_response['status']='error#'.__LINE__;
			}
		}
	}else{
		$ajax_response['status']='error#'.__LINE__;
	}
	if(count($errors_input)!==0){
		$ajax_response['errors_input']=$errors_input;
	}
	echo 'for(;;);'.json_encode($ajax_response);
}
?>