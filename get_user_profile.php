<?php
$lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
function get_user_profile($link,$s_w_object,$user_id,$content_status,$need_fn){
	$result=array();
	$query=(($content_status==='auts')||($content_status==='fans')||($content_status==='pabs')||($content_status==='copies')||($content_status==='faves'))
	?'SELECT name,fname,'.$content_status.',isable FROM ussd WHERE id=? LIMIT 1;'
	:'SELECT name,fname,isable FROM ussd WHERE id=? LIMIT 1;';
	if($pre_result=$s_w_object->get_info_in_new_array(
	$link,
	$query,
	array($user_id),
	'i'
	)){
		if($pre_result['interval']>0){
			$result['id']=$user_id;
			$result['name']=$pre_result[0]['name']; 
			$result['fname']=$pre_result[0]['fname'];
			if($pre_result[0]['isable']!==0){

				if(($content_status==='auts')||($content_status==='fans')){
					include('get_auts_fans_list.php');
					if($result['list_data']=get_auts_fans_list($link,$s_w_object,$content_status,$result['id'],$pre_result[0][$content_status],-1)){
						if($result['list_data']['status']==='good'){
							$result['status']='good';
						}else{
							$res_array['list_data']['status']='error#'.__LINE__.'->'.$result['list_data']['status'];
					}}else{
						$result['status']='error#'.__LINE__;
				}}else if(($content_status==='pabs')||($content_status==='copies')||($content_status==='faves')){
					include('get_pabs.php');
					if($result['pabs_data']=get_pabs($link,$s_w_object,$content_status,$result['id'],$pre_result[0][$content_status],-1,$need_fn)){
						if($result['pabs_data']['status']==='good'){
							$result['status']='good';
						}else{
							$res_array['pabs_data']['status']='error#'.__LINE__.'->'.$result['pabs_data']['status'];
					}}else{
						$result['status']='error#'.__LINE__;
					}
				}else{
					$result['status']='good';
					$result['user_id']=$user_id*1;
					$result['content_status']=$content_status;
					$result['need_fn']=$need_fn;
					$nf=($need_fn)?1:0;
					$result['url']='/';
					$result['request']=($lang==='ru')?
					'Запрос профиля с номером "'.$user_id.'"':
					'Profile with id "'.$user_id.'"';
				}}else{
					$result['status']='not_able';
			}}else{
				$result['status']='no_result';
				$result['user_id']=$user_id*1;
				$result['content_status']=$content_status;
				$result['need_fn']=$need_fn;
				$nf=($need_fn)?1:0;
				$result['url']='/?profile='.$user_id.'&content_status='.$content_status.'&need_fn='.$nf;
				$result['request']=($lang==='ru')?
				'Запрос профиля с номером "'.$user_id.'"':
				'Profile with id "'.$user_id.'"';
		}}else{
			$result['status']='error#'.__LINE__;
		}
		return $result;
}
?>