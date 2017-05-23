<?php
include('filter_post_array.php');
$post_array=json_decode(urldecode($_POST['to_open_pab']));
$post_array=check_filter_input($post_array);
if(isset($post_array)&&$post_array){
	unset($_POST['to_open_pab']);
	$hash_sess_id=$_COOKIE['hash'];
	$ajax_response=array();
	include('sql_lib.php');
	$s_w=new sql_work();
	if($NP=$s_w->connect()){
		mysqli_autocommit($NP, false);
		include('get_user_id.php');
		include('work_with_compressed_str.php');
		include('get_pab_info.php');
		$table=($post_array->content_status==='copies')?'copywritings':'pablications';
		if(isset($_COOKIE['hash'])){
			if($user_id=get_user_id($NP,$s_w,$hash_sess_id,false,false)){
				if($user_id!=='not_able'){
					if($ajax_response=get_pab_info($NP,$s_w,$user_id,$table,$post_array->content_status,$post_array->pab_id,$post_array->need_fn)){
						if(($ajax_response['pab_info']['status']==='good')
						&&(($ajax_response['pab_info']['path']===$post_array->path)
						||($ajax_response['pab_info']['path']==='pabs/deleted_pab_by_user.html'))
						||($ajax_response['pab_info']['path']==='pabs/deleted_pab_by_admin.html')){
							$ajax_response['status']='good';
						}else{
							$ajax_response['status']='fail';
						}}else{
							$ajax_response['status']='error#'.__LINE__;		
					}}else{
						$ajax_response['status']='not_able';
				}}else{
					$ajax_response['status']='no_sess';
					setcookie('hash',$hash_sess_id, time()-3600, '/','.paper-blog.ru', false,true);
			}}else{
				if($ajax_response=get_pab_info($NP,$s_w,0,$table,$post_array->content_status,$post_array->pab_id,$post_array->need_fn)){
					if(($ajax_response['pab_info']['status']==='good')
					&&(($ajax_response['pab_info']['path']===$post_array->path)
					||($ajax_response['pab_info']['path']==='deleted_pab_by_user.html'))
					||($ajax_response['pab_info']['path']==='deleted_pab_by_admin.html')){
						$ajax_response['status']='not_np_user';
						if($table==='copywritings')
						unset($ajax_response['pab_info']);
					}else{
						$ajax_response['status']='none';
				}}else{
					$ajax_response['status']='error#'.__LINE__;
				}
			}
			if($ajax_response['status']==='good'){
				mysqli_commit($NP);
			}else{
				mysqli_rollback($NP);
		}}else{
			$ajax_response['status']='error#'.__LINE__;
		}
		$s_w->sql_close($NP);
		
		function file_get_contents_curl($url) {
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_URL, $url);
			$data = curl_exec($ch);
			curl_close($ch);
			return $data;
		}

		if(($ajax_response['status']==='good')||($ajax_response['status']==='not_np_user'&&$table!=='copywritings')){
			$content=file_get_contents_curl('paper-blog.ru/'.$ajax_response['pab_info']['path']);
			$dom = new DOMDocument();
			$dom->loadHTML($content);
			$tags=$dom->getElementsByTagName('*');
			$bad_tags=array();
			$bad_tags_count=0;
			foreach($tags as $t){
				$node_name=strtolower($t->nodeName);
				if($node_name==='script'){
					$bad_tags[$bad_tags_count]=$t;
					$bad_tags_count++;
				}
			}
			for($i=0;$i<$bad_tags_count;$i++){
				$bad_tags[$i]->parentNode->removeChild($bad_tags[$i]);
			}
			$head=$dom->getElementsByTagName('head')->item(0);
			$head->parentNode->removeChild($head);
			$ajax_response['pab']=$dom->saveHTML();
			$ajax_response['url']='/?pab_id='.$post_array->pab_id.'&path='.$ajax_response['pab_info']['path'].'&content_status='.$post_array->content_status;
		}
		echo 'for(;;);'.json_encode($ajax_response); 		
}
?>