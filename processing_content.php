<?php 
function processing_content($content,$title){
	$start=microtime(true);
	$dom = new DOMDocument();
	$dom->loadHTML("<html><head><meta http-equiv='content-type' content='text/html; charset=utf-8'></head><body>".$content."</body></html>");
	$tags=$dom->getElementsByTagName('*');
	
	$stream_elms=array();
	$stream_elms_mt=array();
	$stream_elms_ml=array();
	$stream_count=0;
	
	foreach($tags as $t){
		
		$node_name=strtolower($t->nodeName);
		$style_val=null;	
		$stream_count_str='stream_'.strval($stream_count);
		
		$is_NPTABLE=false;
		$is_NPTEXT=false;
		$is_NPIMAGE=false;
		$is_NPQUOTE=false;
		$is_NPMOVIE=false;
		$is_YT_iframe=false;
		$is_tag_a=($node_name==='a');
		$is_table_class_elm=false;
		$is_to_continue=true;
		
		if($node_name==='script'){
			$t->parentNode->removeChild($t);
			$is_to_continue=false;
		}else if($node_name==='meta'){
			$t->parentNode->removeChild($t);
			$is_to_continue=false;
		}else if($node_name==='iframe'){
			$iframe_src=$t->getAttribute('src');
			$reg='/www.youtube.com/iu';
			if(preg_match($reg, $iframe_src)){
				$nt=$dom->createElement('div');
				$nt_class=$dom->createAttribute('class');
				$nt_class->value='youtube';
				$nt->appendChild($nt_class);
				$nt_id=$dom->createAttribute('id');
				$youtube_code=explode('//www.youtube.com/embed/', $iframe_src);
				$youtube_code=explode('?',$youtube_code[1]);
				$youtube_code=$youtube_code[0];
				$nt_id->value=$youtube_code;
				$nt->appendChild($nt_id);
				$nt_style=$dom->createAttribute('style');
				$nt_style->value=$t->getAttribute('style');
				$nt->appendChild($nt_style);
				$t->parentNode->replaceChild($nt,$t);
				$is_YT_iframe=true;
			}else{
				$t->parentNode->removeChild($t);
				$is_to_continue=false;
			}
		}else if(($node_name==='table')||($node_name==='tbody')||($node_name==='tr')||($node_name==='td')){
			$is_table_class_elm=true;
		}else{
				
			$classList=$t->getAttribute('class');
			
			if(!empty($classList)){
				$class_array=explode(' ',$classList);
				$class_array_count=count($class_array);
				for($i=0;$i<$class_array_count;$i++){
					if($class_array[$i]==='NPTABLE'){
						$is_NPTABLE=true;
						break;
					}else if($class_array[$i]==='NPTEXT'){
						$is_NPTEXT=true;
						break;
					}else if($class_array[$i]==='NPIMAGE'){
						if(!is_array(getimagesize($t->getAttribute('src')))){
							$t->parentNode->removeChild($t);
							$is_to_continue=false;
						}else{
							$is_NPIMAGE=true;
							$stream_elms[$stream_count_str]=clone $t;	
						}
						break;
					}else if($class_array[$i]==='NPQUOTE'){
						$is_NPQUOTE=true;
						break;
					}else if($class_array[$i]==='NPMOVIE'){
						$is_NPMOVIE=true;	
						break;
					}else if($class_array[$i]==='youtube'){
						$is_YT_iframe=true;	
						break;
					}
				}
            }
            
            if($is_to_continue){
            		
            	$good_mobile_tag=
				$is_NPTABLE||
				$is_NPTEXT||
				$is_NPIMAGE||
				$is_NPQUOTE||
				$is_NPMOVIE;
				
				$good_tag=
				$is_table_class_elm||
				$is_YT_iframe||
				$good_mobile_tag;
				
				foreach($t->attributes as $attrName => $attrNode){
					if(
					((($attrName!=='color')&&
                    ($attrName!=='face')&&
                    ($attrName!=='src')&&
                    ($attrName!=='href')&&
                    ($attrName!=='style')&&
                    ($attrName!=='class')&&
					($attrName!=='id'))
					)){
						$t->removeAttribute($attrName);
                 	}else if(($attrName==='href'&&!$is_tag_a)||
                 	(($attrName==='src')&&(!($is_NPIMAGE||$is_YT_iframe)))){
						$t->removeAttribute($attrName);
					}
		       }
				
				$style_val=$t->getAttribute('style');
				if(!is_null($style_val)){
						
					$style_val=str_replace('; ', ';', $style_val);
					$style_val=str_replace(': ', ':', $style_val);
					$style_array=explode(';',$style_val);
					$style_array_count=count($style_array);
					$new_style=$dom->createAttribute('style');		
					$new_style_val='';
					
					for($i=0;$i<$style_array_count;$i++){
						if(!empty($style_array[$i])){
							$key_value=explode(':',$style_array[$i]);
							$key=$key_value[0];$value=$key_value[1];
							if($key==='cursor'){
								if($t->hasAttribute('href')){
									$new_style_val.='cursor:pointer;';
								}else if($is_NPTEXT){
									$new_style_val.='cursor:default;';
								}else if(!$is_table_class_elm){
									$new_style_val.='';
								}
							}else if(($key==='border')&&!$is_NPTABLE&&!$is_NPQUOTE&&!$is_table_class_elm){
								$new_style_val.='border:0px;';
							}else{
								if($good_tag){
									if($key==='height'&&$is_NPMOVIE)
									$new_style_val.='height:250px;';
									else if($key==='height'&&$is_NPTEXT)
									$new_style_val.='height:auto;';
									else if(!($key==='min-height'&&$is_NPTEXT)) $new_style_val.=$key.':'.$value.';';
								}
							}
						}
					}
					
					$new_style_val=str_replace(';;', ';', $new_style_val);
					$new_style->value=$new_style_val;
					$t->appendChild($new_style);

					if($good_mobile_tag){
						
						$stream_elms[$stream_count_str]=$t->cloneNode(true);
						$style_val=$stream_elms[$stream_count_str]->getAttribute('style');
						$style_array=explode(';',$style_val);
						$style_array_count=count($style_array);
						$new_mobile_style=$dom->createAttribute('style');
						$new_mobile_style_val='';
						
						if($is_NPMOVIE){
							
							$fenpm=$stream_elms[$stream_count_str]->childNodes->item(0);
							if(strtolower($fenpm->nodeName)==='iframe'){
								$iframe_src=$fenpm->getAttribute('src');
								$nt=$dom->createElement('div');
								$nt_class=$dom->createAttribute('class');
								$nt_class->value='youtube';
								$nt->appendChild($nt_class);
								$nt_id=$dom->createAttribute('id');
								$youtube_code=explode('//www.youtube.com/embed/', $iframe_src);
								$youtube_code=explode('?',$youtube_code[1]);
								$youtube_code=$youtube_code[0];
								$nt_id->value=$youtube_code;
								$nt->appendChild($nt_id);
								$nt_style=$dom->createAttribute('style');
								$nt_style->value=$fenpm->getAttribute('style');
								$nt->appendChild($nt_style);
								$fenpm->parentNode->replaceChild($nt,$fenpm);
								$is_YT_iframe=true;
								
							}
							
							for($i=0;$i<$style_array_count;$i++){
								if(!empty($style_array[$i])){
									$key_value=explode(':',$style_array[$i]);
									$key=$key_value[0];$value=$key_value[1];
									if($key==='position')
									$new_mobile_style_val.='position:static;';
									else if($key==='margin-top'){
										$stream_elms_mt[$stream_count_str]=$value*1;
									}else if($key==='margin-left'){
										$stream_elms_ml[$stream_count_str]=$value*1;
									}else if(
									$key!=='margin-left'&&
									$key!=='margin-top'&&
									$key!=='width'&&
									$key!=='height'&&
									$key!=='float')
									$new_mobile_style_val.=$key.':'.$value.';';
								}
							}
						}else if($is_NPQUOTE||$is_NPTABLE){
							for($i=0;$i<$style_array_count;$i++){
								if(!empty($style_array[$i])){
									$key_value=explode(':',$style_array[$i]);
									$key=$key_value[0];$value=$key_value[1];
									$new_mobile_style_val.='overflow:auto;';
									if($key==='position')
									$new_mobile_style_val.='position:static;';
									else if($key==='display'){
										if($is_NPTABLE)
										$new_mobile_style_val.='display:block;';
										else $new_mobile_style_val.='display:table;';
									}
									else if($key==='margin-top'){
										$stream_elms_mt[$stream_count_str]=$value*1;
									}else if($key==='margin-left'){
										$stream_elms_ml[$stream_count_str]=$value*1;
									}else if(
									$key!=='max-width'&&
									$key!=='display'&&
									$key!=='overflow'&&
									$key!=='float')
									$new_mobile_style_val.=$key.':'.$value.';';
								}
							}
						}else if($is_NPIMAGE){
							for($i=0;$i<$style_array_count;$i++){
								if(!empty($style_array[$i])){
									$key_value=explode(':',$style_array[$i]);
									$key=$key_value[0];$value=$key_value[1];
									if($key==='height')
									$new_mobile_style_val.=$key.':'.$value.';';
									else if($key==='position')
									$new_mobile_style_val.='position:static;';
									else if($key==='margin-top'){
										$stream_elms_mt[$stream_count_str]=$value*1;
									}else if($key==='margin-left'){
										$stream_elms_ml[$stream_count_str]=$value*1;
									}else if(
									$key!=='max-width'&&
									$key!=='float')
									$new_mobile_style_val.=$key.':'.$value.';';
                                   }
							}
						}else if($is_NPTEXT){
							for($i=0;$i<$style_array_count;$i++){
								if(!empty($style_array[$i])){
									$key_value=explode(':',$style_array[$i]);
									$key=$key_value[0];$value=$key_value[1];
									if($key==='height')
									$new_mobile_style_val.='height:auto;';
									else if($key==='position')
									$new_mobile_style_val.='position:static;';
									else if($key==='margin-top'){
										$stream_elms_mt[$stream_count_str]=$value*1;
									}else if($key==='margin-left'){
										$stream_elms_ml[$stream_count_str]=$value*1;
									}else if($key==='width')$new_mobile_style_val.=$key.':'.$value.';';
									else if(
									$key!=='max-width'&&
									$key!=='width'&&
									$key!=='float')
									$new_mobile_style_val.=$key.':'.$value.';';
								}
							}
						}
						if(!isset($stream_elms_mt[$stream_count_str])){
							$stream_elms_mt[$stream_count_str]=0;
						}
						if(!isset($stream_elms_ml[$stream_count_str])){
							$stream_elms_ml[$stream_count_str]=0;
						}
						$stream_count+=1;
                        $new_mobile_style_val=str_replace(';;', ';', $new_mobile_style_val);
                        $new_mobile_style->value=$new_mobile_style_val;
                        $stream_elms[$stream_count_str]->appendChild($new_mobile_style);
                    }
                    
                }
				 
				  
              }
           }
         }
         
         $script=$dom->createElement('script');
         $type=$dom->createAttribute('type');
         $src=$dom->createAttribute('src');
         $type->value='text/javascript';
         $src->value='http://paper-blog.ru/java_script/redirect_pab.js';
         $script->appendChild($type);
         $script->appendChild($src);
         $dom->appendChild($script);
         
         $head=$dom->getElementsByTagName('head')->item(0);
         $ptitle=$dom->createElement('title','Paper Blog | '.$title);
         $head->appendChild($ptitle);
		 
		 $processing_content=$dom->saveHTML();
		 
		 array_multisort($stream_elms_mt,$stream_elms_ml,$stream_elms);
		 
		 $processed_mobile_content=new DOMDocument();
		 $html_tag=$processed_mobile_content->createElement('html');
		 $processed_mobile_content->appendChild($html_tag);
		 $head=$processed_mobile_content->createElement('head');
		 $mptitle=$processed_mobile_content->createElement('title','Paper Blog | '.$title);
		 $head->appendChild($mptitle);
		 $html_tag->appendChild($head);
		 $body_tag=$processed_mobile_content->createElement('body');
		 $html_tag->appendChild($body_tag);
		 $processed_mobile_content->formatOutput = true;
		 foreach ($stream_elms as $key => $value) {
		 	$node = $processed_mobile_content->importNode($value, true);
		 	$body_tag->appendChild($node);
		 }
		 $script=$processed_mobile_content->createElement('script');
		 $type=$processed_mobile_content->createAttribute('type');
		 $src=$processed_mobile_content->createAttribute('src');
		 $type->value='text/javascript';
		 $src->value='http://paper-blog.ru/java_script/redirect_pab.js';
		 $script->appendChild($type);
		 $script->appendChild($src);
		 $processed_mobile_content->appendChild($script);
		 
		 $processing_mobile_content=$processed_mobile_content->saveHTML();
		 $time=microtime(true)-$start;
		 return array($processing_content,$processing_mobile_content,$stream_elms_mt,$time);
}
?>