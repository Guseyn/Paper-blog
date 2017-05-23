var ajax_obj=(function(){
	var ajax;
	if(window.ActiveXObject)
	ajax = new ActiveXObject("Microsoft.XMLHTTP");/*IE*/
	else ajax = new XMLHttpRequest();/*W3C*/
	return ajax;
})(),

ajax={
	
	common:function(){
		ajax_obj.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
		ajax_obj.setRequestHeader('Accept-Language', 'en');
		ajax_obj.setRequestHeader('Cache-Control', 'no-cache');
	},
	
	post_common:function(is_acync,url){
		ajax_obj.open('POST',url,is_acync);
		ajax.common();
	},
	
	create_json:function(send_obj_name,send_obj){
		try{
			var json_str=send_obj_name+'='+encodeURIComponent(JSON.stringify(send_obj));
		}catch(e){
			/*error*/	
		}
		return json_str;
	},
	
	post:function(is_acync,url,send_obj_name,send_obj,func){
		var json_str=ajax.create_json(send_obj_name,send_obj),
		response_obj=false;
		ajax.post_common(is_acync,url);
		ajax_obj.onreadystatechange=function(){
			if(ajax_obj.readyState===4){
				if(ajax_obj.status===200){
					try{
					    var ro=ajax_obj.responseText.split('for(;;);')[1];
						response_obj=JSON.parse(ro);
					}catch(e){
						response_obj=false;
						//unknow_error
					}
					if(response_obj){
						func(response_obj);
					}else{
						ajax.fail();
					}
				}else{
					ajax.fail();
				} 
			}
		};
		ajax_obj.send(json_str);
	},
	
	fail:function(obj){
		alert(obj.status);
	}
},

ua=window.navigator.userAgent.toLowerCase(),
mobile=/iphone|ipod|ipad|opera mini|opera mobi|iemobile|android/i.test(ua);

path=(/http:\/\/paper-blog.ru\/mobile_/i.test(window.location))
?window.location.toString().split('http://paper-blog.ru/mobile_')[1]
:window.location.toString().split('http://paper-blog.ru/')[1];

ajax.post(false,'redirect_pab/redirect_pab.php','to_redirect_pab',{path:path},
function(obj){
	if(obj.status==='good'){
		window.location=obj.url;
	}else{
		if(mobile)
		window.location='http://m.paper-blog.ru/';
		else window.location='http://paper-blog.ru/';
	}	
});
