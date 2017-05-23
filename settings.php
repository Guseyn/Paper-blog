<?php
$post_array=json_decode(urldecode($_POST['to_settings']));
$lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
if(isset($post_array)&&$post_array){
	unset($_POST['to_settings']);
	$ajax_response=array();
	if(isset($_COOKIE['hash'])){
		$hash_sess_id=$_COOKIE['hash'];
		include('sql_lib.php');
		$s_w=new sql_work();
		if($NP=$s_w->connect()){
			mysqli_autocommit($NP,false);
			include('get_user_id.php');
			if($user_id=get_user_id($NP,$s_w,$hash_sess_id,false,false)){
				if($user_id!=='not_able'){
						
					function remove_whitespace($string){
						$string = preg_replace('/ {2,}/',' ',$string) ;
						$string = trim($string) ;
						return $string;
					}
					
					$change_name=htmlspecialchars(remove_whitespace($post_array->change_name));
					$change_fname=htmlspecialchars(remove_whitespace($post_array->change_fname));
					$change_login=htmlspecialchars(remove_whitespace($post_array->change_login));
					$change_email=htmlspecialchars(remove_whitespace($post_array->change_email));
					$change_old_pword=htmlspecialchars(remove_whitespace($post_array->change_old_pword));
					$change_pword=htmlspecialchars(remove_whitespace($post_array->change_pword));
					$change_pword2=htmlspecialchars(remove_whitespace($post_array->change_pword2));
					$name_reg='/^([a-zа-яё -]){1,50}$/iu';
					$login_reg='/^([a-zа-яё0-9._ -]){4,30}$/iu';
					$email_reg='/^([a-z0-9_\.-]+)@([a-z0-9_\.-]+)\.([a-z\.]{2,6})$/iu';
					$pword_reg='/^([a-zа-яё0-9.,;_-]){8,}$/iu';
					$errors_input=array();
					if(
					($change_name==='')&&
					($change_fname==='')&&
					($change_login==='')&&
					($change_email==='')&&
					($change_pword==='')&&
					($change_pword2==='')
					){
						$ajax_response['status']=($lang==='ru')?
						'Нет данных для изменения параметров профиля':
						'No data to edit a profile';
					}else{
						if($change_old_pword!==''){
								if(preg_match($pword_reg, $change_old_pword)){
									$is_able_pword_query='SELECT password FROM ussd WHERE id=? LIMIT 1';
									if($just_array=$s_w->get_info_in_new_array(
									$NP,
									$is_able_pword_query,
									array($user_id),
									's'
									)){
										if($just_array['interval']===0){
											$ajax_response['status']='Неверный пароль';
											$errors_input[0]='change_old_pword';$ec++;
										}else{
											if(hash('sha256',$change_old_pword,false)===$just_array[0]['password']){
												$query='UPDATE ussd SET ';
												$params=array();$types='';
												$ec=0;$c=0;
												if($change_name!==''){
													if(preg_match($name_reg, $change_name)){
														$query.='name=? ';
														$types.='s';
														$params[$c]=$change_name;$c++;
													}else{
														$ec++;
														$ajax_response['status']=($lang==='ru')?
														'Недопустимые символы в имени':
														'Invalid characters in name';
														$errors_input[0]='change_name';
													}
												}
												if($ec===0){
													if($change_fname!==''){
														if(preg_match($name_reg, $change_fname)){
															$query.=($c===0)?'fname=? ':',fname=? ';
															$types.='s';
															$params[$c]=$change_fname;$c++;
														}else{
															$ec++;
															$ajax_response['status']=($lang==='ru')?
															'Недопустимые символы в фамилии':
															'Invalid characters in last name';
															$errors_input[0]='change_fname';
														}
													}
												}
												if($ec===0){
													if($change_login!==''){
														if(preg_match($login_reg, $change_login)){
															$is_able_login_query='SELECT id FROM ussd USE INDEX (email_login) WHERE login=?  LIMIT 1';
															if($just_array=$s_w->get_info_in_new_array(
															$NP,
															$is_able_login_query,
															array($change_login),
															's'
															)){
																if($just_array['interval']>0){
																	$ajax_response['status']=($lang==='ru')?
																	'Пользователь с таким логином уже зарегистрирован':
																	'User with such login is already registered';
																	$errors_input[0]='change_login';$ec++;
																}else{
																	$query.=($c===0)?'login=? ':', login=? ';
																	$types.='s';
																	$params[$c]=$change_login;$c++;
																}
															}
														}else{
															$ec++;
															$ajax_response['status']=($lang==='ru')?
															'Недопустимые символы в логине':'Invalid characters in login';
															$errors_input[0]='change_login';
														}
													}
												}
												if($ec===0){
													if($change_email!==''){
														if(preg_match($email_reg, $change_email)){
															$is_able_login_query='SELECT id FROM ussd USE INDEX (email_login) WHERE email=?  LIMIT 1';
															if($just_array=$s_w->get_info_in_new_array(
															$NP,
															$is_able_login_query,
															array($change_email),
															's'
															)){
																if($just_array['interval']>0){
																	$ajax_response['status']=($lang==='ru')?
																	'Пользователь с таким email уже зарегистрирован':
																	'User with this email already registered';
																	$errors_input[0]='change_email';$ec++;
																}else{
																	$query.=($c===0)?'email=? ':', email=? ';
																	$types.='s';
																	$params[$c]=$change_email;$c++;
																}
															}
														}else{
															$ec++;
															$ajax_response['status']=($lang==='ru')?
															'Недопустимые символы в email':
															'Invalid characters in email';
															$errors_input[0]='change_email';
														}
													}
												}
												if($ec===0){
													if(($change_pword!=='')&&($change_pword2!=='')){
														if($change_pword===$change_pword2){
															if(preg_match($pword_reg, $change_pword)){
																$query.=($c===0)?'password=? ':', password=? ';
																$types.='s';
																$params[$c]=hash('sha256',$change_pword,false);$c++;
															}else{
																$ec++;
																$ajax_response['status']=($lang==='ru')?
																'Недопустимые символы в пароле, верный формат:aA-zZаА-яЯ0-9,_-.; и длина не менее 8 символов':
																'Invalid characters in the password, the correct format: aA-zZaA-yaYa0-9, _- .; and the length of at least 8 characters';
																$errors_input[0]='change_pword';
															}
														}else{
															$ec++;
															$ajax_response['status']=($lang==='ru')?
															'Введённые пароли не совпадают':
															'The entered passwords do not match';
															$errors_input[0]='change_pword';
															$errors_input[1]='change_pword2';
														}
													}else if((($change_pword!=='')&&($change_pword2===''))||(($change_pword==='')&&($change_pword2!==''))){
														$ec++;
														$ajax_response['status']=($lang==='ru')?
														'Заполните все нужные поля для изменения пароля':
														'Fill in all required fields to change your password';
														$errors_input[0]='change_pword';
														$errors_input[1]='change_pword2';
													}
                                                }
                                                if($ec===0){
                                                	$query.='WHERE id=? LIMIT 1;';
                                                	$types.='i';
                                                	$params[$c]=$user_id;
                                                	if($s_w->manipulation(
                                                	$NP,
                                                	$query,
                                                	$params,
                                                	$types
													)){
														$ajax_response['status']='good';
													}else{
														$ajax_response['info']=$query;
														$ajax_response['status']='error#'.__LINE__;
													}
												}
											}else{
												$ajax_response['status']=($lang==='ru')?
												'Неверный пароль':'The entered passsword is wrong';
												$errors_input[0]='change_old_pword';$ec++;
											}
										}
									}
								}else{
									$ec++;
									$ajax_response['status']=($lang==='ru')?
									'Неверный пароль':'The entered passsword is wrong';
									$errors_input[0]='change_old_pword';
								}
							}else{
								$ajax_response['status']='You must enter the password for setting your parameters';
								$errors_input[0]='change_old_pword';
							}
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
if(count($errors_input)!==0){
	$ajax_response['errors_input']=$errors_input;
}
echo 'for(;;);'.json_encode($ajax_response);
}
?>