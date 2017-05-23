<?php
include('filter_post_array.php');
$lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
$post_array=json_decode(urldecode($_POST['to_search']));
$post_array=check_filter_input($post_array);
if(isset($post_array)&&$post_array){
	unset($_POST['to_search']);
	$ajax_response=array();
	$hash_sess_id=$_COOKIE['hash'];
	include('sql_lib.php');
	$s_w=new sql_work();
	if($NP=$s_w->connect()){
			
		function remove_whitespace($string){
			$string = preg_replace('/ {2,}/',' ',$string);
			$string = trim($string);
			return $string;
		}
		
		function file_get_contents_curl($url) {
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_URL, $url);
			$data = curl_exec($ch);
			curl_close($ch);
			return $data;
		}
		
		mysqli_autocommit($NP, false);
		
		include('get_user_profile.php');
		$type_of_search=strip_tags($post_array->type_of_search);
		$search_str=remove_whitespace($search_str);
		$search_str=addslashes($post_array->search_input);
		
		if($type_of_search==='aut_id_search'){
			$search_str=preg_replace('/[^0-9]/','', $search_str);
			if($search_str!==''){
				$ajax_response['profile']=get_user_profile($NP,$s_w,$search_str,'pabs',false);
				if($ajax_response['profile']['status']==='good'){
					if(isset($_COOKIE['hash'])){
						include('get_user_id.php');
						if($user_id=get_user_id($NP,$s_w,$hash_sess_id,false,false)){
							if($user_id!=='not_able'){
								$ajax_response['status']='good';
								$ajax_response['type_of_search']=$type_of_search;
								if($user_id===$ajax_response['profile']['id']*1){
									$ajax_response['profile']['user']='you';
								}else{
									include('work_with_compressed_str.php');
									if($fans_compressed_str=get_compressed_str($NP,$s_w,$search_str,'fans','ussd')){
										$ajax_response['profile']['user']='not_you';
										if(is_elm_in_compressed_str($fans_compressed_str,$user_id)){
											$ajax_response['profile']['is_aut']=true;
										}else{
											$ajax_response['profile']['is_aut']=false;
                                    }}else{
                                     	$ajax_response['status']='error#'.__LINE__;
								}}}else{
									$ajax_response['status']='not_able';
									setcookie('hash','', time() - 3600, '/', false, false);
							}}else{
								$ajax_response['status']='error#'.__LINE__;
						}}else{
							$ajax_response['profile']['user']='not_np_user';
							$ajax_response['status']='good';
							$ajax_response['type_of_search']=$type_of_search;
					}}else if($ajax_response['profile']['status']==='no_result'){
						$ajax_response['type_of_search']=$type_of_search;
						$ajax_response['status']='good';
					}else if($ajax_response['profile']['status']==='not_able'){
						$ajax_response['status']='not_able';
					}else{
						$ajax_response['status']='error#'.__LINE__;
				}}else{
					$ajax_response['profile']['status']='no_result';
					$ajax_response['type_of_search']=$type_of_search;
					$ajax_response['profile']['request']=($lang==='ru')?
					'Запрос профиля с номером "'.$search_str.'"':
					'Profile with id "'.$search_str.'"';
					$ajax_response['status']='good';
				}
				
				
     }else if($type_of_search==='title_search'){
     	
     	if($search_str!==""){
     		
     		$words=explode(' ',$search_str);$count=count($words);$query='';$types='';$params=array();
     		$query="SELECT id,title,path,date,aut FROM pablications USE INDEX(title) WHERE isreadable=1 AND (title LIKE '%".$search_str."%' ";
     		if($count>1){
     			for($i=0;$i<$count;$i++){
     				$word=$words[$i];
     				$query.=" OR title LIKE '%".$word."' ";
				}
			}
			
			$portion=48;
			$next_load_step=$post_array->next_load_step;
			
			if($next_load_step===-1){
				$query.=') ORDER BY lq DESC,vq DESC,id DESC LIMIT 0,?;';
				$params=array($portion+1);
				$types='i';
			}else{
				$query.=') ORDER BY lq DESC,vq DESC,id DESC LIMIT ?,?;';
				$params=array($portion*$next_load_step,$portion+1);
				$types='ii';
			}
			
			$ajax_response['pabs_data']=array();
			
			 if($ajax_response['pabs_data']['pabs']=$s_w->get_info_in_new_array($NP,$query,$params,$types)){
			 	
				$ajax_response['url']='/?query='.$search_str.'&type_of_search='.$type_of_search;
				
			 	$ajax_response['pabs_data']['pabs']['status']='good';
			 	$interval=$ajax_response['pabs_data']['pabs']['interval'];
				$ajax_response['pabs_data']['content_status']='search_pabs';
				$ajax_response['pabs_data']['query']=($lang==='ru')?
				'Результаты по запросу "'.$search_str.'"':
				'Results on request "'.$search_str.'"';
				$ajax_response['pabs_data']['search_str']=$search_str;
				$ajax_response['pabs_data']['type_of_search']=$type_of_search;
				
				if(!isset($post_array->clear)){
					$ajax_response['pabs_data']['clear']=true;
				}else{
					$ajax_response['pabs_data']['clear']=$post_array->clear;
				}	
			 	if($interval>$portion){
			 		$ajax_response['pabs_data']['next_load_step']=($next_load_step===-1)?1:$next_load_step+1;
					$ajax_response['pabs_data']['is_next']=true;
					unset($ajax_response['pabs_data']['pabs'][$portion]);
					$ajax_response['pabs_data']['pabs']['interval']-=1;
					$interval-=1;
			 	}else{
			 		$ajax_response['pabs_data']['next_load_step']=-1;
					$ajax_response['pabs_data']['is_next']=false;
			 	}
			 	if($interval>0){
			 			
			 		for($i=0;$i<$ajax_response['pabs_data']['pabs']['interval'];$i++){
			 			$ajax_response['pabs_data']['pabs'][$i]['content']=file_get_contents_curl('paper-blog.ru/mobile_'.$ajax_response['pabs_data']['pabs'][$i]['path']);
			 			$ajax_response['info']='mobile_'.$ajax_response['pabs_data']['pabs'][$i]['path'];
                    }
					
			 		$fn_q='SELECT name,fname FROM ussd WHERE id=';
			 		$fn_query='';
			 		for($i=0;$i<$interval;$i++){
			 			$fn_query.=$fn_q.($ajax_response['pabs_data']['pabs'][$i]['aut']*1).' LIMIT 1;';
					}
					if($ajax_response['pabs_data']['auts']=$s_w->multi_query_in_new_array(
					$NP,
					$fn_query,
					array('name','fname')
					)){
						
						$ajax_response['pabs_data']['auts']['status']='good';
						$ajax_response['pabs_data']['status']='good';
						
						$ajax_response['status']='good';
						
					}else{
						$res_array['auts']['status']='error#'.__LINE__;
					}
				}else{
					$ajax_response['pabs_data']['pabs']['status']='no_result';
					$ajax_response['pabs_data']['pabs']['interval']=0;
					$ajax_response['pabs_data']['status']='good';
					$ajax_response['pabs_data']['clear']=true;
					$ajax_response['status']='good';
				}
			 }else{
			 	$res_array['auts']['status']='error#'.__LINE__;
            }
     	}else{
     		$ajax_response['pabs_data']['pabs']['status']='no_result';
			$ajax_response['pabs_data']['content_status']='search_pabs';
			$ajax_response['pabs_data']['pabs']['interval']=0;
			$ajax_response['pabs_data']['status']='good';
			$ajax_response['pabs_data']['clear']=true;
			$ajax_response['pabs_data']['type_of_search']=$type_of_search;
			$ajax_response['pabs_data']['query']=($lang==='ru')?
			'Результаты по запросу "'.$search_str.'"':
			'Results on request "'.$search_str.'"';
			$ajax_response['pabs_data']['search_str']=$search_str;
			$ajax_response['status']='good';
     	}
     }

     else if($type_of_search==='aut_search'){
     	
     	if($search_str!==""){
     		
			$present_status=false;
			if(!isset($post_array->auts_cache)){
				$words=explode(' ',$search_str);$count=count($words);$query='';$types='';$params=array();
				$query="SELECT id FROM ussd USE INDEX(name_fname) WHERE isable=1 AND (CONCAT_WS(' ',name,fname) LIKE '%".$search_str."%' ";
				if($count>1){
					for($i=0;$i<$count;$i++){
						$word=$words[$i];
						$query.=" OR CONCAT_WS(' ',name,fname) LIKE '%".$word."' ";
					}
				}
				$query.=') ORDER BY fq DESC,pq DESC,id DESC LIMIT 0,5;';
				
				if($cache=$s_w->get_info_in_new_array(
				$NP,
				$query,
				$params,
				$types
				)){
					$present_status=true;
					$auts_cache=array();
					for($i=0;$i<$cache['interval'];$i++){
						$auts_cache[$i]=$cache[$i]['id'];
					}
				}else{
					$ajax_response['status']='error#'.__LINE__;
				}
			}else{
				$auts_cache=json_decode($post_array->auts_cache);
				$present_status=true;
			}
			if($present_status && isset($auts_cache)){
				if(count($auts_cache)>0){
					$a_count=count($auts_cache);$p=array();$t='';
					$query='SELECT id,title,path,date,aut FROM pablications USE INDEX(aut) WHERE isreadable=1 AND aut=?';
					$p[0]=$auts_cache[0];$t.='i';
					for($i=1;$i<$a_count;$i++){
						$query.=' OR aut=?';
						$p[$i]=$auts_cache[$i];
						$t.='i';
					}
					$portion=48;
					$next_load_step=$post_array->next_load_step;
					if($next_load_step===-1){
						$query.=' ORDER BY lq DESC,vq DESC,id DESC LIMIT 0,?;';
						$p[$a_count]=$portion+1;
						$t.='i';
					}else{
						$query.=' ORDER BY lq DESC,vq DESC,id DESC LIMIT ?,?;';
						$p[$a_count]=$portion*$next_load_step;
						$p[$a_count+1]=$portion+1;
						$t.='ii';
					}
					if($ajax_response['pabs_data']['pabs']=$s_w->get_info_in_new_array(
					$NP,
					$query,
					$p,
					$t
					)){
						
						$ajax_response['url']='/?query='.$search_str.'&type_of_search='.$type_of_search;
						
						$ajax_response['pabs_data']['pabs']['status']='good';
						$interval=$ajax_response['pabs_data']['pabs']['interval'];
						$ajax_response['pabs_data']['content_status']='search_pabs';
						$ajax_response['pabs_data']['query']=($lang==='ru')?
						'Результаты по запросу "'.$search_str.'"':
						'Results on request "'.$search_str.'"';
						$ajax_response['pabs_data']['search_str']=$search_str;
						$ajax_response['pabs_data']['type_of_search']=$type_of_search;
						$ajax_response['pabs_data']['auts_cache']=json_encode($auts_cache);
						
						if(!isset($post_array->clear)){
							$ajax_response['pabs_data']['clear']=true;
						}else{
							$ajax_response['pabs_data']['clear']=$post_array->clear;
						}
						
						if($interval>$portion){
							$ajax_response['pabs_data']['next_load_step']=($next_load_step===-1)?1:$next_load_step+1;
							$ajax_response['pabs_data']['is_next']=true;
							unset($ajax_response['pabs_data']['pabs'][$portion]);
							$ajax_response['pabs_data']['pabs']['interval']-=1;
							$interval-=1;
						}else{
							$ajax_response['pabs_data']['next_load_step']=-1;
							$ajax_response['pabs_data']['is_next']=false;
						}
						if($interval>0){
								
							for($i=0;$i<$ajax_response['pabs_data']['pabs']['interval'];$i++){
								$ajax_response['pabs_data']['pabs'][$i]['content']=file_get_contents_curl('paper-blog.ru/mobile_'.$ajax_response['pabs_data']['pabs'][$i]['path']);
								$ajax_response['info']='mobile_'.$ajax_response['pabs_data']['pabs'][$i]['path'];
							}
							
							$fn_q='SELECT name,fname FROM ussd WHERE id=';
							$fn_query='';
							for($i=0;$i<$interval;$i++){
								$fn_query.=$fn_q.($ajax_response['pabs_data']['pabs'][$i]['aut']*1).' LIMIT 1;';
                            }
                            if($ajax_response['pabs_data']['auts']=$s_w->multi_query_in_new_array(
                            $NP,
                            $fn_query,
                            array('name','fname')
							)){
                                $ajax_response['pabs_data']['auts']['status']='good';
								$ajax_response['pabs_data']['status']='good';
								$ajax_response['pabs_data']['content_status']='search_pabs';
								$ajax_response['status']='good';
							}else{
								$res_array['auts']['status']='error#'.__LINE__;
							}
                        }else{
                        	$ajax_response['pabs_data']['pabs']['status']='no_result';
                        	$ajax_response['pabs_data']['pabs']['interval']=0;
                        	$ajax_response['pabs_data']['status']='good';
                        	$ajax_response['pabs_data']['content_status']='search_pabs';
                        	$ajax_response['pabs_data']['clear']=true;
                        	$ajax_response['pabs_data']['type_of_search']=$type_of_search;
                        	$ajax_response['status']='good';
					}}else{
						$res_array['auts']['status']='error#'.__LINE__;
				}}else{
					$ajax_response['pabs_data']['pabs']['status']='no_result';
					$ajax_response['pabs_data']['content_status']='search_pabs';
					$ajax_response['pabs_data']['pabs']['interval']=0;
					$ajax_response['pabs_data']['status']='good';
					$ajax_response['pabs_data']['clear']=true;
					$ajax_response['pabs_data']['type_of_search']=$type_of_search;
					$ajax_response['pabs_data']['query']=($lang==='ru')?
					'Результаты по запросу "'.$search_str.'"':
					'Results on request "'.$search_str.'"';
					$ajax_response['pabs_data']['search_str']=$search_str;
					$ajax_response['status']='good';
				}}else{
					$res_array['auts']['status']='error#'.__LINE__;
            }	
     	}else{
     		$ajax_response['pabs_data']['pabs']['status']='no_result';
			$ajax_response['pabs_data']['content_status']='search_pabs';
			$ajax_response['pabs_data']['pabs']['interval']=0;
			$ajax_response['pabs_data']['status']='good';
			$ajax_response['pabs_data']['clear']=true;
			$ajax_response['pabs_data']['type_of_search']=$type_of_search;
			$ajax_response['pabs_data']['query']=($lang==='ru')?
			'Результаты по запросу "'.$search_str.'"':
			'Results on request "'.$search_str.'"';
			$ajax_response['pabs_data']['search_str']=$search_str;
			$ajax_response['status']='good';
     	}
     }else{
     	$res_array['auts']['status']='error#'.__LINE__;
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