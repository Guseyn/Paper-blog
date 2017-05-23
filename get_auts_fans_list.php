<?php

function get_auts_fans_list($link,$s_w_object,$type_of_rects,$user_id,$list_compressed_string,$next_load_step){
	$res_array=array();	
	$list_q='SELECT id,name,fname FROM ussd WHERE id=';$list_query='';
	$rects=json_decode(gzuncompress($list_compressed_string));
	$list_length=count($rects);
	if($list_length!==0){
		$portion=48;$is_next=false;
		$start_point=($next_load_step===-1)?$list_length-1:$next_load_step;
		$end_point=($start_point>=$portion)?$start_point-$portion+1:0;
		$next_load_step=$end_point-1;
		if($start_point===$list_length-1)
		$res_array['clear']=true;
		else $res_array['clear']=false;
		if($end_point!==0){$is_next=true;}
		for($i=$start_point;$i>=$end_point;$i--){
			$new=$rects[$i]*1;
			$list_query.=$list_q.$new.' LIMIT 1;';
		}
		$keys=array('id','name','fname');
		if($res_list_array=$s_w_object->multi_query_in_new_array(
		$link,
		$list_query,
		$keys
		)){
			
			$res_array['list']=$res_list_array;
			$res_array['list']['status']='good';

		}else{ 
			$res_array['list']['status']='error#'.__LINE__;
	}}else{
		$res_array['list']['status']='no_result';
		$res_array['clear']=true;
	}
	if($res_array['list']['status']==='good'){
			
		$res_array['status']='good';
		$res_array['is_next']=$is_next;
		$res_array['next_load_step']=$next_load_step;
		$res_array['content_status']=$type_of_rects;
		
		$res_array['url']='/?profile='.$user_id.'&content_status='.$type_of_rects;
		$res_array['user_id']=$user_id;
		
	}else if($res_array['list']['status']==='no_result'){
			
		$res_array['status']='good';
		$res_array['is_next']=false;
		$res_array['next_load_step']=0;
		$res_array['list']['interval']=0;
		$res_array['content_status']=$type_of_rects;
		
		$res_array['url']='/?profile='.$user_id.'&content_status='.$type_of_rects;
		$res_array['user_id']=$user_id;
		
	}else{
		$res_array['status']=$res_array['list']['status'];
	}
	return $res_array;
}

?>