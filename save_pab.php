<?php
$post_array=json_decode(urldecode($_POST['to_save_pab']));
$lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
if(isset($post_array)&&$post_array){
	unset($_POST['to_save_pab']);
	$ajax_response=array();
	$hash_sess_id=$_COOKIE['hash'];
	if(isset($_COOKIE['hash'])){
		include('sql_lib.php');
		$s_w=new sql_work();
		if($NP=$s_w->connect()){
			mysqli_autocommit($NP,false);
			include('get_user_id.php');
			if($user_id=get_user_id($NP,$s_w,$hash_sess_id,false,false)){
				if($user_id!=='not_able'){
						
					function translit($str) {
						$t = array(
						"А"=>"A","Б"=>"B","В"=>"V","Г"=>"G",
						"Д"=>"D","Е"=>"E","Ж"=>"J","З"=>"Z","И"=>"I",
						"Й"=>"Y","К"=>"K","Л"=>"L","М"=>"M","Н"=>"N",
						"О"=>"O","П"=>"P","Р"=>"R","С"=>"S","Т"=>"T",
						"У"=>"U","Ф"=>"F","Х"=>"H","Ц"=>"TS","Ч"=>"CH",
						"Ш"=>"SH","Щ"=>"SCH","Ъ"=>"","Ы"=>"YI","Ь"=>"",
						"Э"=>"E","Ю"=>"YU","Я"=>"YA","а"=>"a","б"=>"b",
						"в"=>"v","г"=>"g","д"=>"d","е"=>"e","ж"=>"j",
						"з"=>"z","и"=>"i","й"=>"y","к"=>"k","л"=>"l",
						"м"=>"m","н"=>"n","о"=>"o","п"=>"p","р"=>"r",
						"с"=>"s","т"=>"t","у"=>"u","ф"=>"f","х"=>"h",
						"ц"=>"ts","ч"=>"ch","ш"=>"sh","щ"=>"sch","ъ"=>"y",
						"ы"=>"yi","ь"=>"","э"=>"e","ю"=>"yu","я"=>"ya"
						);
						return strtr($str,$t);
					}
					
					$months=array(
					1=>'января',
					2=>'Февраля',
					3=>'марта',
					4=>'апреля',
					5=>'мая',
					6=>'июня',
					7=>'июля',
					8=>'августа',
					9=>'сентября',
					10=>'октября',
					11=>'ноября',
					12=>'декабря'
					);
					
					function reading_date($date,$months){
						$date_array=explode(':', $date);
						$da1=$date_array[1]*1;$dd=$date_array[0]*1;
						$res_date=$dd.' '.$months[$da1].' '.$date_array[2].'г';
						return $res_date;
					}
					
					function remove_whitespace($string){
						$string = preg_replace('/ {2,}/',' ',$string);
						$string = trim($string);
						return $string;
					}
					
					$title=htmlspecialchars(remove_whitespace($post_array->title));
                    $aut=$user_id*1;
                    $standart_str=gzcompress(json_encode(array()));
                    $translit_title=translit($title);
                    $date=reading_date(date('d:m:Y'),$months);
                    $to_where=strip_tags($post_array->to_where);$pabs_params=array();$pabs_types_str='';
                    $title_reg='/^([a-zа-яё -.,;?! 0-9]){1,65}$/iu';
					$for_remake_content=strip_tags($post_array->for_remake_content);
					$for_remake_pab_id=is_int($post_array->for_remake_pab_id*1)?$post_array->for_remake_pab_id*1:null;
					$is_for_remake=($for_remake_pab_id===0)?0:1;
					$pabs_query='';$continue=true;
					$path=false;
					if($is_for_remake===1){
						$pqaq=($for_remake_content==='pabs')
						?'SELECT path FROM pablications WHERE id=? LIMIT 1;'
						:'SELECT path FROM copywritings WHERE id=? LIMIT 1;';
						if($p_a=$s_w->get_info_in_new_array($NP,$pqaq,array($for_remake_pab_id),'i')){
							$path=$p_a[0]['path'];
							$mobile_path='mobile_'.$path;
						}else{
							$continue=false;
						}
					}else{
						$path='pabs/'.$user_id.str_replace(' ','',$translit_title.mt_rand(101,1001)).'.html';
						$mobile_path='mobile_'.$path;
					}
					include('work_with_compressed_str.php');
					
					if((($is_for_remake===1)||($is_for_remake===0))&&$continue){
						if($is_for_remake===1){
							if($test_pab_id_str=get_compressed_str($NP,$s_w,$user_id,$for_remake_content,'ussd')){
								if($test_is_id=is_elm_in_compressed_str($test_pab_id_str,$for_remake_pab_id)){
									$pabs_query='UPDATE ';
									if($for_remake_content==='pabs'){
										if($to_where==='pablications'){
											$pabs_query.='pablications SET title=? ,aut=? ,path=? , date=? WHERE id=? LIMIT 1;';
											$pabs_params=array($title,$aut,$path,$date,$for_remake_pab_id);
											$pabs_types_str='sissi';											
										}else if($to_where==='copywritings'){
											$pabs_query='INSERT INTO copywritings (title,aut,path,date) VALUES (?,?,?,?) ;';
											$pabs_params=array($title,$aut,$path,$date);
											$pabs_types_str='siss';
										}
									}else if($for_remake_content==='copies'){
										if($to_where==='copywritings'){
											$pabs_query.='copywritings SET title=? ,aut=? ,path=?, date=? WHERE id=? LIMIT 1;';
											$pabs_params=array($title,$aut,$path,$date,$for_remake_pab_id);
											$pabs_types_str='sissi';	
										}else if($to_where==='pablications'){
											$pabs_query='INSERT INTO pablications (title,aut,likes,dislikes,views,path,date,corrects) VALUES (?,?,?,?,?,?,?,?) ;';
											$pabs_params=array($title,$aut,$standart_str,$standart_str,$standart_str,$path,$date,$standart_str);
											$pabs_types_str='sissssss';
										}
									}else{
										$pabs_query=false;
									}
								}else{
									$pabs_query=false;
								}
							}else{
								$pabs_query=false;
							}
						}else{
                       		$pabs_query='INSERT INTO ';
                       		if($to_where==='pablications'){
                       			$pabs_query.='pablications (title,aut,likes,dislikes,views,path,date,corrects) VALUES (?,?,?,?,?,?,?,?) ';
                       			$pabs_params=array($title,$aut,$standart_str,$standart_str,$standart_str,$path,$date,$standart_str);
                       			$pabs_types_str='sissssss';
							}else if($to_where==='copywritings'){
								$pabs_query.='copywritings (title,aut,path,date) VALUES (?,?,?,?) ';
								$pabs_params=array($title,$aut,$path,$date);
								$pabs_types_str='siss';
							}else{
								$pabs_query=false;
							}
                       	}
                      }else{
                      	$pabs_query=false;
                      }
					   if(preg_match($title_reg,$title) && $pabs_query){
					   	include('processing_content.php');
					   	$html=processing_content($post_array->content,$title);
						$content=$html[0];
						$mobile_content=$html[1];
						$ajax_response['time']=$html[2];
					   	if(($content&&$mobile_content)&&((strlen($content))>300)&&(strlen($mobile_content))>300){
					   		if($s_w->manipulation(
					   		$NP,
					   		$pabs_query,
					   		$pabs_params,
					   		$pabs_types_str
							)){
								$pab_id=mysqli_insert_id($NP);
								
								$status=false;
								if($is_for_remake===1){
									$ajax_response['to_where']=$to_where;
									$ajax_response['from_where']=$for_remake_content;
									if(($to_where==='pablications')&&($for_remake_content==='copies')){
									$is_for_remake=0;
									if($compr_str=get_compressed_str($NP,$s_w,$user_id,'copies','ussd')){
										if($new_compr_str=delete_from_compressed_str($compr_str,$for_remake_pab_id)){
											if(update_compressed_str($NP,$s_w,$user_id,'copies','ussd',$new_compr_str,'cpq','-')){
												if($s_w->manipulation(
												$NP,
												'UPDATE copywritings SET isreadable=0 WHERE id=? LIMIT 1;',
												array($for_remake_pab_id),
												'i'
												)){
													$status='good';
												}else{
													$status='error#'.__LINE__;
												}
											}else{
												$status='error#'.__LINE__;
										}}else{
											$status='error#'.__LINE__;
									}}else{
										$ajax_response['status']='error#'.__LINE__;
								}}else if(($to_where==='copywritings')&&($for_remake_content==='pabs')){
									$is_for_remake=0;
									if($compr_str=get_compressed_str($NP,$s_w,$user_id,'pabs','ussd')){
										if($new_compr_str=delete_from_compressed_str($compr_str,$for_remake_pab_id)){
											if(update_compressed_str($NP,$s_w,$user_id,'pabs','ussd',$new_compr_str,'pq','-')){
												if($s_w->manipulation(
												$NP,
												'UPDATE pablications SET isreadable=0,iswritable=0 WHERE id=? LIMIT 1;',
												array($for_remake_pab_id),
												'i'
												)){
													$status='good';
												}else{
													$status='error#'.__LINE__;
												}
											}else{
												$status='error#'.__LINE__;
										}}else{
											$status='error#'.__LINE__;
									}}else{
										$status='error#'.__LINE__;
									}
								}}else{
									$status='good';
									$ajax_response['to_where']=$to_where;
								}
								
								if($is_for_remake===0){
									if($status==='good'){
										$from_where=false;$q=false;
										if($to_where==='pablications'){
											$from_where='pabs';$q='pq';
										}else if($to_where==='copywritings'){
											$from_where='copies';$q='cpq';
										}
										if($from_where&&$q){
											if($new_pabs_array_to_save=get_compressed_str($NP,$s_w,$aut,$from_where,'ussd')){
												if($updated_pabs_array_to_save=add_to_compressed_str($new_pabs_array_to_save,$pab_id)){
													if(update_compressed_str($NP,$s_w,$aut,$from_where,'ussd',$updated_pabs_array_to_save,$q,'+')){
														file_put_contents($path,$content);
														file_put_contents($mobile_path,$mobile_content);
														$ajax_response['status']='good';
													}else{
														$status='error#'.__LINE__;
												}}else{
													$status='error#'.__LINE__;
											}}else{
												$status='error#'.__LINE__;
										}}else{
											$status='error#'.__LINE__;
										}									
									}else{
										$ajax_response['status']='error#'.__LINE__.'->'.$status;
								}}else{
									file_put_contents($path,$content);
									file_put_contents($mobile_path,$mobile_content);
									$ajax_response['status']='good';
								}}else{
									$ajax_response['status']=($lang==='ru')?'Неверные данные!':'Invalid data';
							}}else{
								$ajax_response['status']=($lang==='ru')?'Неверные данные!':'Invalid data';
						}}else{
							$errors_input=array();$k=0;
							if(!preg_match($title_reg,$title)||$title===''){
								$errors_input[$k]='title';$k++;
							}
							$ajax_response['status']=($lang==='ru')?'Неверные данные!':'Invalid data';
							if($k!==0)
							$ajax_response['errors_input']=$errors_input;
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