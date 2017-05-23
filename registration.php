<?php 
$post_array=json_decode(urldecode($_POST['to_reg']));
if(isset($post_array)){
	session_start();
	$ajax_response=array();
	unset($_POST['to_reg']);
	if(!isset($_COOKIE['hash'])){
			
		function remove_whitespace($string){
			$string = preg_replace('/ {2,}/',' ',$string) ; 
			$string = trim($string) ; 
			return $string;
		}
		
		$regname=htmlspecialchars(remove_whitespace($post_array->regname));
		$regfname=htmlspecialchars(remove_whitespace($post_array->regfname));
		$reglogin=htmlspecialchars(remove_whitespace($post_array->reglogin));
		$regemail=htmlspecialchars(remove_whitespace($post_array->regemail));
		$regpword=htmlspecialchars(remove_whitespace($post_array->regpword));
		$regpword2=htmlspecialchars(remove_whitespace($post_array->regpword2));
		$captcha=htmlspecialchars($post_array->captcha_input);
		$reg_code=htmlspecialchars($post_array->reg_code);
		$name_reg='/^([a-zа-яё -]){1,50}$/iu';
		$login_reg='/^([a-zа-яё0-9._ -]){4,30}$/iu';
		$email_reg='/^([a-z0-9_\.-]+)@([a-z0-9_\.-]+)\.([a-z\.]{2,6})$/iu';
		$pword_reg='/^([a-zа-яё0-9.;_-]){8,}$/iu';  
		$errors_input=array();
		if($regname===''||$regfname===''||$reglogin===''||$regemail===''||$regpword===''||$regpword2===''||$captcha===''){
			$ajax_response['status']='Заполните все поля';
			$e_count=0;
			if($regname===''){$errors_input[$e_count]='regname';$e_count++;}
			if($regfname===''){$errors_input[$e_count]='regfname';$e_count++;}
			if($reglogin===''){$errors_input[$e_count]='reglogin';$e_count++;}
			if($regemail===''){$errors_input[$e_count]='regemail';$e_count++;}
			if($regpword===''){$errors_input[$e_count]='regpword';$e_count++;}
			if($regpword2===''){$errors_input[$e_count]='regpword2';$e_count++;}
			if($captcha===''){$errors_input[$e_count]='captcha_input';}
		}else if(!preg_match($name_reg, $regname)){
			$ajax_response['status']='Недопустимые символы в имени';
			$errors_input[0]='regname';
		}else if(!preg_match($name_reg,$regfname)){
			$ajax_response['status']='Недопустимые символы в фамилии';
			$errors_input[0]='regfname';
		}else if(!preg_match($login_reg, $reglogin)){
			$ajax_response['status']='Недопустимые символы в логине(aA-zZ,аА-яЯ,0-9,_-.)';
			$errors_input[0]='reglogin';
		}else if(!preg_match($email_reg, $regemail)){
			$ajax_response['status']='Неверный формат email';
			$errors_input[0]='regemail';
		}else if(!preg_match($pword_reg, $regpword)){
			$ajax_response['status']='Недопустимые символы в пароле, верный формат:aA-zZаА-яЯ0-9,_-.; и длина не менее 8 символов';
			$errors_input[0]='regpword';
		}else if($regpword!==$regpword2){
			$ajax_response['status']='Введённые пароли не совпадают';
			$errors_input[0]='regpword';
			$errors_input[1]='regpword2';
		}else if(md5($captcha)!==$_SESSION['captcha']){
			$ajax_response['status']='Попробуйте ещё раз ввести текст с картинки';
			$errors_input[0]='captcha_input';
		}else{
			include('sql_lib.php');
			if(isset($_SESSION['code'],$_SESSION['ec'])){
				$md5=md5($regemail);
				if((($reg_code+123456789)*2===$_SESSION['code'])&&($md5.$_SESSION['code'].$md5===$_SESSION['ec'])){
					$s_w=new sql_work();
					if($NP=$s_w->connect()){
						mysqli_autocommit($NP, false);
						$is_able_login_query='SELECT id FROM ussd USE INDEX (email_login) WHERE login=?  LIMIT 1';
						$is_able_email_query='SELECT id FROM ussd USE INDEX (email_login) WHERE email=?  LIMIT 1';
						if($just_array=$s_w->get_info_in_new_array(
						$NP,
						$is_able_login_query,
						array($reglogin),
						's'
						)){
							if($just_array['interval']>0){
								$ajax_response['status']='Пользователь с таким логином уже зарегистрирован';
								$errors_input[0]='reglogin';
							}else{
								if($just_array=$s_w->get_info_in_new_array(
								$NP,
								$is_able_email_query,
								array($regemail),
								's'
								)){
									if($just_array['interval']>0){
										$ajax_response['status']='Пользователь с таким email уже зарегистрирован';
										$errors_input[0]='regemail';
									}else{
										$reg_query='INSERT INTO ussd (name,fname,login,email,password,pabs,copies,faves,fans,auts,likes,dislikes,views,hash_sess_id) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)';
										$hash_sess_id=hash('sha256',$reglogin.rand(12345, 67890).$regemail.'NetPapers',false);
										$regpword=hash('sha256',$regpword,false);
										$ua=array();$ua[0]=strtolower($_SERVER['HTTP_USER_AGENT']);
										$ua_str=gzcompress(json_encode($ua));
										$standart_str=gzcompress(json_encode(array()));
										if($s_w->manipulation(
										$NP,
										$reg_query,
										array($regname,$regfname,$reglogin,$regemail,$regpword,$standart_str,$standart_str,$standart_str,$standart_str,$standart_str,$standart_str,$standart_str,$standart_str,$hash_sess_id),
										'ssssssssssssss'
										)){
											include('get_user_profile.php');
											$user_id=mysqli_insert_id($NP);
											$ajax_response['profile']=get_user_profile($NP,$s_w,$user_id,'pabs',false);
											if($ajax_response['profile']['status']==='good'){
												$ajax_response['profile']['user']='you';
												$ajax_response['status']='good';
												setcookie('hash',$hash_sess_id, time()+2678400, '/','.paper-blog.ru', false,true);
											}else{
												$ajax_response['status']='error#'.__LINE__;
										}}else{
											$ajax_response['status']='error#'.__LINE__;
									}}}else{
										$ajax_response['status']='error#'.__LINE__;
								}}}else{
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
                    	$ajax_response['status']='Неверный код';
               }}else{
               	$ajax_response['status']='Действие кода прекращено.<br>Попробуйте снова получить код <br> доступа для регистрации';
			}}
			if(count($errors_input)!==0){
					$ajax_response['errors_input']=$errors_input;
		}}else{
			$ajax_response['status']='error#'.__LINE__;
		}
		echo 'for(;;);'.json_encode($ajax_response);
}
?>