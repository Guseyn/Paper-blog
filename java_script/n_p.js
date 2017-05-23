var ua=window.navigator.userAgent.toLowerCase(),
WebGod=/mozilla\/5.0/.test(ua),
mial=false,
ie=!!top.execScript,
mial=false,
id={},
no_sess_func=function(){},
not_able_func=function(){},

web_drivers={
	
	mobile: /iphone|ipod|ipad|opera mini|opera mobi|iemobile|android/i.test(ua),
	latest_Chrome_and_Safari:(/applewebkit/.test(ua) && WebGod),
	latest_Firefox:(/firefox\/2/.test(ua) && WebGod),
	latest_Opera:(/presto/.test(ua) && !WebGod),
	IE10_and_more:(/trident\/6.0/.test(ua) && WebGod)
	
},

g={
	
	i:function(e){
		return document.getElementById(e);
	},
	
    c:function(c){
		return document.getElementsByClassName(c);
	},
	
	ec:function(elm,c){
		return elm.getElementsByClassName(c);
	},
	
	t:function(t){
		return document.getElementsByTagName(t);
	},
	
	dt:function(elm,t){
		return elm.getElementsByTagName(t);
	},
	
	et:function(elm){
		return elm.getElementsByTagName('*');
	},
	
	n:function(n){
		return document.getElementsByName(n);
	}

},


regs={
	
	url:function(url){
		return  /^(https?:\/\/)?([\w\.]+)\.([a-z]{2,6}\.?)(\/[\w\.]*)*\/?$/.test(url);
	},
	
	error:function(str){
		return /error#/.test(str);
	}
	
},

effects={
	
	show:function(elm){
		var l=arguments.length;
		if(l>1){
			for(i=0;i<l;i++){
				effects.show(arguments[i]);
			}
			return;
		}
		if(!elm || !elm.style)return;
		var tt=elm.tagName.toLowerCase();
		if(tt==='table')
		elm.style.display='table';
		else if(tt==='tr')
		elm.style.display='table-row';
		else if(tt==='td')
		elm.style.display='table-cell';
		else if(tt==='div')
		elm.style.display='block';
		else
		elm.style.display='inline-block';
	},
	
	hide:function(elm){
		var l=arguments.length;
		if(l>1){
			for(i=0;i<l;i++){
				effects.hide(arguments[i]);
			}
			return;
		}
		if(!elm || !elm.style)return;
		elm.style.display='none';
	},

	text_color:function(c){
		document.execCommand( 'forecolor', false, c);
	},
	
	src:function(img,src){
		img.setAttribute('src',src);
	},
	
	cross_opacity:function(elm,ind){
		elm.style.opacity=ind;
		elm.style.filter="alpha(opacity="+ind*100+")";
	},
	
	disable:function(elms){
		l=elms.length;
		for(i=0;i<l;i++){
			elms[i].disabled=(elms[i].disabled)?false:true;
		}
	},
	
	smooth:function(elm,index){
		if(mial){
			clearInterval(mial);
		}
		var opa_i=(index)?0.9:-0.1;
		setTimeout(
			function(){
				var mial=setInterval(
				function(){
					effects.cross_opacity(elm,opa_i);
					opa_i=(index)?opa_i-0.1:opa_i+0.1;
					if((opa_i<-1)||(opa_i>1)){
						clearInterval(mial);
					}
			},100)
		},1000);
	},
	
	hidding_message:function(elm,mess){
		effects.show(elm);
		elm.innerHTML=mess;
		effects.cross_opacity(elm,1);
		effects.smooth(elm,true);
	},

	switch_images:function(img,src1,src2){
		var s=img.getAttribute('src');
		if(s===src1){
			effects.src(img,src2);
		}else{
			effects.src(img,src1);
		}
	},
	
	switch_bimages:function(bimg,src1,src2){
		var s=bimg.style.backgroundImage;
		if(s===src1){
			bimg.style.backgroundImage=src2;
		}else{
			bimg.style.backgroundImage=src1;
		}
	},
	
	ajax_sw_img:function(elm){
		if(elm){
			if(elm.classList.contains('ajax_img')){
				design.change_class(elm,'ajax_img','ajax_stop_img');
			}else if(elm.classList.contains('ajax_stop_img')){
				design.change_class(elm,'ajax_stop_img','ajax_img');
			}	
		}
	},
	
	ajax_sw_img_stop:function(){
		var imgs=g.t('div'),l=imgs.length;
		for(i=0;i<l;i++){
			if(imgs[i].classList.contains('ajax_img')){
				design.change_class(imgs[i],'ajax_img','ajax_stop_img');
			}else if(imgs[i].classList.contains('open_pab_ajax_img')){
				design.change_class(elm,'open_pab_ajax_img','no_open_pab_ajax_img');
			}
		}
	},
	
	ajax_sw_open_img:function(elm){
		if(!elm)return;
		if(elm.classList.contains('open_pab_ajax_img')){
			design.change_class(elm,'open_pab_ajax_img','no_open_pab_ajax_img');
		}else{
			design.change_class(elm,'no_open_pab_ajax_img','open_pab_ajax_img');
		}	
	},
	
	switch_classes_in_elms:function(elms,class1,class2,index){
		var l=elms.length;
		for(i=0;i<l;i++){
			if(i===index){
				design.change_class(elms[i],class1,class2);
			}else{
				design.change_class(elms[i],class2,class1);
			}
		}
	}
		
},

design={
	
	setStyles:function(elm,style_obj){
		for(var s in style_obj){
			elm.style[s]=style_obj[s];
		}
	},
	
	change_class:function(elm,class1,class2){
		if(elm.classList.contains(class1)){
			elm.classList.remove(class1);
		};//to remove
		if(!elm.classList.contains(class2)){
			elm.classList.add(class2);//to add
		}
	}
	
},

modal={

	close:function(win){
		var l=arguments.length;
		if(l>1){
			for(i=0;i<l;i++){
				this.close(arguments[i]);
			}
			return;
		}
		win.style.left='-3000px';
		win.style.top='-3000px';
	},

	open_center:function(win,bh,bw,body){
		var w=win.offsetWidth,
		h=win.offsetHeight;
		if(bw>w+20){
			win.style.left=Math.round((bw-w)/2)+body.scrollLeft+'px';
		}else{
			win.style.left=body.scrollLeft+'px';
		}
		if(bh>h+20){
			win.style.top=Math.round((bh-h)/2)+body.scrollTop+'px';
		}else{
			win.style.top=body.scrollTop+'px';
		}
	},
	
	open_fix:function(win,move,close,bh,bw,body){
		modal.open_center(win,bh,bw,body);
		win.style.display="block";
		move.onmouseover=function(){
			tricks.dragdrop(win,body);
		};
		move.onmouseout=function(){
			tricks.notricks();
		};
		close.onclick=function(){
			modal.close(win);
		};
		close.onmousedown=function(){
			tricks.notricks();
		};
	},
	
	open_free:function(event,win,move,close,body){
		var e=event||window.event;
		win.style.top=e.clientY+npbody.scrollTop;
		win.style.left=e.clientX+npbody.scrollLeft;
		win.style.display="block";
		move.onmouseover=function(){
			tricks.dragdrop(win,body)
		};
		move.onmouseout=function(){
			tricks.notricks()
		};
		close.onclick=function(){
			modal.close(win)
		};
		close.onmousedown=function(){
			tricks.notricks()
		};
	},
		
	just_open:function(win,y,x,move,close,body){
		win.style.top=y;win.style.left=x;
		win.style.display="block";
		move.onmouseover=function(){
			tricks.dragdrop(win,body)
		};
		move.onmouseout=function(){
			tricks.notricks()
		};
		close.onclick=function(){
			id.layer.style.zIndex='1000';
			if(nohideall)
			nohideall=false; 
			modal.close(win);
		};
		close.onmousedown=function(){
			tricks.notricks()
		}
	},
	
	eo:function(event,win){
		var e=event||window.event;
		win.style.top=e.clientY+npbody.scrollTop;
		win.style.left=e.clientX+npbody.scrollLeft;
		win.style.display="block";
	}
},

errors={
	
	form :function(elms){
		if(!elms||!elms.length)return;
		var r=220,g=65,b=35,l=elms.length,sid;
		for(i=0;i<l;i++){
			id[elms[i]].style.border="1px rgb("+r+","+g+","+b+") solid";
		}
		if(sid){
			clearInterval(sid);
		}
		sid=setInterval(
			function(){
				r-=10,g+=28,b+=35;
				for(i=0;i<l;i++){
					id[elms[i]].style.border="1px rgb("+r+","+g+","+b+") solid";
				}
				if(r===180)clearInterval(sid);
			},250);	
	},

	corner_div:function(div,status){
		div.style.display='block';
		div.style.background='rgb(220,65,35)'
		div.innerHTML=(status)?status:'Unknown error';
	}
	
},

system={
	
	/*DOM*/
	domStart:function(id_obj,elm){
		var e=g.i(elm);
		id_obj[elm]=e;
		var tags=g.et(elm),
		tlen=tags.length;
		for(i=0;i<tlen;i++){
			try{
				var id=tags[i].getAttribute('id');
				id_obj[id]=g.i(id);
			}catch(e){}
		}
		return id_obj;
	},
	
	clean_shit:function(elm,ind){
		var shittags=g.et(elm),
		stlen=shittags.length;
		for(i=0;i<stlen;i++){
			var si=shittags[i],statlen=si.attributes.length;
			for(j=0;j<statlen;j++){
				if(si!='[object HTMLTableCellElement]'
				&& si!='[object HTMLTableRowElement]'
				&& si!='[object HTMLTableElement]'
				&& si!='[object HTMLTableSectionElement]'
				&& si!='[object HTMLTableDataCellElement]'){
					if(si.attributes[j]
						&& si.attributes[j].name!=="color"
						&& si.attributes[j].name!=="href"
						&& si.attributes[j].name!=="face"){
							si.removeAttribute(si.attributes[j].name);
					}
				}
				if(si=='[object HTMLButtonElement]'
				||si=='[object HTMLInputElement]'
				||si=='[object HTMLTextAreaElement]'){
					var sip=si.parentElement || si.parentNode;
					if(sip){
						sip.removeChild(si);
						stlen--;i--;
					}
				}
			}
		}
	},
		
	remove_scripts:function(elm){
		var script_tags=g.dt(elm,"SCRIPT"),
		len=script_tags.length;
		for(i=0;i<len;i++){
			elm.removeChild(script_tags[i]);
			i--;len--;
		}
	},
		
	defaultelms:function(elms){
		var tags=g.et(elms),stlen=tags.length;
		for(i=0;i<stlen;i++){
			var t=tags[i];t.style.cursor='default';
			if(t=='[object HTMLDivElement]'){
				if(!t.classList.contains('NPTABLE')&&!t.classList.contains('NPQUOTE')){
					t.style.border='0px';
					t.setAttribute('contenteditable','false');
					if(t.classList.contains('NPMOVIE')){
						t.style.height="250px";
						t.style.width="300px";
						if(t.firstChild.style.marginTop!=='0px'&&t.firstChild.style.marginLeft!=='0px'){
							t.style.marginTop=t.style.marginTop.split('px')[0]*1+35+'px';
							t.style.marginLeft=t.style.marginLeft.split('px')[0]*1+35+'px';
							t.firstChild.style.marginTop='0px';
							t.firstChild.style.marginLeft='0px';
						}
					}
				}else {
					system.for_ie_and_ff(t,t.getElementsByTagName('table')[0],"false");
				}
			}
		}
	},
	
	content_size:function(elm){
		var tags=g.et(elm),
		len=tags.length,
		max=0,
		ne;
		for(i=0;i<len;i++){
			var ti=tags[i];class_name=false;
			try{
				var class_name=ti.getAttribute('class');
			}catch(e){}
			if(class_name){
				if(ti.classList.contains('NPTEXT')||
				ti.classList.contains('NPQUOTE')||
				ti.classList.contains('NPIMAGE')||
				ti.classList.contains('NPTABLE')||
				ti.classList.contains('NPMOVIE')){
					var present_bottom=ti.style.marginTop.split('px')[0]*1+ti.offsetHeight;
					if(ti.classList.contains('NPTEXT'))
					present_bottom+=4;
					if(present_bottom>=max){
						max=present_bottom;
						ne=ti;
					}
				}
			}
		}
		return max;
	},

	for_ie_and_ff:function(elm1,elm2,boolindex){
		if(web_drivers.latest_Firefox){
			elm2.setAttribute('contenteditable',boolindex);
		}else{
			elm1.setAttribute('contenteditable',boolindex);
			elm2.setAttribute('contenteditable',boolindex);
		}
	},
	
	/*EVENTS*/
	add_event :function(elm, type, handler){
		if(elm.addEventListener){
			elm.addEventListener(type, handler, false);
		}else{
			elm.attachEvent("on"+type, handler);
		}
	},
	
	remove_event:function(elm, type, handler){
		if(elm.removeEventListener){
			elm.removeEventListener(type, handler, false);
		}else{
			elm.detachEvent("on"+type, handler);
		}
	},
	
	add_event_to_elms:function(elms,type,handler){
		var l=elms.length;
		for(i=0;i<l;i++){
			system.add_event(elms[i],type,handler);
		}
	},
	
	create_mouse_event:function(elm,type){
		var ev=document.createEvent('MouseEvents');
		ev.initEvent(type, true, true);
		elm.dispatchEvent(ev);
	},
		
	only_to_child:function(event){
		e = event || window.event;
		e.stopPropagation
		?e.stopPropagation()
		:(e.cancelBubble=true);
	},
	
	/*VALUES&datas*/
	return_obj_to_val:function(obj,val){
		for(var i in obj){
			obj[i]=val;
		}
	},
	
	in_array:function(a,elm){
		return (a.indexOf(elm)!= -1);
	},
	
	splash_html:function(obj){
		for(var elm in obj){
			id[elm].innerHTML=obj[elm];
		}
	},
	
	splash_attrs:function(attr,obj){
		for(var elm in obj){
			id[elm].setAttribute(attr,obj[elm]);
		}
	},
	
	splash_style:function(property,obj){
		for(var elm in obj){
			id[elm].style[property]=obj[elm];
		}
	},
	
	splash_event_to_elms:function(type_of_event,func,elms){
		var l=elms.length;
		for(i=0;i<l;i++){
			elms[i][type_of_event]=function(){
				func();
			}	
		}
	},
	
	insert_char:function(ch){
		var txt='';
		txt=window.getSelection();
		txt.collapseToEnd();
		document.execCommand('insertHtml',false,ch);
	},
	
	getek:function(event){
		return event.keyCode||event.which;
	},
	
	getec:function(event){
		return event.charCode;
	},
	
	enter:function(e){
		return (this.getek(e)===13);
	},
	
	up_key:function(e){
		return (this.getek(e)===38);
	},
	
	down_key:function(e){
		return (this.getek(e)===40);
	},
	
	replace_char:function(elm,code,ch){
		elm.onkeypress=function(e){
			if(this.getek(e)===code){
				system.insert_char(ch);
				return false;
			}
		}
	},
	
	getchar:function(event) {
		if (event.which == null) {
			if (event.keyCode < 32) return null;
			return String.fromCharCode(event.keyCode)
		}else if (event.which!=0 && event.charCode!=0){
			if (event.which < 32) return null;
			return String.fromCharCode(event.which);
		}
		return null;
	},

	create_send_obj_from_form:function(form){
		var input_tags=g.et(form),
		l=input_tags.length,
		form_values={};
		for(i=0;i<l;i++){
			if((input_tags[i].value!==undefined)&&(input_tags[i].nodeName.toLowerCase()!=='button')&&(!input_tags[i].classList.contains('no_read'))){
				form_values[input_tags[i].getAttribute('id')]=input_tags[i].value;
			}
		}
		return form_values;
	},
	
	html_to_text:function(text){
		return text
		.replace(/&/g, "&amp;")
		.replace(/</g, "&lt;")
		.replace(/>/g, "&gt;")
		.replace(/"/g, "&quot;")
		.replace(/'/g, "&#039;");
	},
		
	strip_tags:function(text){
		return text
		.replace(/</g, "")
		.replace(/>/g, "")
		.replace(/"/g, "")
		.replace(/'/g, "");
	},
		
	no_s_char:function(elm){
		elm.onkeypress=function(e){
			e = e || window.event;
			var charable=system.getchar(e)*1;
			if(isNaN(charable)){
				return false
			};
		}
	},
	
	int_sw:function(elms){
		var l=elms.length
		for(i=0;i<l;i++){
			elms[i].onkeypress=function(e){
				e = e || window.event;
				var charable=system.getchar(e)*1;
				if(isNaN(charable)||this.value>255){
					return false;
				};
			}
			elms[i].onkeyup=function(){
				if(this.value>255){
					this.value=255;
				}
			}
		}
	},
	
	ds:function(ints,sb,ind,bi){
		l=ints.length;for(i=0;i<l;i++){
			ints[i].disabled=bi;
			if(ind)ints[i].value = ""; 
		}sb.disabled=bi;
	}
},

cookie={
	
	set:function(name,value,path,domain,expires,secure){
		var s="";s+=name+"="+value;
		if(path!="")s+=";path="+path;
		if(domain!="")s+=";domain="+domain;
		var de =new Date();de.setDate(expires+de.getDate());
		s+=";expires="+de.toGMTString();
		if(secure!="")s+=";secure="+secure;
		document.cookie=s;
	},
	
	get:function(name){
		var c= document.cookie,res;c=c.split(" ");
		l=c.length;for(var i=0;i<l;i++){
			var s=c[i].split(";");
			var ss=s[0].split("=");
			if(ss[0]===name){
				res = ss[1];
			}else{
				res = false;
			}
		}
		return res;
	},
	
	remove:function(name){
		if(cookie.get(name)){
			cookie.set(name, "", "/","",-17,"");
		}
	}

},
	
ajax_obj=(function(){
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
						if(response_obj.status==='no_sess'){
							no_sess_func();
						}else if(response_obj.status==='not_able'){
							not_able_func();
						}else if(response_obj.status==='not_able_cookie'){
							effects.show(id.hm);
						}else{
							func(response_obj);
						}
					}else{
						ajax.fail();
					}
				}else{
					ajax.fail();
				} 
			}
		};
		ajax_obj.onloaded=function(){

		}
		ajax_obj.send(json_str);
	},
	
	ajax_box:function(div_box,button,url,act,is_async,response_func,add_params){
		var buttons=g.dt(div_box,'button'),
		buttons_len=buttons.length,
		buttons_aj=[],inputs=g.dt(div_box,'input'),
		inputs_len=inputs.length,k=0,j=0,block=false;
		inputs_enter_able=[];
		for(i=0;i<inputs_len;i++){
			if(inputs[i].classList.contains('enter_able')){
				inputs_enter_able[k]=inputs[i];k++;
			}
		}
		for(i=0;i<buttons_len;i++){
			if(!buttons[i].classList.contains('no_ajax')){
				buttons_aj[j]=buttons[i];j++;
			}
		}
		var elms=buttons_aj.concat(inputs);
		function func(){
			img=g.ec(div_box,'ajax_stop_img')[0];
			var inputs_values=system.create_send_obj_from_form(div_box),params=inputs_values;
			if(add_params.length!==0){
				for(i in add_params){
					var type_of=typeof(add_params[i]);
					if(type_of==="object"){
						var type=add_params[i].tagName.toLowerCase();
						if(type==="img"){
							params[i]=add_params[i].getAttribute('src');	
						}else if(type==="div"){
							params[i]=add_params[i].innerHTML;
						}else if(type==="table"||type==="tr"||type==="td"){
							params[i]=add_params[i].getAttribute('class');
						}else if(add_params[i].value){
							params[i]=add_params[i].value;
						}
					}
				}	
			}
			if(!block){
				block=true;
				effects.disable(elms);
				if(img){
					effects.ajax_sw_img(img);
				}
				ajax.post(is_async,url,act,params,
					function(obj){
						response_func(obj);
						if(img){
							effects.ajax_sw_img(img);
						}
					}
				);
				block=false;
				effects.disable(elms);
			}
		}
		button.onclick=function(){func()};
		system.add_event_to_elms(inputs_enter_able,'keyup',
		function(e){
			if((this.value!=='')&&(system.getek(e)===13)){
				func();	
			}
		})
	},
	
	fail:function(obj){
		try{
			effects.ajax_sw_img(h_obj.img);
		}catch(e){}
		effects.hidding_message(id.corner_error_display,"Undefined error");
	}
},

record_en={
	welcome:'Welcome to Paper Blog',
	title_pubs:'Publications',
	title_copies:'Achievmennts',
	title_faves:'Fave publications',
	title_readers:'Readers',
	title_authors:'Authors',
	dem:'Show WYSIWYG', 
	need_join:'You should sign in',
	load:'Load...',
	process:'Please, wait...',
	my_pabs:'My publications',
	my_copies:'My achievements',
	my_faves:'My fave publications',
	my_fans:'My readers',
	my_auts:'My authors',
	no_result:'There are no results for this query',
	no_result_pabs:'There are no published articles',
	no_result_copies:'There are no saved achievements',
	no_result_faves:'There are no fave publications',
	no_result_fans:"You don't have readers yet",
	no_result_auts:"You don't have authors yet",
	no_user_result_pabs:"User doesn't have published articles",
	no_user_result_faves:"User doesn't have fave publications",
	no_user_result_fans:"User doesn't have readers",
	no_user_result_auts:"User doesn't read anyone",
	no_result_auts:"You don't authors yet",
	remake_pab:'Edit publication',
	read_remarks:'Read remarks to this publication',
	remove_pab:'Remove this publication',
	add_to_elect:'Add to my fave publications',
	remove_from_elect:'Remove from my fave publications',
	to_remark:'Write or rewrite remark to this publication',
	to_complain:'Report this publication',
	need_authorizations:'You should sign in',
	add_to_auts:'Add user to my authors',
	remove_aut:'Remove user from my authors',
	make_pab:'<span>Publicate</span>',
	save_pab:'<span>Save in my achievements</span>',
        resave_pab:'<span>Save</span>',
	to_copy:'<span>Send in my achievements</span>',
	write_a_pub:'Write a publication',
	deleted:'Publication removed successfully',
	remarked:'Remark sent successfully',
	remark_deleted:'Remark removed successfully',
	no_remarks:'There are no ramarks to this publication',
	read_remarks:'Read remarks',
	clear_all:'Remove all remarks',
	pablicated:'Publicated successfully',
	saved_in_copies:'Saved successfully',
	transfered_in_copies:'Done successfully',
	saved:'Saved successfully',
	set_suc:'Done successfully',
	del_title:'Remove by pressing on the indentation of the field video',
	movie_title:'Drag by pressing on the indentation of the field video',
	changed_fpword:'Password recovered successfully',
	complained:'The complaint is accepted',
	feed:'Recently published',
	profile_not_found:'Profile not found',
	f_b_i:'Publicate',
	s_b_i:'Publication of the article',
	ff_b_i:'Save',
	ss_b_i:'Saving of the article',
	left_side:'By left side',
	right_side:'By right side',
	middle:'By center',
	open:'Open',
	access_error:'<span style="color:white;background:#454545;padding:5px;margin:6px 3px;float:left;">Access error</span>',
	not_able:'<span style="color:white;background:#454545;padding:5px;margin:6px 3px;float:left;">Account has been locked by the administrator</span>',
	ppic:'http://paper-blog.ru/img/ppic.png',
	ff:'http://paper-blog.ru/img/ff.png',
	pl:'http://paper-blog.ru/img/pl.png',
	pd:'http://paper-blog.ru/img/pd.png',
	ml:'http://paper-blog.ru/img/ml.png',
	md:'http://paper-blog.ru/img/md.png'
};
