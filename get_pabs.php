<?php

function file_get_contents_curl($url) {
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_URL, $url);
	$data = curl_exec($ch);
	curl_close($ch);
	return $data;
}

function get_pabs($link,$s_w_object,$type_of_pabs,$user_id,$pabs_compressed_string,$next_load_step,$need_fn){
	
	$res_array=array(); 
	
	$is_copies=($type_of_pabs==='copies');
	$is_pabs=($type_of_pabs==='pabs');
	$is_faves=($type_of_pabs==='faves');
		
	$pabs_q='SELECT id,title,path,date';
	
	if($is_copies){
		
		if($need_fn){
			$pabs_q.=',aut FROM  copywritings ';
			$keys=array('id','title','path','date','aut');
		}else{
			$pabs_q.=' FROM  copywritings ';
			$keys=array('id','title','path','date');
		}
		
	}else if($is_pabs){
		
		if($need_fn){
			$pabs_q.=',aut FROM  pablications ';
			$keys=array('id','title','path','date','aut');	
		}else{
			$pabs_q.=' FROM  pablications ';
			$keys=array('id','title','path','date');
		}
		
	}else if($is_faves){
		
		$need_fn=true;
		$pabs_q.=',aut FROM  pablications ';
		$keys=array('id','title','path','date','aut');
		
	}else{
		
		$pabs_q.=' FROM  pablications ';
		$keys=array('id','title','path','date');
	}
	$pabs_q.='WHERE id=';
	
	$pabs=json_decode(gzuncompress($pabs_compressed_string));
	$pabs_query='';
	$pabs_length=count($pabs);
	
	if($pabs_length!==0){
		
		$portion=48;$is_next=false;
		$start_point=($next_load_step===-1)?$pabs_length-1:$next_load_step;
		if($start_point>$portion){
			$is_next=true;
			$end_point=$start_point-$portion+1;
		}else if($start_point===$portion){
			$end_point=0;
		}else{
			$end_point=0;
		}
		
		$next_load_step=$end_point-1;
		if($start_point===$pabs_length-1)
		$res_array['clear']=true;
		else $res_array['clear']=false;
		for($i=$start_point;$i>=$end_point;$i--){
			$new=$pabs[$i]*1;
			$pabs_query.=$pabs_q.$new.' LIMIT 1;';
		}

		if($res_pab_array=$s_w_object->multi_query_in_new_array(
		$link,
		$pabs_query,
		$keys
		)){
			$res_array['pabs']=$res_pab_array;
			$res_array['pabs']['status']='good';
			
			$pabs_count=$res_pab_array['interval'];
			
			for($i=0;$i<$pabs_count;$i++){ 
				$res_array['pabs'][$i]['content']=file_get_contents_curl('paper-blog.ru/mobile_'.$res_pab_array[$i]['path']);
			}
			
			if($need_fn&&$pabs_count!==0){
				$fn_q='SELECT name,fname FROM ussd WHERE id=';
				$fn_query='';
				for($i=0;$i<$pabs_count;$i++){
					$fn_query.=$fn_q.($res_pab_array[$i]['aut']*1).' LIMIT 1;';
				}
				if($res_aut_array=$s_w_object->multi_query_in_new_array(
				$link,
				$fn_query,
				array('name','fname')
				)){
					$res_array['auts']=$res_aut_array;
					$res_array['auts']['status']='good';
				}else{
					$res_array['auts']['status']='error#'.__LINE__;
				}
			}}else{
				$res_array['pabs']['status']='error#'.__LINE__;
		}}else{
			$res_array['pabs']['status']='no_result';
			$res_array['clear']=true;
	}
		
	if(((
	$res_array['pabs']['status']==='good')
	&&($res_array['auts']['status']==='good'))
	||(($res_array['pabs']['status']==='good')
	&&!$need_fn
	)){
			
		$res_array['status']='good';
		$res_array['is_next']=$is_next;
		$res_array['next_load_step']=$next_load_step;
		$res_array['content_status']=$type_of_pabs;
		
		$nf=($need_fn)?1:0;
		$res_array['url']='/?profile='.$user_id.'&content_status='.$type_of_pabs.'&need_fn='.$nf;
		$res_array['user_id']=$user_id;
		$res_array['need_fn']=$need_fn;
			
	}else if($res_array['pabs']['status']==='no_result'){
		
		$res_array['status']='good';
		$res_array['is_next']=false;
		$res_array['next_load_step']=0;
		$res_array['pabs']['interval']=0;
		$res_array['content_status']=$type_of_pabs;
		if($need_fn)
		$res_array['auts']['interval']=0;
		
		$nf=($need_fn)?1:0;
		$res_array['url']='/?profile='.$user_id.'&content_status='.$type_of_pabs.'&need_fn='.$nf;
		$res_array['user_id']=$user_id;
		$res_array['need_fn']=$need_fn;
		
	}else{ 
		$res_array['status']=$res_array['pabs']['status'].'->'.$res_array['auts']['status'];
	}
	return $res_array;
} 

?>