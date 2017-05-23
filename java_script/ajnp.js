var id={},

need_fn=                            false,
if_enter_reg=                       false,
if_user_tools_able=                 false,
if_reg=                             false,
if_change_pword=                    false,
if_wysiwyg=                         false,
profile_user=                       'you',     //'you','not_you','no_np_user'
user_name=                          false,
your_user_name=                     false,
profile_user_id=                    0, 
content_status=                     'pabs', 
next_load_step=                     -1,
more_info_sw_index=                 0,
pabs_queue=                         [],
queue_index=                        0,
queue_present_index=                0,      
search_choose_queue=                [],
search_choose_queue_index=          -1,
history_obj_page_status=             {},
search_to_history=                  true,
auto_search=                        false,
tfs_sw_index=                       0,
tc_sw_index=                        0,
td_sw_index=                        0,
p_sw_index=                         0,
rc=                                 0,
wl=                                 window.location.toString(),
ajax_global_img_for_pabs_and_auts=  false,
ajax_global_img_for_open_pabs=      false,
search_input_div_clicked=           false,
first_pop_state=                    false,
OverClassParser=                    function(){},

no_sess=false,
start=false,
open_redactor=false,
open_pab=false,
open_settings=false,
open_a_proj=false,
from_remake=false,
is_saved_pab=false,
nohideall=false,

for_remake={
	pab_id:0,
	content_status:'pabs',
	title:'',
	pab:''
}

load_work={
	
	default_params:function(type){
		pabs_queue=[];
		queue_index=0;
		next_load_step=-1;
		need_fn=(type==='faves')?true:false;
	},
	
	default_you_params:function(type){
		load_work.default_params(type);
		profile_user='you';
		profile_user_id=0;
		if((type==='pabs')
		||(type==='copies')
		||(type==='faves'))
		load_work.load_pabs(0,type);
		else if((type==='fans')
		||(type==='auts'))
		load_work.load_auts_fans_list(0,type);
	},
	
	hide_static:function(){
		if(history_obj_page_status.user_id){
			history_api.set_profile(
				history_obj_page_status.user_id,
				history_obj_page_status.content_status,
				history_obj_page_status.need_fn,
				history_obj_page_status.url
			);
		}else if(history_obj_page_status.search_input){
			history_api.set_query(
				history_obj_page_status.search_input,
				history_obj_page_status.type_of_search,
				history_obj_page_status.url
			);
		}else if(history_obj_page_status.feed){
			history_api.set_feed(
				history_obj_page_status.url
			);
		}else{
			if(no_sess){
				load_work.feed();
			}else{
				load_work.load_pabs(0,'pabs');
			}
		}
		effects.hide(
			id.layer,id.pab_show_panel,
			id.load_word
		);
		npbody.style.overflowY='scroll';
	},
	
	hide_all:function(){
		if(!nohideall){
			id.layer.style.zIndex='1000';
			if(!start&&!from_remake&&no_sess){
				if (profile_user=='not_np_user'){
					load_work.feed();	
				}else{
					console.log("not_np_user");
				}
				npbody.style.overflowY='scroll';
				effects.hide(
					id.layer,id.pab_show_panel,
					id.load_word
				);
				start=true;
			}else if(is_saved_pab&&open_redactor){
				send_functions.close_redactor();
				is_saved_pab=false;
			}else if(no_sess&&open_redactor){
				send_functions.close_redactor();
			}else if(!no_sess&&open_redactor){
				var crd=tricks.getelmcrd(id.pablicate_place),
				pabh=id.pablicate_place.offsetHeight;
				id.layer.style.zIndex='3000';
				nohideall=true;
				modal.just_open(
					id.clredmodal,
					Math.round((crd.top+(pabh-114)/2))+'px',
					crd.left+309+'px',id.clredmove,
					clredclose,id.npbody
				);
			}else if(!start&&!from_remake&&open_pab){
				load_work.load_pabs(0,'pabs');
				effects.hide(
					id.layer,id.pab_show_panel,
					id.load_word
				);
				open_pab=false;
				start=true;
				from_remake=false;
				npbody.style.overflowY='scroll';
			}else if(!open_redactor&&!open_settings&&!open_a_proj&&start){
				load_work.hide_static();
			}
			if(open_settings){
				effects.hide(id.layer);
				open_settings=false;
				npbody.style.overflowY='scroll';
			}
			if(open_pab){
				id.pablication_place.innerHTML='';
			}
			modal.close(
				id.deletemodal,
				id.crmodal,
				id.wrcrmodal,
				id.settingmodal,
				id.complainmodal
			);
			ajax_global_img_for_pabs_and_auts=false;
			ajax_global_img_for_open_pabs=false;
		}else{
			id.layer.style.zIndex='1000';
			nohideall=false;
			modal.close(
				id.deletemodal,
				id.crmodal,
				id.wrcrmodal,
				id.settingmodal,
				id.complainmodal,
				id.clredmodal
			);
		}
	},
	
	create_pablications:function(obj,index){
		var pablication=document.createElement('div'),
		title,pab=obj.pabs[index];
		if(obj.auts){
			aut=obj.auts[index];
		}
		pabs_queue[queue_index]=[];
		pabs_queue[queue_index]['id']=pab.id;
		pabs_queue[queue_index]['path']=pab.path;queue_index++;
		
		var str="<div class='pablication_content' id='pc_"+
		pab.id+
		"' >"+
		pab.content+
		"</div><div class='pabl_info' id='pabl_info_"+
		pab.id+
		"'><div class='no_open_pab_ajax_img' id='open_pab_ajax_img_"+
		pab.id+
		"'></div><button class='button_interface open_pab_button' id='open_pab_button_"+
		pab.id+
		"'><span>"+record.open+"</span></button>"+
        "<div id='vk_share_pab_"+
        pab.id+
        "' style='width:auto;float:right;margin-right:90px;'></div><div id='pab_title_"+
		pab.id+
		"'>"+
		pab.title+
		"</div>";
		if(obj.auts){
			str+="<div class='aut_fn' id='fn_"+
			pab.id+
			"'>"+
			aut.name+' '+aut.fname+
			"</div>";
		}
		str+="<div id='pab_date_"+
		pab.id+
		"'>"+
		pab.date+
		"</div></div>";
			
		pablication.innerHTML=str;
		id.res_div.appendChild(pablication);
		var img=g.i('open_pab_ajax_img_'+pab.id),
		open_pab_button=g.i('open_pab_button_'+pab.id),
        vk_button=g.i('vk_share_pab_'+pab.id);    
		
		pablication.className='pablication border';
		pablication.setAttribute('id','pab_'+pab.id);
		open_pab_button.style.marginTop=(obj.auts)?'16px':'6px';
		img.style.marginTop=(obj.auts)?'14px':'4px';
        vk_button.style.marginTop=(obj.auts)?'22px':'13px';
      
		response_functions.youtube_opt_elms(pablication,true);
      
        var img_vk_pc=g.i('pc_'+pab.id),
        img_for_vk=img_vk_pc.getElementsByTagName('img'),
        vk_img_l=img_for_vk.length,
        vk_img;
      
        if(content_status!=='copies'){
           if(vk_img_l!==0){
              vk_img=img_for_vk[0].getAttribute('src');
           }else{
              vk_img='http://paper-blog.ru/img/logo_v1.png';
           }
         vk_button.innerHTML = VK.Share.button({
           url:'http://paper-blog.ru/'+pab.path,
           description:'Paper Blog|'+pab.title,
           image:vk_img
          },{type: "round_nocount", text: "<b>VK</b>"});
        }

        
		open_pab_button.onclick=function(){
			var img=g.i('open_pab_ajax_img_'+pab.id);
			ajax_global_img_for_open_pabs=img;
			effects.ajax_sw_open_img(img);
			load_work.open_pab(pab.id,pab.path,content_status);
			id.search_input.value='';
		}
		if(obj.auts){
			g.i('fn_'+pab.id).onclick=function(e){
				system.only_to_child(e);
				if(profile_user==='you'){
					effects.ajax_sw_img(id.u_ajax_img);
					ajax_global_img_for_pabs_and_auts=id.u_ajax_img;
				}else{
					effects.ajax_sw_img(id.n_u_ajax_img);
					ajax_global_img_for_pabs_and_auts=id.n_u_ajax_img;
				}
				load_work.load_profile(pab.aut,'pabs',false);
				window.scrollTo(0,0);
			}
		}
	},
	
	create_auts_fans_list:function(obj,index){
		var elm=obj.list[index],
		rect=document.createElement('div');
		rect.className='fans_auts_rect';
		rect.setAttribute('id','fans_auts_'+elm.id);
		rect.innerHTML='<span>'+
		elm.name+' '+elm.fname+'</span>';
		id.res_div.appendChild(rect);
		rect.onclick=function(){
			if(profile_user==='you'){
				effects.ajax_sw_img(id.u_ajax_img);
				ajax_global_img_for_pabs_and_auts=id.u_ajax_img;
			}else{
				effects.ajax_sw_img(id.n_u_ajax_img);
				ajax_global_img_for_pabs_and_auts=id.n_u_ajax_img;
			}
			load_work.load_profile(elm.id,'pabs',false);
		}
	},
	
	load_profile_start:function(user_id,content_status,need_fn){
		ajax.post(false,'profile.php','to_profile',
		{
			user_id:user_id,
			content_status:content_status,
			need_fn:need_fn
		},
		function(obj){
			response_functions.load_profile(obj);
		});
	},
	
	load_profile:function(user_id,content_status,need_fn){
		ajax.post(true,'profile.php','to_profile',
		{
			user_id:user_id,
			content_status:content_status,
			need_fn:need_fn
		},
		function(obj){
			response_functions.load_profile(obj);
		});
	},
		
	open_pab:function(id,path,content_status){
		ajax.post(true,'open_pab.php','to_open_pab',
		{
			pab_id:id,path:path,
			content_status:content_status
		},
		function(obj){
			response_functions.open_pab(obj);
		});
	},
	
	refresh_viewes:function(id){
		ajax.post(true,'refresh_views.php','to_refresh_views',
		{pab_id:id},
		function(obj){
			response_functions.no_good(obj);
		});
	},
	
	like_dislike:function(id,like_index){
		ajax.post(false,'like_dislike.php','to_like_dislike',
		{
			pab_id:id,
			like_index:like_index
		},
		function(obj){
			response_functions.show_likes(obj);
		});
	},
	
	load_pabs:function(user_id,type_of_pabs){
		ajax.post(true,'load_pabs.php','to_load_pabs',
		{
			user_id:user_id,
			type_of_pabs:type_of_pabs,
			next_load_step:next_load_step,
			need_fn:need_fn
		},
		function(obj){
			response_functions.load_pabs(obj);
		});
	},
	
	load_auts_fans_list:function(user_id,type_of_rects){
		ajax.post(true,'load_auts_fans_list.php','to_load_auts_fans_list',
		{
			user_id:user_id,
			type_of_rects:type_of_rects,
			next_load_step:next_load_step
		},
		function(obj){
			response_functions.load_auts_fans_list(obj);
		});
	},
	
	feed:function(){
		ajax.post(true,'feed.php','to_feed',
		{next_load_step:next_load_step},
		function(obj){
			response_functions.load_feed(obj);
		});
	},
	
	save_common_event:function(to_where){
		effects.show(id.load_block);
		ajax.post(true,'save_pab.php','to_save_pab',
		{
			title:id.title.value,
			content:id.content.innerHTML,
			to_where:to_where,
			for_remake_content:for_remake.content_status,
			for_remake_pab_id:for_remake.pab_id
		},
		function(obj){
			response_functions.save_pab(obj);
		});
	},
	
	search_next_results:function(obj){
		var send_obj={clear:false,search_input:obj.search_str,
			type_of_search:obj.type_of_search,
			next_load_step:obj.next_load_step,
			auts_cache:obj.auts_cache};
			ajax.post(true,'search.php','to_search',send_obj,
			function(obj){
				response_functions.load_pabs(obj);
			});
	},
	
	feed_next_results:function(obj){
		var send_obj={
			clear:false,
			next_load_step:obj.next_load_step
		};
		ajax.post(true,'feed.php','to_feed',send_obj,
		function(obj){
			response_functions.load_pabs(obj);
		});
	},
	
	dynamic_search:function(e){
		if(id.search_input.value!==''){
			if(!system.enter(e)&&!system.down_key(e)&&!system.up_key(e)){
				ajax.post(false,'search_choose.php','to_search_choose',
				{
					search_input:id.search_input.value,
					type_of_search:id.value_of_type_search.getAttribute('class')
				},
				function(obj){
					response_functions.search_choose(obj);
				})
			}else if(system.enter(e)){
				if(search_choose_queue_index!==-1){
					system.create_mouse_event(search_choose_queue[search_choose_queue_index],'click');
				}else{
					system.create_mouse_event(id.search_button,'click');
					effects.hide(id.search_choose);
				}
			}
		}else{
			effects.hide(id.search_choose);
		}
	},
		
	search:function(){
	
		var options_of_type_of_search_tr=g.dt(id.type_of_search,'tr');
		var options_of_type_of_search_td=g.dt(id.type_of_search,'td');
		system.add_event(id.type_of_search,'mouseover',
		function(){
			effects.show(options_of_type_of_search_tr[1],options_of_type_of_search_tr[2])
		});
		system.add_event(id.type_of_search,'mouseout',
		function(){
			effects.hide(options_of_type_of_search_tr[1],options_of_type_of_search_tr[2])
		});
		system.add_event_to_elms(options_of_type_of_search_td,'click',
		function(){
			effects.hide(options_of_type_of_search_tr[1],options_of_type_of_search_tr[2]);
			var value=options_of_type_of_search_td[0].innerHTML,ch_op=this.innerHTML,
			value_class=options_of_type_of_search_td[0].getAttribute('class'),
			ch_op_class=this.getAttribute('class');
			this.innerHTML=value;options_of_type_of_search_td[0].innerHTML=ch_op;
			this.setAttribute('class',value_class);options_of_type_of_search_td[0].setAttribute('class',ch_op_class);
			
		});
		
		id.search_input.onblur=function(){
			effects.hide(id.search_choose);
		};
		
		id.search_choose.onmouseover=function(){
			id.search_input.onblur=function(){return null;};
		};
		
		id.search_choose.onmouseout=function(){
			id.search_input.onblur=function(){
				effects.hide(id.search_choose);
			};
		};

		id.search_input.onkeyup=function(e){
			if(system.down_key(e)||system.up_key(e)){
				return null;
			}
			load_work.dynamic_search(e);
		}
		
		function sds(queue,index){
			var l=queue.length;
			for(i=0;i<l;i++){
				if(i===index){
					queue[i].style.background='#3F4B4F';
					queue[i].style.color='#fff';
				}else{
					queue[i].style.background='#fff';
					queue[i].style.color='#000';
				} 
			}
		};
		
		system.add_event(id.search_input,'keydown',function(e){
			if(id.search_choose.style.display!=='none'){
				var ql=search_choose_queue.length-1;
				if(search_choose_queue_index===-1){
					if(system.down_key(e)){
						search_choose_queue_index=0;
						sds(search_choose_queue,0);
					}else if(system.up_key(e)){
						search_choose_queue_index=ql;
						sds(search_choose_queue,ql);
					}
				}else{
					if(system.down_key(e)){
						search_choose_queue_index+=1;
						if(search_choose_queue_index>ql)
						search_choose_queue_index=-1;
						sds(search_choose_queue,search_choose_queue_index);
					}else if(system.up_key(e)){
						search_choose_queue_index-=1;
						if(search_choose_queue_index<=-1)
						search_choose_queue_index=-1;
						sds(search_choose_queue,search_choose_queue_index);
					}
				}
			}
		});
				
		system.add_event(id.search_input,'focus',function(e){
			if(id.search_input.value!==''&&!search_input_div_clicked)
			load_work.dynamic_search(e);
			else search_input_div_clicked=false;
		});
		
		system.add_event(id.search_input_div,'click',function(){
			search_input_div_clicked=true;
			id.search_input.focus();
		});	
			
		ajax.ajax_box(
			id.search_div,
			id.search_button,
			'search.php',
			'to_search',
			true,
			function(obj){
				response_functions.search(obj)
			},{
				type_of_search:options_of_type_of_search_td[0],
				next_load_step:next_load_step
			});
		system.add_event(id.search_button,'click',
		function(){
			ajax_global_img_for_pabs_and_auts=false;
			ajax_global_img_for_open_pabs=false;	
		})
	},
	
},

send_functions={
	
				
	enter_reg:function(){
		
		if(!if_enter_reg){
			system.add_event(id.re_captcha,'click',
			function(){
				id.captcha.setAttribute('src','np_captcha.php');
			});
			system.add_event(id.regclose,'click',
			function(){
				effects.hide(id.registration,id.errorres);effects.show(id.email_for_code);
			});
			system.add_event(id.chfpwclose,'click',
			function(){
				effects.hide(id.change_fpword_modal,id.errorres);effects.show(id.email_for_code);
			});
			system.add_event(id.email_for_code_close,'click',
			function(){
				effects.hide(id.email_for_code,id.errorres);effects.show(id.enter_system);
			});
			system.add_event(id.to_reg,'click',function(){
				effects.show(id.email_for_code);
				effects.hide(id.enter_system,id.errorres);
				rc=1;
			});
			system.add_event(id.feed_link,'click',
			function(){
				load_work.feed();
			});
			function efc(){
				effects.ajax_sw_img(id.email_for_code_img);
				ajax.post(true,'email_for_code.php','to_email_for_code',{
					email_for_code_input:id.email_for_code_input.value,
					to_reg:rc
				},function(obj){
					response_functions.email_for_code(obj)
				});
			}
			system.add_event(id.email_for_code_ok,'click',
			function(){
				efc();
			});
			system.add_event(id.email_for_code_input,'keyup',
			function(e){
				if(system.enter(e))efc();
			});
			ajax.ajax_box(
				id.enter_system,
				id.enterok,
				'enter_system.php',
				'to_enter_system',
				true,
				response_functions.to_join,
				{}
			);
			system.add_event(id.forget_pword,'click',
			function(){
				effects.hide(id.enter_system,id.errorres);
				effects.show(id.email_for_code);rc=0;
			});
			if_enter_reg=true;
		}

	},
	
	close_redactor:function(){
		for_remake.content_status='pabs';
		npbody.style.overflowY='scroll';
		for_remake.pab_id=0;
		for_remake.title='';
		for_remake.pab='';
		open_redactor=false;
		effects.hide(
			id.layer,
			id.pablicate_place,
			id.pmerrpl,
			id.more_info
		);
		modal.close(
			id.textcolor,
			id.textfamdiv,
			id.pablicatemodal,
			id.text_edit,id.tdcolor,
			id.clredmodal
		);
		if(open_panel_index!==-1)
		modal.close(panelarray[open_panel_index]);
		tfs_sw_index=0;tc_sw_index=0;
		td_sw_index=0;p_sw_index=0;
		open_panel_index=-1;
		effects.hide(id.load_block);
	},
	
	to_wysiwyg:function(){
		if(!if_wysiwyg){
			system.add_event(id.to_pablicate,'click',
			function(){
				design.setStyles(id.layer,{zIndex:'1000'});
				open_redactor=true;
				if(id.wholeworkplace){
					effects.show(id.layer,id.pablicate_place);
					effects.hide(id.pab_show_panel,id.pablication_tools_place);
					if(!from_remake){
						id.content.innerHTML='';
						id.title.value='';
					}
					id.pablicate_place.style.top=15+tricks.scroll.top()+'px';
					id.npbody.style.overflowY='hidden';
					send_functions.buttons_appearance();
					if(no_sess){
						system.splash_event_to_elms(
							'onclick',
							function(){
								effects.hidding_message(id.main_ajax_response_place,record.need_join);
							},
							[id.make_pab,
							id.to_copy]
						);
					}else{
						id.make_pab.onclick=function(){
							load_work.save_common_event('pablications');
						}
						id.to_copy.onclick=function(){
							load_work.save_common_event('copywritings');
						}
					}
				}else{
					ajax.post(false,'load_wysiwyg.php','to_work_with_wysiwyg',{},
					function(obj){
						response_functions.to_pablicate(obj);
					});
				}
			});
			if_wysiwyg=true;
		}
	},
	
	user_tools_able:function(){
		
		if(!if_user_tools_able){
			system.add_event(id.exit,'click',
			function(){
				ajax.post(false,'exit.php','to_exit',{},
				function(obj){
					response_functions.exit(obj);
				});
			});
			send_functions.to_wysiwyg();
			system.add_event(id.settings,'click',
			function(){
				effects.show(id.layer);
				id.npbody.style.overflowY='hidden';
				modal.open_fix(
					id.settingmodal,
					id.settingmove,
					id.settingclose,
					id.nph,id.npw,
					id.npbody
				);
				open_settings=true;
				id.settingclose.onclick=function(){
					effects.hide(id.layer,id.set_err);
					modal.close(id.settingmodal);
					id.npbody.style.overflowY='scroll';
					open_settings=false;
				};
				ajax.ajax_box(
					id.settingmodal,
					id.save_changes,
					'settings.php',
					'to_settings',
					true,
					response_functions.settings,
					{}
				);
			})
			system.add_event(id.my_pabs,'click',
			function(){
				effects.ajax_sw_img(id.my_pabs_ajax_img);
				ajax_global_img_for_pabs_and_auts=id.my_pabs_ajax_img;
				load_work.default_you_params('pabs');
				user_name=your_user_name;
				id.search_input.value='';
			});
			system.add_event(id.my_copies,'click',
			function(){
				effects.ajax_sw_img(id.my_copies_ajax_img);
				ajax_global_img_for_pabs_and_auts=id.my_copies_ajax_img;
				load_work.default_you_params('copies');
				user_name=your_user_name;
				id.search_input.value='';
			});
			system.add_event(id.my_faves,'click',
			function(){
				effects.ajax_sw_img(id.my_faves_ajax_img);
				ajax_global_img_for_pabs_and_auts=id.my_faves_ajax_img;
				load_work.default_you_params('faves');
				user_name=your_user_name;
				id.search_input.value='';
			});
			system.add_event(id.my_fans,'click',
			function(){
				effects.ajax_sw_img(id.my_fans_ajax_img);
				ajax_global_img_for_pabs_and_auts=id.my_fans_ajax_img;
				load_work.default_you_params('fans');
				user_name=your_user_name;
				id.search_input.value='';
			});
			system.add_event(id.my_auts,'click',
			function(){
				effects.ajax_sw_img(id.my_auts_ajax_img);
				ajax_global_img_for_pabs_and_auts=id.my_auts_ajax_img;
				load_work.default_you_params('auts');
				user_name=your_user_name;
				id.search_input.value='';
			});
			system.add_event(id.load_feed,'click',
			function(){
				effects.ajax_sw_img(id.feed_ajax_img);
				ajax_global_img_for_pabs_and_auts=id.feed_ajax_img;
				load_work.feed();
				id.search_input.value='';
			});
			if_user_tools_able=true;
			id.clred_yes.onclick=function(){
				id.content.innerHTML='';
				id.layer.style.zIndex='1000';
				nohideall=false; 
				if(from_remake){
					load_work.hide_static();
					from_remake=false;
				}
				if(!start&&no_sess){
					load_work.feed();
					start=true;
					npbody.style.overflowY='scroll';
				}else if(!start&&open_pab){
					load_work.load_pabs(0,'pabs');
					effects.hide(
						id.layer,id.pab_show_panel,
						id.load_word
					);
					open_pab=false;
					start=true;
					from_remake=false;
					npbody.style.overflowY='scroll';
				}
				send_functions.close_redactor();
			}
		}
		effects.hide(id.enter_system,id.registration);
		effects.show(id.user_display,id.exit,id.settings);
		id.to_pablicate.innerHTML=record.write_a_pub;
		
	},
	
	buttons_appearance:function(){
		if(for_remake.pab_id===0){
			if(for_remake.content_status==='pabs'){
				system.splash_html({
					make_pab:record.make_pab,
					to_copy:record.save_pab
				});
			}else{
				system.splash_html({
					make_pab:record.save_pab,
					to_copy:record.to_copy
				});
			}
		}else{
			if(for_remake.content_status==='pabs'){
				system.splash_html({
					make_pab:record.resave_pab,
					to_copy:record.to_copy
				});
			}else{
				system.splash_html({
					make_pab:record.make_pab,
					to_copy:record.save_pab
				});
			}
		}
	},
	
	start_load:function(){
		load_work.search();
		load_work.load_profile_start(0,false,false);
		console.log("start_load");
	}
	
	
},

response_functions={
	
	handler:function(obj,h_obj){
		if(obj.status==='good'){
			h_obj.func(obj);
		}else if(regs.error(obj.status)){
			effects.hidding_message(id.corner_error_display,obj.status);
		}else if(obj.status==='no_sess'){
			no_sess_func();
		}else if(obj.status==='not_able'){
			not_able_func();
		}else if(obj.status==='no_result'){
			if(h_obj.no_result_func)h_obj.no_result_func(obj);
		}else if(obj.status==='not_np_user'){
			if(h_obj.not_np_user_func)h_obj.not_np_user_func(obj);
		}else if(h_obj.error_func){
			h_obj.error_func(obj);
		}
		if(h_obj.img){
			effects.ajax_sw_img_stop();
			ajax_global_img_for_pabs_and_auts=false;
			ajax_global_img_for_open_pabs=false;
		}
	},	
	
	email_for_code:function(obj){
		effects.hide(id.errorres);
		response_functions.handler(obj,{
			func:function(){
				effects.hide(id.email_for_code);
				if(rc===1){
					if(!if_reg){
						ajax.ajax_box(
							id.registration,
							id.regok,
							'registration.php',
							'to_reg',
							true,
							response_functions.to_join,
							{}
						);
						id.captcha.setAttribute('src','np_captcha.php');
						if_reg=true;
					}
					id.regemail.value=obj.email_for_code;
					effects.show(id.registration);
				}else{
					if(!if_change_pword){
						ajax.ajax_box(
							id.change_fpword_modal,
							id.chfpw_ok,
							'change_pword.php',
							'to_change_fpword',
							true,
							response_functions.ch_ok,
							{}
						);
						if_reg=true;
					}
					effects.show(id.change_fpword_modal);
				}
			},
			error_func:function(){
				errors.corner_div(id.errorres,obj.status);
				errors.form(obj.errors_input);
			},
			img:id.email_for_code_img
		})
	},
	
	to_join:function(obj){
		effects.hide(id.errorres);
		response_functions.handler(obj,{
			func:function(obj){
				no_sess=false;
				effects.hide(id.feed_link);
				effects.hidding_message(id.main_ajax_response_place,record.welcome);
				system.splash_style('marginTop',{user_common_info:'0px',res_div:'40px'});
				send_functions.user_tools_able();
				response_functions.load_profile(obj);
				if(ajax_global_img_for_pabs_and_auts)
				design.change_class(ajax_global_img_for_pabs_and_auts,'ajax_img','ajax_stop_img');
			},
			error_func:function(obj){
				errors.corner_div(id.errorres,obj.status);
				errors.form(obj.errors_input);
				id.captcha.setAttribute('src','np_captcha.php');
			}
		});
	},
	
	ch_ok:function(obj){
		effects.hide(id.fpword_errorres);
		response_functions.handler(obj,{
			func:function(obj){
				effects.hidding_message(id.main_ajax_response_place,record.changed_fpword);
				effects.hide(id.change_fpword_modal);
				effects.show(id.enter_system);
			},
			error_func:function(obj){
				errors.corner_div(id.fpword_errorres,obj.status);
				errors.form(obj.errors_input);
			}
		});
	},
	
	settings:function(obj){
		effects.hide(id.set_err);
		response_functions.handler(obj,{
			func:function(obj){
				effects.hidding_message(id.main_ajax_response_place,record.set_suc);
				effects.hide(id.layer);
				modal.close(id.settingmodal);
				id.layer.style.zIndex='1000';nohideall=false; 
			},
			error_func:function(){
				errors.corner_div(id.set_err,obj.status);
				errors.form(obj.errors_input);
			}
		})
	},
	
	exit:function(obj){
		response_functions.handler(obj,{
			func:function(){
				effects.show(id.enter_system,id.feed_link);
				effects.hide(id.user_display,id.exit,id.settings);
				id.to_pablicate.innerHTML=record.dem;
				send_functions.enter_reg();
				ajax_global_img_for_pabs_and_auts=false;
				ajax_global_img_for_open_pabs=false;
				no_sess=true;
				if(id.make_pab){
					system.splash_event_to_elms(
						'onclick',
						function(){
							effects.hidding_message(id.main_ajax_response_place,record.need_join);
						},
						[id.make_pab,
						id.to_copy]
					);
				}
			}
		});
	},

	load_profile:function(obj){
		response_functions.handler(obj,{
			func:function(){
				response_functions.profile(obj.profile);
			}		
		});
	},
	
	no_sess:function(){
		no_sess=true;
		send_functions.enter_reg();
		effects.show(id.enter_system,id.feed_link);
		effects.hide(id.user_display,id.exit,id.settings);
		id.to_pablicate.innerHTML=record.dem;
		if(id.make_pab){
			system.splash_event_to_elms(
				'onclick',
				function(){
					effects.hidding_message(id.main_ajax_response_place,record.need_join);
				},
				[id.make_pab,
				id.to_copy]
			);
		}
		send_functions.to_wysiwyg();
		load_work.hide_all();
		history_api.url_to_page(wl);
	},
	
	no_good:function(obj){
		if(obj.status!=='good'){
			effects.hidding_message(id.corner_error_display,obj.status);	
		}
	},	
			
	
	no_result_pabs_realization:function(obj){
		var res;
		if(profile_user==='you'){
			if(content_status==='pabs')res=record.no_result_pabs;
			else if(content_status==='copies')res=record.no_result_copies;
			else if(content_status==='faves')res=record.no_result_faves;
			else if(content_status==='fans')res=record.no_result_fans;
			else if(content_status==='auts')res=record.no_result_auts;
			else if(content_status==='search_pabs')res=record.no_result;
			id.no_result.style.marginTop='48px';
			id.user_common_info.style.height='33px';
		}else if((profile_user==='not_you')||(profile_user==='not_np_user')){
			if(content_status==='pabs')res=record.no_user_result_pabs;
			else if(content_status==='faves')res=record.no_user_result_faves;
			else if(content_status==='fans')res=record.no_user_result_fans;
			else if(content_status==='auts')res=record.no_user_result_auts;
			else if(content_status==='search_pabs')res=record.no_result;
			id.no_result.style.marginTop='88px';
			id.user_common_info.style.height='75px';
		}
		if(obj&&((obj.content_status==='search_pabs')||(obj.content_status==='feed_pabs')||(obj.type_of_search==='aut_id_search'))){
			effects.show(id.u_i);
			effects.hide(id.not_your_info);
			system.splash_html({your_info:obj.query});
			id.res_div.style.marginTop='39px';
			id.user_common_info.style.height='33px';
			id.no_result.style.marginTop='48px';
		}
		return res;
	},
	
	panel:function(obj){
		if(profile_user==='you'){
			effects.show(id.u_i);
			effects.hide(id.not_your_info);
			if(content_status==='pabs'){system.splash_html({your_info:record.my_pabs});}
			else if(content_status==='copies'){system.splash_html({your_info:record.my_copies});}
			else if(content_status==='faves'){system.splash_html({your_info:record.my_faves});}
			else if(content_status==='fans'){system.splash_html({your_info:record.my_fans});}
			else if(content_status==='auts'){system.splash_html({your_info:record.my_auts});}
			id.res_div.style.marginTop='39px';
			id.user_common_info.style.height='33px';
		}else{
			var elms=[id.not_your_pabs,id.not_your_faves,id.not_your_fans,id.not_your_auts];
			if(content_status==='pabs')effects.switch_classes_in_elms(elms,'not_you_load_span_selected','not_you_load_span',0);
			else if(content_status==='faves')effects.switch_classes_in_elms(elms,'not_you_load_span_selected','not_you_load_span',1);
			else if(content_status==='fans')effects.switch_classes_in_elms(elms,'not_you_load_span_selected','not_you_load_span',2);
			else if(content_status==='auts')effects.switch_classes_in_elms(elms,'not_you_load_span_selected','not_you_load_span',3);
			id.res_div.style.marginTop='81px';
			id.user_common_info.style.height='75px';
		}
		if((content_status==='search_pabs')||(content_status==='feed_pabs')||(obj.type_of_search==='aut_id_search')){
			effects.show(id.u_i);
			effects.hide(id.not_your_info);
			system.splash_html({your_info:obj.query});
			id.res_div.style.marginTop='39px';
			id.user_common_info.style.height='33px';
		}
	},
	
	you_realization:function(obj){
		effects.show(id.u_i);
		effects.hide(id.not_your_info);
		var name=obj.name+' '+obj.fname;
		user_name=name;
		your_user_name=name;
		system.splash_html({fn:name});
		document.title=name;
		send_functions.user_tools_able();
	},
	
	not_you_common_realization:function(obj){
		var name=obj.name+' '+obj.fname;
		system.splash_html({loaded_fn_info:name});
		user_name=name;
		if(obj.is_aut){
			effects.src(add_to_auts_img,record.ff);
			system.splash_attrs('title',{add_to_auts:record.remove_aut});
		}else{
			effects.src(add_to_auts_img,'http://paper-blog.ru/img/fl.png');
			system.splash_attrs('title',{add_to_auts:record.add_to_auts});
		}
		id.not_your_pabs.onclick=function(){
			load_work.default_params('pabs');
			effects.ajax_sw_img(id.n_u_ajax_img);
			ajax_global_img_for_pabs_and_auts=id.n_u_ajax_img;
			load_work.load_pabs(obj.id,'pabs');
			id.search_input.value='';
		}
		id.not_your_faves.onclick=function(){
			load_work.default_params('faves');
			effects.ajax_sw_img(id.n_u_ajax_img);
			ajax_global_img_for_pabs_and_auts=id.n_u_ajax_img;
			load_work.load_pabs(obj.id,'faves');
			id.search_input.value='';
		}
		id.not_your_fans.onclick=function(){
			load_work.default_params('fans');
			effects.ajax_sw_img(id.n_u_ajax_img);
			ajax_global_img_for_pabs_and_auts=id.n_u_ajax_img;
			load_work.load_auts_fans_list(obj.id,'fans');
			id.search_input.value='';
		}
		id.not_your_auts.onclick=function(){
			load_work.default_params('auts');
			effects.ajax_sw_img(id.n_u_ajax_img);
			ajax_global_img_for_pabs_and_auts=id.n_u_ajax_img;
			load_work.load_auts_fans_list(obj.id,'auts');
			id.search_input.value='';
		}
	},
	
	not_you_realization:function(obj){
		effects.show(id.not_your_info,id.add_to_auts);
		effects.hide(id.u_i);
		response_functions.not_you_common_realization(obj);
		add_to_auts.onclick=function(){
			obj.id*=1;
			ajax.post(false,'add_to_auts.php','to_add_to_auts',{aut_id:obj.id},
			function(obj){
				response_functions.add_to_auts(obj);
			});
		}
	},
	
	not_np_user_realization:function(obj){
		effects.show(id.not_your_info);
		effects.hide(id.u_i,id.add_to_auts);
		response_functions.not_you_common_realization(obj);
		add_to_auts.onclick=function(){}
	},
	
	profile:function(obj){
		if(obj.pabs_data!==undefined)
		need_fn=(obj.pabs_data.content_status==='faves')?true:false;
		response_functions.handler(obj,{
			func:function(obj){
				load_work.default_params();
				profile_user=obj.user;
				profile_user_id=obj.id;
				effects.hide(id.no_result);
				if(profile_user==='you'){
					response_functions.you_realization(obj);
				}else if(profile_user==='not_you'){
					response_functions.not_you_realization(obj);
				}else if(profile_user==='not_np_user'){
					response_functions.not_np_user_realization(obj);
					console.log("not_np_user");	
				}
				if(obj.list_data){
					response_functions.load_auts_fans_list(obj);
				}else if(obj.pabs_data){
					response_functions.load_pabs(obj);
					console.log("load_pabs");
				}else{
				     if(!start||first_pop_state){
				     	history_api.url_to_page(wl);
				     	first_pop_state=false;	
				     }
				     else {
				       id.res_div.style.marginTop='39px';
				       id.res_div.innerHTML=record.access_error;
				     }
				}
			},
			no_result_func:function(obj){
				if(obj.pabs_data!==undefined)
				load_work.default_params(obj.pabs_data.content_status);
				history_api.set_profile(
					obj.user_id,
					obj.content_status,
					obj.need_fn,
					obj.url
				);
				id.user_common_info.style.height='33px';
				id.no_result.style.marginTop='48px';
				effects.show(id.no_result,id.u_i);
				effects.hide(id.next_results,id.not_your_info);
				if(obj.request!==undefined)
				system.splash_html({
					res_div:'',
					no_result:record.profile_not_found,
					your_info:obj.request
				});
				else system.splash_html({
					res_div:'',no_result:record.no_result
				});
			}
		});
	},
		
	load_pabs:function(obj){
		response_functions.handler(obj,{
			func:function(obj){
				response_functions.pabs(obj.pabs_data);
				history_api.set_profile(
					obj.pabs_data.user_id,
					obj.pabs_data.content_status,
					obj.pabs_data.need_fn,
					obj.pabs_data.url
				);
				console.log("load_pabs");
			},
			no_result_func:function(){
				history_api.set_profile(
					obj.pabs_data.user_id,
					obj.pabs_data.content_status,
					obj.pabs_data.need_fn,
					obj.pabs_data.url
				);
			},
			img:ajax_global_img_for_pabs_and_auts
		});
	},
	
	load_pabs_for_search:function(obj){
		response_functions.handler(obj,{
			func:function(obj){
				response_functions.pabs(obj.pabs_data);
				if(search_to_history){
					history_api.set_query(
						obj.pabs_data.search_str,
						obj.pabs_data.type_of_search,
						obj.url
					);
				}else{
					search_history=true;
					if(!auto_search){
						history_api.set_query(
							obj.pabs_data.search_str,
							obj.pabs_data.type_of_search,
							obj.url
						);
					}else{
						auto_search=false;
					}
				}
			},
			no_result_func:function(){
				history_api.set_query(
					obj.pabs_data.search_str,
					obj.pabs_data.type_of_search,
					obj.url
				);
			}
		});
	},
	
	load_auts_fans_list:function(obj){
		response_functions.handler(obj,{
			func:function(obj){
				response_functions.auts_fans_list(obj.list_data);
				history_api.set_profile(
					obj.list_data.user_id,
					obj.list_data.content_status,
					null,
					obj.list_data.url
				);
			},
			no_result_func:function(){
				history_api.set_profile(
					obj.list_data.user_id,
					obj.list_data.content_status,
					null,
					obj.list_data.url
				);
			},
			img:ajax_global_img_for_pabs_and_auts
		});
	},
	
	load_feed:function(obj){
		response_functions.handler(obj,{
			func:function(obj){
				response_functions.pabs(obj.pabs_data);
				history_api.set_feed(
					obj.pabs_data.url
				);
			},
			img:ajax_global_img_for_pabs_and_auts
		});
	},
	
	youtube_opt_elms:function(elm,hc){
		var i,c,y,v,s,n,vl;
		v=elm.getElementsByClassName("youtube");
		vl=v.length;
		for(n=0;n<vl;n++){
			y=v[n];i=document.createElement("img");
			if(hc){
				y.style.marginLeft='auto';
				y.style.marginRight='auto';
			}
			y.parentNode.style.background='white';
			i.setAttribute("src","http://i.ytimg.com/vi/"+y.id+"/hqdefault.jpg");
			i.setAttribute("class","thumb");
			c=document.createElement("div");
			c.setAttribute("class","play");
			y.appendChild(i);
			y.appendChild(c);
			y.onclick=function(){
				var a=document.createElement("iframe");
				a.setAttribute("src","https://www.youtube.com/embed/"+this.id+"?autoplay=1&autohide=1&border=0&wmode=opaque&enablejsapi=1");
				a.style.width=this.style.width;
				a.style.height=this.style.height;
				this.parentNode.replaceChild(a,this);
				a.setAttribute('allowfullscreen',true);
				if(!hc){
					if(a.parentNode.style.cursor=='move'){
						a.style.marginTop='35px';
						a.style.marginLeft='35px';
					}
				}
			}
		};
	},
	
	pabs:function(obj){
		response_functions.handler(obj,{
			func:function(obj){
				start=true;
				content_status=obj.content_status;
				if(obj.pabs.interval===0){
					effects.show(id.no_result);
					system.splash_html({
						no_result:response_functions.no_result_pabs_realization(obj)
					});
				}else{
					effects.hide(id.no_result);
				}
				response_functions.panel(obj);
				if(obj.clear){
					system.splash_html({res_div:''});
					pabs_queue=[];	
					queue_index=0;
					window.scrollTo(0,0);
				}
				response_functions.handler(obj,{
					func:function(obj){
						next_load_step=obj.next_load_step;
						for(index=0;index<obj.pabs.interval;index++){
							load_work.create_pablications(obj,index);
						}	
					}
				})
				console.log("pabs");
				if(obj.is_next){
					effects.show(id.next_results);
					if(obj.content_status==='search_pabs'){
						id.next_results.onclick=function(){
							load_work.search_next_results(obj);
						}
					}else if(obj.content_status==='feed_pabs'){
						id.next_results.onclick=function(){
							load_work.feed_next_results(obj);
						}
					}else{
						id.next_results.onclick=function(){
							load_work.load_pabs(profile_user_id,content_status);
						}
					}
				}else{
					effects.hide(id.next_results);
					id.next_results.onclick=function(){}
				}
			}
		});
	},
	
	auts_fans_list:function(obj){
		response_functions.handler(obj,{
			func:function(obj){
				start=true;
				content_status=obj.content_status;
				if(obj.list.interval===0){
					effects.show(id.no_result);
					system.splash_html({
						no_result:response_functions.no_result_pabs_realization(obj)
					});
				}else{
					effects.hide(id.no_result);
				}
				response_functions.panel(obj);
				next_load_step=obj.next_load_step;
				if(obj.clear)
				system.splash_html({res_div:''});
				response_functions.handler(obj,{
					func:function(obj){
						next_load_step=obj.next_load_step;
						for(index=0;index<obj.list.interval;index++){
							load_work.create_auts_fans_list(obj,index);
						}
					}
				})
				if(obj.is_next){
					effects.show(id.next_results);
					id.next_results.onclick=function(){
						load_work.load_auts_fans_list(profile_user_id,content_status);
					}
				}else{
					effects.hide(id.next_results);
					id.next_results.onclick=function(){}
				}
			}
		});
	},
	
	to_pablicate:function(obj){
		response_functions.handler(obj,{
			func:function(){
				effects.show(id.layer,id.pablicate_place);
				effects.hide(id.pab_show_panel,id.pablication_tools_place);
				system.splash_html({
					pablicate_place:obj.wysiwyg,
					wysiwyg_modals:obj.wysiwyg_modals
				});
				id=system.domStart(id,id.pablicate_place);
				id=system.domStart(id,id.wysiwyg_modals);
				var v1=nph-70+"px";
				design.setStyles(id.pablicate_place,{height:v1,top:'15px'});
				design.setStyles(id.content,{height:v1});
				var _ws=document.createElement('script');
				_ws.src="http://paper-blog.ru/java_script/wysiwyg.js?"+Math.random();
				_ws.type="text/javascript";
				id.npbody.appendChild(_ws);
				id.pablicate_place.style.top=15+tricks.scroll.top()+'px';
				id.npbody.style.overflowY='hidden';
				send_functions.buttons_appearance();
				system.add_event_to_elms([id.make_pab,id.to_copy],'click',
				function(){
					effects.hide(id.pmerrpl,id.more_info);
					more_info_sw_index=0;
					system.remove_scripts(id.content);
					system.defaultelms(id.content);
				})
				if(no_sess){
					system.splash_event_to_elms(
						'onclick',
						function(){
							effects.hidding_message(id.main_ajax_response_place,record.need_join);
						},
						[id.make_pab,
						id.to_copy]
					);
				}else{
					id.make_pab.onclick=function(){
						load_work.save_common_event('pablications');
					}
					id.to_copy.onclick=function(){
						load_work.save_common_event('copywritings');
					}
				}
			}
		});
	},
	
	save_pab:function(obj){
		response_functions.handler(obj,{
			func:function(obj){
				var c_s=((content_status==='pabs')||
				(content_status==='search_pabs')||
				(content_status==='feed_pabs'))
				?'pabs':content_status;
				load_work.load_pabs(0,c_s);
				id.content.innerHTML='';
				id.title.value='';
				if(for_remake.pab_id===0){
					if(obj.to_where==='copywritings'){
						effects.hidding_message(id.main_ajax_response_place,record.saved_in_copies);
					}else{
						effects.hidding_message(id.main_ajax_response_place,record.pablicated);
					}
				}else{
					if(obj.to_where==='copywritings'){
						if(obj.from_where==='pabs')
						effects.hidding_message(id.main_ajax_response_place,record.transfered_in_copies);
						else if (obj.from_where==='copies')
						effects.hidding_message(id.main_ajax_response_place,record.saved);
					}else if(obj.to_where==='pablications'){
						if(obj.from_where==='pabs')
						effects.hidding_message(id.main_ajax_response_place,record.saved);
						else if (obj.from_where==='copies'){
							effects.hidding_message(id.main_ajax_response_place,record.pablicated);
						}
						
					}
				}
				is_saved_pab=true;
				load_work.hide_all();
				setTimeout(function(){effects.hide(id.load_block);},1000);
			},
			error_func:function(obj){
				setTimeout(
					function(){
						effects.show(id.pmerrpl);
						errors.form(obj.errors_input);
					},1000);
				id.pmerrpl.innerHTML="<span>"+
				obj.status+
				"</span>"+"<img src='http://paper-blog.ru/img/up2.png' id='aj_e_p'></img>";
				id['aj_e_p']=g.i('aj_e_p');
				effects.switch_images(id.aj_e_p,'http://paper-blog.ru/img/down2.png','http://paper-blog.ru/img/up2.png');
				setTimeout(function(){effects.hide(id.load_block);},1000);
			}
		});
	},
	
	show_likes:function(obj){
		id.like.removeAttribute('title');
		id.dislike.removeAttribute('title');
		if(obj.liked){
			id.like_img.setAttribute('src',record.pl);
		}else{
			id.like_img.setAttribute('src',record.pd);
		}
		if(obj.disliked){
			id.dislike_img.setAttribute('src',record.ml);
		}else{
			id.dislike_img.setAttribute('src',record.md);
		}
		if(obj.lq!==undefined&&obj.dlq!==undefined){
			system.splash_html({like_quans:obj.lq});
			system.splash_html({dislike_quans:obj.dlq});
		}else if((obj.like_diff!==undefined) && (obj.dislike_diff!==undefined)){
			var lq=id.like_quans.innerHTML*1+obj.like_diff*1;
			var dlq=id.dislike_quans.innerHTML*1+obj.dislike_diff*1;	
			system.splash_html({like_quans:lq});
			system.splash_html({dislike_quans:dlq});
		}
	},
	
	add_to_auts:function(obj){
		response_functions.handler(obj,{
			func:function(obj){
				if(obj.added){
					effects.src(id.add_to_auts_img,'http://paper-blog.ru/img/ff.png');
					system.splash_attrs('title',{add_to_auts:record.remove_aut});
				}else{
					effects.src(id.add_to_auts_img,'http://paper-blog.ru/img/fl.png');
					system.splash_attrs('title',{add_to_auts:record.add_to_auts});
				}	
			}
		});
	},

	pab_tools:function(obj){
		response_functions.handler(obj,{
			func:function(obj){
				response_functions.open_pab_common_for_good_status(obj);
			},
			not_np_user_func:function(obj){
				response_functions.open_pab_common(obj);
				response_functions.show_likes(obj.pab_info);
				system.splash_html({vq_info:obj.pab_info.vq});
				system.splash_attrs('title',{
					like:record.need_authorizations,
					dislike:record.need_authorizations
				});
				effects.hide(id.pab_manipulation_tools);
				effects.show(id.pab_open_tools);
				system.splash_event_to_elms(
				'onclick',
				function(){effects.hidding_message(id.main_ajax_response_place,record.need_join);},
				[id.to_remake_add_to_elect,
				id.to_remark_to_read_remark,
				id.to_remove_to_complain,
				id.like,
				id.dislike]
				);
			}
		});
	},

	open_pab_common:function(obj){
		id.load_word.innerHTML=record.load;
		effects.show(
			id.layer,
			id.pab_show_panel,
			id.pablication_tools_place,
			id.load_word
		);
		effects.hide(id.pablicate_place);
		var name=obj.pab_info.aut_name+' '+obj.pab_info.aut_fname;
		system.splash_html({
			pablication_place:obj.pab,
			fn_info:name,
			title_info:obj.pab_info.title,
			date_info:obj.pab_info.date
		});
		var cs=system.content_size(id.pablication_place)+'px';
		id.pablication_place.style.height=cs;
		var ph=id.pab_show_panel.offsetHeight,
		pth=id.pablication_tools_place.offsetHeight;
		if(ph>id.nph-10){
			ph=id.nph-20;
			id.pablication_place.style.height=ph-pth-50+'px';
		}
		
		var space_div=document.createElement('div');
		space_div.setAttribute('id','space_div');
		design.setStyles(space_div,{
			position:'absolute',
			marginTop:cs,
			width:'100%',
			height:'50px'
		})
		id.pablication_place.appendChild(space_div);
		 
		id.comm_aj_open_img.style.marginTop=((id.pablication_place.offsetHeight-39)/2)+'px';
		id.pab_show_panel.style.top=Math.round((id.nph-ph)/2)+tricks.scroll.top()+'px';
		system.splash_style('height',{b_p_q:ph-pth,f_p_q:ph-pth});
		nphhh=Math.round(((id.b_p_q.offsetHeight)-40)/2)+'px';
		system.splash_style('top',{pqr:nphhh,lqr:nphhh});
		id.npbody.style.overflowY='hidden';
		id.fn_info.onclick=function(){
			load_work.hide_all();
			load_work.load_profile(obj.pab_info.aut,'pabs',false);
			window.scrollTo(0,0);
		}
		var links=id.pablication_place.getElementsByTagName('a'),ll=links.length;
		for(i=0;i<ll;i++){
			links[i].onclick=function(){
				var newWin = window.open(this.href,'_blank');
				return false;
			}
		}
		response_functions.youtube_opt_elms(id.pablication_place);
        
	},
	
	delete_pab:function(obj){
		response_functions.handler(obj,{
			func:function(obj){
				id.layer.style.zIndex='1000';
				nohideall=false; 
				load_work.hide_all();
				effects.disable([id.del_yes,id.del_no]);
				effects.hide(g.i('pab_'+obj.pab_id));
				pabs_queue.splice(queue_present_index, 1);
				queue_index-=1;
				effects.hidding_message(id.main_ajax_response_place,record.deleted);
				if(queue_index===0){
					effects.show(id.no_result);
					system.splash_html({
						no_result:response_functions.no_result_pabs_realization(obj)
					});
				}
			},
			error_func:function(obj){
				effects.disable([id.del_yes,id.del_no]);
			}
		});
		
	},
	
	elect:function(obj){
		var elect=(obj.faved)?record.remove_from_elect:record.add_to_elect;
		system.splash_html({
			to_remake_add_to_elect:elect
		})
		effects.hide(id.load_word);
	},
	
	remark:function(obj){
		response_functions.handler(obj,{
			func:function(obj){
				modal.close(id.wrcrmodal);
				id.layer.style.zIndex='1000';nohideall=false; 
				effects.hidding_message(id.main_ajax_response_place,record.remarked);
			},
			error_func:function(obj){
				errors.form(obj.errors_input);
			}
		});
	},
	
	delete_remark:function(obj){
		response_functions.handler(obj,{
			func:function(obj){
				modal.close(id.wrcrmodal);
				id.layer.style.zIndex='1000';nohideall=false; 
				effects.hidding_message(id.main_ajax_response_place,record.remark_deleted);
			}
		});
	},
	
	clear_all:function(obj){
		response_functions.handler(obj,{
			func:function(obj){
				id.cr_text.innerHTML=record.no_remarks;
				to_remark_to_read_remark.innerHTML=record.read_remarks;
				effects.hidding_message(id.main_ajax_response_place,record.clear_all);
				id.cr_clear.disabled=true;
				id.cr_clear.onclick=function(){return null;}
			}
		})
	},
	
	read_remarks:function(obj){
		response_functions.handler(obj,{
			func:function(obj){
				var crd=tricks.getelmcrd(id.pablication_place),
				pabh=id.pablication_place.offsetHeight;
				modal.just_open(id.crmodal,
					Math.round((crd.top+(pabh-233)/2))+'px',
					crd.left+245+'px',id.crmove,crclose,id.npbody);
				id.cr_text.innerHTML='';
				if(!obj.corrects){
					id.cr_text.innerHTML=record.no_remarks;
					id.cr_clear.disabled=true;
					id.cr_clear.onclick=function(){return null;}
				}else{
					var l=obj.corrects.length;
					for(i=0;i<l;i++){
						id.cr_text.innerHTML+='<div>'+
						(i+1)+
						') <span style="margin-left:2px;">'+
						obj.corrects[i]+
						'</span></div>';
					}
					id.cr_clear.disabled=false;
					id.cr_clear.onclick=function(){
						ajax.post(false,'remark.php','to_remark',
						{pab_id:obj.pab_id,mark:id.wrcr_text.value,act:'clear_all'},
						function(obj){
							response_functions.clear_all(obj);
						});
					}
				}
			}
		});
	},
	
	open_corrects_panel:function(obj){
		response_functions.handler(obj,{
			func:function(obj){
				id.wrcr_text.value=obj.remark;
				var crd=tricks.getelmcrd(id.pablication_place),
				pabh=id.pablication_place.offsetHeight;
				modal.just_open(id.wrcrmodal, Math.round((crd.top+(pabh-213)/2))+'px',crd.left+225+'px',id.wrcrmove,wrcrclose,id.npbody);
				id.wrcr_write.onclick=function(){
					if(id.wrcr_text.value!==""){
						ajax.post(false,'remark.php','to_remark',
						{pab_id:obj.pab_id,mark:id.wrcr_text.value,act:'write'},
						function(obj){
							response_functions.remark(obj);
						});
					}else{
						errors.form(['wrcr_text']);
					}
				}
			}
		});
	},
	
	complain:function(obj){
		response_functions.handler(obj,{
			func:function(obj){
				effects.hidding_message(id.main_ajax_response_place,record.complained);
				modal.close(id.complainmodal);
				id.layer.style.zIndex='1000';nohideall=false; 
			},
			error_func:function(obj){
				errors.form(obj.error_inputs);
			}
		});
	},
	
	your_pab_tools_function:function(obj){
		var pab_info=obj.pab_info;
		if(pab_info.iswritable===0){
			effects.hide(id.to_remake_add_to_elect);
			id.to_remake_add_to_elect.onclick=function(){return null;}
		}else{
			id.to_remake_add_to_elect.onclick=function(){
				from_remake=true;
				id.pablication_place.innerHTML='';
				effects.hide(id.layer,id.pab_show_panel);
				for_remake.pab_id=pab_info.id;
				for_remake.content_status='pabs';
				for_remake.title=pab_info.title;
				for_remake.pab=obj.pab;
				system.create_mouse_event(id.to_pablicate,'click');
				id.content.innerHTML=for_remake.pab;
				response_functions.youtube_opt_elms(id.content);
				id.title.value=for_remake.title;
				OverClassParser();
			}
		}
		id.to_remove_to_complain.onclick=function(){
			var crd=tricks.getelmcrd(id.pablication_place),
			pabh=id.pablication_place.offsetHeight;
			modal.just_open(id.deletemodal,Math.round((crd.top+(pabh-114)/2))+'px',crd.left+309+'px',id.deletemove,deleteclose,id.npbody);
			modal.close(id.crmodal);
			id.layer.style.zIndex='3000';nohideall=true;  
			id.del_yes.onclick=function(){
				effects.disable([id.del_yes,id.del_no]);
				id.load_word.innerHTML=record.process;effects.show(id.load_word);
				ajax.post(false,'delete_pab.php','to_delete_pab',
				{pab_id:pab_info.id,type_of_pab:pab_info.type_of_pab},
				function(obj){
					response_functions.delete_pab(obj);
				});
			}
		}
		id.to_remark_to_read_remark.onclick=function(){
			modal.close(id.deletemodal);
			id.layer.style.zIndex='3000';nohideall=true;  
			ajax.post(false,'remark.php','to_remark',
			{pab_id:pab_info.id,act:'read'},
			function(obj){
				response_functions.read_remarks(obj);
			});
		}
		
	},
	
	not_your_pab_tools_function:function(obj){
		var pab_info=obj.pab_info;
		id.to_remake_add_to_elect.onclick=function(){
			ajax.post(false,'elect.php','to_elect',
			{pab_id:pab_info.id},
			function(obj){
				response_functions.elect(obj);
			});
		}
		id.to_remark_to_read_remark.onclick=function(){
			modal.close(id.wrcr_clear,id.complainmodal);
			id.layer.style.zIndex='3000';nohideall=true;  
			ajax.post(false,'remark.php','to_remark',
			{pab_id:pab_info.id,act:'open'},
			function(obj){
				response_functions.open_corrects_panel(obj);
			});
		}
		id.wrcr_clear.onclick=function(){
			ajax.post(false,'remark.php','to_remark',
			{pab_id:pab_info.id,act:'delete_remark'},
			function(obj){
				response_functions.delete_remark(obj);
			});
		}
		id.to_remove_to_complain.onclick=function(){
			var crd=tricks.getelmcrd(id.pablication_place),
			pabh=id.pablication_place.offsetHeight;
			modal.just_open(id.complainmodal,
				Math.round((crd.top+(pabh-220)/2))+'px',
				crd.left+220+'px',id.complainmove,
				id.complainclose,id.npbody
			);
			modal.close(id.wrcrmodal);
			id.layer.style.zIndex='3000';nohideall=true; 
			id.complain_ok.onclick=function(){
				if(id.reason.value===''){
					errors.form(['reason']);
				}else{
					ajax.post(false,'complain.php','to_complain',
					{reason:id.reason.value,pab_id:pab_info.id},
					function(obj){
						response_functions.complain(obj);
					});
				}
			}
		}
	},
	
	copies_tools_function:function(obj){
		var pab_info=obj.pab_info;
		id.to_remake_add_to_elect.onclick=function(){
			effects.hide(id.layer,id.pab_show_panel);
			from_remake=true;
			id.pablication_place.innerHTML='';
			for_remake.pab_id=pab_info.id;
			for_remake.content_status='copies';
			for_remake.title=pab_info.title;
			for_remake.pab=obj.pab;
			system.create_mouse_event(id.to_pablicate,'click');
			id.content.innerHTML=for_remake.pab;
			response_functions.youtube_opt_elms(id.content);
			id.title.value=for_remake.title;
			OverClassParser();
		}
		id.to_remove_to_complain.onclick=function(){
			var crd=tricks.getelmcrd(id.pablication_place),
			pabh=id.pablication_place.offsetHeight;
			modal.just_open(id.deletemodal,Math.round((crd.top+(pabh-114)/2))+'px',crd.left+309+'px',id.deletemove,id.deleteclose,id.npbody);
			id.layer.style.zIndex='3000';nohideall=true; 
			id.del_yes.onclick=function(){
				effects.disable([id.del_yes,id.del_no]);
				id.load_word.innerHTML=record.process;effects.show(id.load_word);
				ajax.post(false,'delete_pab.php','to_delete_pab',
				{pab_id:pab_info.id,type_of_pab:pab_info.type_of_pab},
				function(obj){
					response_functions.delete_pab(obj);
				});
			}
		}
	},
	
	open_pab_common_for_good_status:function(obj){
		if(obj.pab_info.content_status!=='copies'){
			system.splash_html({vq_info:obj.pab_info.vq});
			effects.show(
				id.vq_info_div,
				id.pab_open_tools,
				id.pab_manipulation_tools,
				id.to_remark_to_read_remark
			);
			load_work.refresh_viewes(obj.pab_info.id);
			if(obj.pab_info.isreadable===1){
				if(obj.pab_info.your){
					var rm=(obj.pab_info.corrects_q===0)
					?record.read_remarks
					:record.read_remarks+' ('+obj.pab_info.corrects_q+')';
					system.splash_html({
						to_remake_add_to_elect:record.remake_pab,
						to_remark_to_read_remark:rm,
						to_remove_to_complain:record.remove_pab
						
					});
					response_functions.your_pab_tools_function(obj);
				}else{
					var elect=(obj.pab_info.faved)
					?record.remove_from_elect
					:record.add_to_elect;
					system.splash_html({
						to_remake_add_to_elect:elect,
						to_remark_to_read_remark:record.to_remark,
						to_remove_to_complain:record.to_complain
					})
					response_functions.not_your_pab_tools_function(obj);
				}
			}else{
				if(obj.pab_info.faved&&!obj.pab_info.your){
					effects.hide(
						id.to_remark_to_read_remark,
						id.to_remove_to_complain
					);
					effects.show(id.to_remake_add_to_elect);
					var elect=(obj.pab_info.faved)
					?record.remove_from_elect
					:record.add_to_elect;
					system.splash_html({
						to_remake_add_to_elect:elect
					});
					id.to_remake_add_to_elect.onclick=function(){
						ajax.post(false,'elect.php','to_elect',
						{pab_id:obj.pab_info.id},
						function(obj){
							response_functions.elect(obj);
						});
					}
				}else{
					effects.hide(id.pab_manipulation_tools);
				}
			}
			id.like.onclick=function(){
				load_work.like_dislike(obj.pab_info.id,'like');
			}
			id.dislike.onclick=function(){
				load_work.like_dislike(obj.pab_info.id,'dislike');
			}
		}else{
			effects.show(id.pab_manipulation_tools);
			effects.hide(
				id.vq_info_div,
				id.pab_open_tools,
				id.to_remark_to_read_remark
			);
			if(obj.pab_info.isreadable===1){
				effects.show(
					id.to_remake_add_to_elect,
					id.to_remove_to_complain
				);
				system.splash_html({
					to_remake_add_to_elect:record.remake_pab,
					to_remove_to_complain:record.remove_pab
				})
				response_functions.copies_tools_function(obj);
			}else{
				system.splash_event_to_elms(
					'onclick',
					function(){},
					[id.to_remake_add_to_elect,
					id.to_remove_to_complain,]
				);
				effects.hide(
					id.to_remake_add_to_elect,
					id.to_remove_to_complain
				);
			}
			system.splash_event_to_elms(
				'onclick',
				function(){},
				[id.like,
				id.dislike]
			);
		}
		response_functions.open_pab_common(obj);
		response_functions.show_likes(obj.pab_info);
	},
	
	turn_pabs:function(pab){
		modal.close(id.deletemodal,id.crmodal,id.wrcrmodal,id.complainmodal);
		for(i=0;i<queue_index;i++){
			if((pabs_queue[i]['id']==pab.id)&&(pabs_queue[i]['path']==pab.path)){
				queue_present_index=i;
			}
		}
		if(queue_present_index===0){
			design.change_class(id.b_p_q,'turn_pab_1','turn_pab_2');
			design.change_class(id.f_p_q,'turn_pab_2','turn_pab_1');
			if(queue_index===1){
				design.change_class(id.f_p_q,'turn_pab_1','turn_pab_2');
				id.f_p_q.style.cursor='default';
				id.f_p_q.onclick=function(){load_work.hide_all();};
			}else{
				id.f_p_q.style.cursor='pointer';
				id.f_p_q.onclick=function(){
					load_work.open_pab(
						pabs_queue[queue_present_index+1]['id'],
						pabs_queue[queue_present_index+1]['path'],
						content_status
					)
					effects.ajax_sw_open_img(id.comm_aj_open_img);
					id.pablication_place.innerHTML='';
					ajax_global_img_for_open_pabs=id.comm_aj_open_img;
				}
			}
			id.b_p_q.style.cursor='default';
			id.b_p_q.onclick=function(){load_work.hide_all();};
		}else if(queue_present_index===(queue_index-1)){
			design.change_class(id.b_p_q,'turn_pab_2','turn_pab_1');
			design.change_class(id.f_p_q,'turn_pab_1','turn_pab_2');
			id.b_p_q.style.cursor='pointer';
			id.f_p_q.style.cursor='default';
			id.f_p_q.onclick=function(){load_work.hide_all();};
			id.b_p_q.onclick=function(){
				load_work.open_pab(
					pabs_queue[queue_present_index-1]['id'],
					pabs_queue[queue_present_index-1]['path'],
					content_status
				);
				effects.ajax_sw_open_img(id.comm_aj_open_img);
				id.pablication_place.innerHTML='';
				ajax_global_img_for_open_pabs=id.comm_aj_open_img;
			}
		}else{
			design.change_class(id.b_p_q,'turn_pab_2','turn_pab_1');
			design.change_class(id.f_p_q,'turn_pab_2','turn_pab_1');
			id.b_p_q.style.cursor='pointer';
			id.f_p_q.style.cursor='pointer';
			id.b_p_q.onclick=function(){
				load_work.open_pab(
					pabs_queue[queue_present_index-1]['id'],
					pabs_queue[queue_present_index-1]['path'],
					content_status
				);
				effects.ajax_sw_open_img(id.comm_aj_open_img);
				id.pablication_place.innerHTML='';
				ajax_global_img_for_open_pabs=id.comm_aj_open_img;
			}
			id.f_p_q.onclick=function(){
				load_work.open_pab(
					pabs_queue[queue_present_index+1]['id'],
					pabs_queue[queue_present_index+1]['path'],
					content_status
				);
				effects.ajax_sw_open_img(id.comm_aj_open_img);
				id.pablication_place.innerHTML='';
				ajax_global_img_for_open_pabs=id.comm_aj_open_img;
			}
		}
	},
	
	op_real:function(obj){
		response_functions.pab_tools(obj);
		if(pabs_queue.length!==0)
		response_functions.turn_pabs(obj.pab_info);
		else{
			id.f_p_q.onclick=function(){load_work.hide_all();};
			id.b_p_q.onclick=function(){load_work.hide_all();};
		}
		effects.hide(id.load_word);
		open_pab=true;
	},
	
	open_pab:function(obj){
		var pab=obj.pab_info;
		response_functions.handler(obj,{
			func:function(obj){
				history_api.set_pab(
					obj.pab_info.id,
					obj.pab_info.path,
					obj.pab_info.content_status,
					obj.pab_info.title,
					obj.url
				);
				effects.ajax_sw_open_img(ajax_global_img_for_open_pabs);
				response_functions.op_real(obj);
			},
			not_np_user_func:function(obj){
				if(obj.pab_info)
				history_api.set_pab(
					obj.pab_info.id,
					obj.pab_info.path,
					obj.pab_info.content_status,
					obj.pab_info.title,
					obj.url
				);else{
					if(no_sess)
					load_work.feed();
					else load_work.load_pabs(0,'pabs');
				}
				effects.ajax_sw_open_img(ajax_global_img_for_open_pabs);
				response_functions.op_real(obj);
			},
			error_func:function(){
				if(no_sess)
				load_work.feed();
				else load_work.load_pabs(0,'pabs');
			}
		})
	},
		
	search:function(obj){
		response_functions.handler(obj,{
			func:function(obj){
				if(obj.type_of_search==='aut_id_search'){
					response_functions.load_profile(obj);	
				}else{
					response_functions.load_pabs_for_search(obj);
				}
			}
		});
	},
	
	over:function(elm){
		elm.style.background='#3F4B4F';
		elm.style.color='#fff';
	},
	
	out:function(elm){
		elm.style.background='#fff';
		elm.style.color='#000';
	},
	
	down:function(elm){
		elm.style.background='#000';
		elm.style.color='#fff';
	},
	
	search_choose:function(obj){
		id.search_choose.innerHTML='';
		search_choose_queue_index=-1;
		search_choose_queue=[];
		response_functions.handler(obj,{
			func:function(obj){
				var str='';
				if(obj.interval>0){
					effects.show(id.search_choose);
					for(i=0;i<obj.interval;i++){
						var res=obj.result[i];
						str+="<div class='search_choose_options' id='sco_"+i+"'>"+res+"</div>";
					}
					id.search_choose.innerHTML+=str;
					for(i=0;i<obj.interval;i++){
						var res_s=obj.result[i];
						search_choose_queue[i]=g.i('sco_'+i);
						search_choose_queue[i].onclick=function(){
							id.search_input.value=this.innerHTML;
							system.create_mouse_event(id.search_button,'click');
							effects.hide(id.search_choose);
						}
						search_choose_queue[i].onmouseover=function(){
							response_functions.over(this);
						}
						search_choose_queue[i].onmouseout=function(){
							response_functions.out(this);
						}
						search_choose_queue[i].onmousedown=function(){
							response_functions.down(this);
						}
						search_choose_queue[i].onmouseup=function(){
							response_functions.over(this);
						}
					}
					id.search_input.onkeyup=function(e){
						load_work.dynamic_search(e);
					}
				}else{
					effects.hide(id.search_choose);
				}
			}
		});
	},
	
}
system.add_event(window,'popstate',
function(e){
	history_api.popstate(e);
});
no_sess_func=response_functions.no_sess;
not_able_func=function(){
	if(if_enter_reg)
	system.splash_html({your_info:'',res_div:record.not_able});
	id.res_div.style.marginTop='39px';
	send_functions.enter_reg();
	design.change_class(id.enter_ajax_img,'ajax_img','ajax_stop_img');
	effects.show(id.enter_system);
	effects.hide(id.user_display,id.exit,id.settings);
	id.to_pablicate.innerHTML=record.dem;
	if(id.make_pab){
		system.splash_event_to_elms(
			'onclick',
			function(){
				effects.hidding_message(id.main_ajax_response_place,record.need_join);
			},
			[id.make_pab,
			id.to_copy]
		);
	}
}
