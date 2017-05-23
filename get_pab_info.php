<?php
function get_pab_info($link,$s_w_object,$user_id,$table,$content_status,$pab_id){
	$res_array=array();$pab_id=$pab_id*1;$pab_info_query='';$params=array();$types='';
	if($table==='pablications'){
		$pab_info_query='SELECT title,aut,likes,dislikes,lq,dlq,vq,likes,dislikes,path,date,corrects,isreadable,iswritable';
	}else{
		$pab_info_query='SELECT title,path,date,aut,isreadable';
	}
	$params=array($pab_id);
	$types='i';
	$pab_info_query.=' FROM '.$table.' WHERE id=? LIMIT 1';
	if($pab_info_array=$s_w_object->get_info_in_new_array($link,$pab_info_query,$params,$types)){
		$pab_info_array['info']=$pab_id;
		if($pab_info_array['interval']>0){
			
			$res_array['pab_info']=$pab_info_array[0];
			$res_array['pab_info']['id']=$pab_id;
			
			$res_array['pab_info']['status']='good';
			
			$fn_query='SELECT name,fname FROM ussd WHERE id=? LIMIT 1';
			$params=array($res_array['pab_info']['aut']*1);
			$types='i';$faved=false;
			if($fn_array=$s_w_object->get_info_in_new_array($link,$fn_query,$params,$types)){
				$res_array['pab_info']['aut_name']=$fn_array[0]['name'];
				$res_array['pab_info']['aut_fname']=$fn_array[0]['fname'];
				$res_array['pab_info']['type_of_pab']=$table;
				$res_array['pab_info']['fn']['status']='good';
			}else{
				$res_array['fn']['status']='error#'.__LINE__;
			}
			
			if(is_elm_in_compressed_str($res_array['pab_info']['likes'],$user_id)){
				$res_array['pab_info']['liked']=true;
			}else{
				$res_array['pab_info']['liked']=false;
			}
			if(is_elm_in_compressed_str($res_array['pab_info']['dislikes'],$user_id)){
				$res_array['pab_info']['disliked']=true;
			}else{
				$res_array['pab_info']['disliked']=false;
			}
			
			if($user_id!==0){
				if($faved=$s_w_object->get_info_in_new_array($link,'SELECT faves FROM ussd WHERE id=? LIMIT 1',array($user_id),'i')){
					$res_array['pab_info']['faved']=is_elm_in_compressed_str($faved[0]['faves'],$pab_id);	
				}else{
					$res_array['pab_info']['faved']='error#'.__LINE__;
				}
				if($res_array['pab_info']['aut']===$user_id){
					$res_array['pab_info']['your']=true;
					$res_array['pab_info']['corrects_q']=count(json_decode(gzuncompress($res_array['pab_info']['corrects']),true));
					$res_array['pab_info']['cinfo']=(gzuncompress($res_array['pab_info']['corrects']));
					unset($res_array['pab_info']['corrects']);
				}else{
					$res_array['pab_info']['your']=false;
					if($table==='copywritings'){
						unset($res_array['pab_info']);
					}
					unset($res_array['pab_info']['corrects']);
				}
			}else{
				if($table==='copywritings'){
					unset($res_array['pab_info']);
				}
			}
			
		}else{
			$res_array['pab_info']['status']='error#'.__LINE__;
	}}else{
		$res_array['pab_info']['status']='error#'.__LINE__;
}
if(($res_array['pab_info']['status']==='good')&&($res_array['pab_info']['fn']['status']==='good')){
	unset($res_array['pab_info']['likes']);
	unset($res_array['pab_info']['dislikes']);	
	$res_array['pab_info']['content_status']=$content_status;
	$res_array['status']='good';
}else{
	$res_array['status']=$res_array['pabs_info']['status'].'->'.$res_array['fn']['status'];
}
return $res_array;
}

?>