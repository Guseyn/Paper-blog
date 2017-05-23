<?php
include('filter_post_array.php');
$post_array=json_decode(urldecode($_POST['to_search_choose']));
$post_array=check_filter_input($post_array);

if(isset($post_array)&&$post_array){
	unset($_POST['to_search_choose']);
	$ajax_response=array();
	include('sql_lib.php');
	$s_w=new sql_work();
	if($NP=$s_w->connect()){
			
		function remove_whitespace($string){
			$string = preg_replace('/ {2,}/',' ',$string);
			$string = trim($string);
			return $string;
		}
		
		mysqli_autocommit($NP, false);

		$type_of_search=strip_tags($post_array->type_of_search);
		$search_str=remove_whitespace($search_str);
		$search_str=addslashes($post_array->search_input);
		
		$is_title_search=($type_of_search==='title_search');
		$is_aut_search=($type_of_search==='aut_search');
		if($is_title_search||$is_aut_search){
     	
     	if($search_str!==""){
     		
     		$words=explode(' ',$search_str);$count=count($words);$query='';$types='';$params=array();
			if($is_title_search)
     		$query="SELECT DISTINCT title FROM pablications WHERE isreadable=1 AND (title LIKE '%".$search_str."%' ";
			else if($is_aut_search)
			$query="SELECT DISTINCT name,fname FROM ussd WHERE isable=1 AND (CONCAT_WS(' ',name,fname) LIKE '%".$search_str."%' ";
     		if($count>1){
     			for($i=0;$i<$count;$i++){
     				$word=$words[$i];
     				$query.=($is_title_search)?" OR title LIKE '%".$word."' ":" OR CONCAT_WS(' ',name,fname) LIKE '%".$word."' ";
				}
			}
			
			$portion=8;
			$next_load_step=$post_array->next_load_step;
			
			$query.=($is_title_search)?') ORDER BY lq DESC,vq DESC,id DESC LIMIT 0,?;':') ORDER BY fq DESC,pq DESC,id DESC LIMIT 0,?;';
			$params=array($portion);
			$types='i';
			
			 if($answer=$s_w->get_info_in_new_array($NP,$query,$params,$types)){
			 	$res_array=array();
				for($i=0;$i<$answer['interval'];$i++){
					if($is_title_search)
					$res_array[$i]=$answer[$i]['title'];
					else $res_array[$i]=$answer[$i]['name'].' '.$answer[$i]['fname'];
				}
				$ajax_response['interval']=$answer['interval'];
				$ajax_response['result']=$res_array;
				$ajax_response['status']='good';
			 }else{
			 	$ajax_response['interval']=0;
				$ajax_response['result']=array();
				$ajax_response['status']='good';
			 }
     	}
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