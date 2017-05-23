<?php
function check_filter_input($object){
	if(is_object($object)){
		$res=false;
		foreach($object as $key=>$value){
			if(is_string($value)){
				if(ctype_digit($value)){
					$object->$key=intval($value*1);
				}else{
					$object->$key=htmlspecialchars($value);
				}	
			}else if(is_int($value)){
				$object->$key=$value*1;
			}else if(is_object($value)){
				$a=check_filter_input($object->$key);
				if(!$a){
					$res=$key;$object=false;break;
				}
			}else if(is_bool($value)){
				
			}else{
				$object=false;break;
			}
		}
	}else{
		$object=false;
	}

	return $object;
}
?>