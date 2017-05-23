<?php
include('filter_post_array.php');
$lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
$post_array=json_decode(urldecode($_POST['to_feed']));
$post_array=check_filter_input($post_array);
if(isset($post_array)&&$post_array){
	unset($_POST['to_feed']);
	$ajax_response=array();
	include('sql_lib.php');
	$s_w=new sql_work();
	if($NP=$s_w->connect()){
			
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
		
		$portion=48;
		$next_load_step=$post_array->next_load_step;
			
		$query="SELECT id,title,path,date,aut FROM pablications USE INDEX(title) WHERE isreadable=1 ";
		
		if($next_load_step===-1){
			$query.='ORDER BY lq DESC,vq DESC,id DESC LIMIT 0,?;';
			$params=array($portion+1);
			$types='i';
		}else{
			$query.='ORDER BY lq DESC,vq DESC,id DESC LIMIT ?,?;';
			$params=array($portion*$next_load_step,$portion+1);
			$types='ii';
		}
		
		if($ajax_response['pabs_data']['pabs']=$s_w->get_info_in_new_array($NP,$query,$params,$types)){
			 	
			 	$ajax_response['pabs_data']['pabs']['status']='good';
			 	$interval=$ajax_response['pabs_data']['pabs']['interval'];
				$ajax_response['pabs_data']['content_status']='feed_pabs';
				$ajax_response['pabs_data']['query']=($lang==='ru')?
				'Недавно опубликованные':'Recently published';
				
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
			 	
			 	for($i=0;$i<$ajax_response['pabs_data']['pabs']['interval'];$i++){ 
			 		$ajax_response['pabs_data']['pabs'][$i]['content']=file_get_contents_curl('paper-blog.ru/mobile_'.$ajax_response['pabs_data']['pabs'][$i]['path']);
					$ajax_response['info']='mobile_'.$ajax_response['pabs_data']['pabs'][$i]['path'];
				}
				
			 	if($interval>0){
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
						
						$ajax_response['pabs_data']['url']='/?feed=true';
												
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