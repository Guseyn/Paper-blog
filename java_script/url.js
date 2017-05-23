var h_api=!!(window.history && history.pushState),
global_title=false;

var history_api={
	
	set_pab:function(pab_id,path,content_status,title,url){
		history.pushState({
			pab_id:pab_id,
			path:path,
			content_status:content_status,
			title:title
		},
		title,url
		);
		document.title=title;
	},
	
	set_profile:function(user_id,content_status,need_fn,url){
		var gt_cs; 
		if(content_status==='pabs'){
			gt_cs=record.title_pubs;
		}else if(content_status==='copies'){
			gt_cs=record.title_copies;
		}else if(content_status==='faves'){
			gt_cs=record.title_faves;
		}else if(content_status==='fans'){
			gt_cs=record.title_readers;
		}else if(content_status==='auts'){
			gt_cs=record.title_authors;
		}
		title=user_name+' | '+gt_cs;
		history_obj_page_status={
			user_id:user_id,
			content_status:content_status,
			need_fn:need_fn,
			url:url
		};
		history.pushState({
			user_id:user_id,
			content_status:content_status,
			need_fn:need_fn,
			title:title
		},
		title,url
		);
		document.title=title;
	},
	
	set_query:function(query,type_of_search,url){
		history_obj_page_status={
			type_of_search:type_of_search,
			search_input:query,
			title:query,
			url:url
		};
		history.pushState({
			type_of_search:type_of_search,
			search_input:query,
			title:query
		},
		query,url
		);
		document.title=query;
	},
	
	set_feed:function(url){
		history_obj_page_status={
			feed:true,
			url:url
		};
		history.pushState({
			feed:true,
			title:record.feed
		},
		record.feed,
		url
		);
		document.title=record.feed;
	},
		
	load_profile:function(user_id,content_status,need_fn){
		ajax.post(true,'profile.php','to_profile',
		{
			user_id:user_id,
			content_status:content_status,
			need_fn:need_fn
		},
		function(obj){
			history_api.resposne_profile(obj);
		});
	},
	
	load_feed:function(){
		next_load_step=-1;
		ajax.post(false,'feed.php','to_feed',
		{next_load_step:next_load_step},
		function(obj){
			history_api.load_pabs(obj);
			console.log("load_feed_in_url");
		});
	},
	
	popstate:function(e){
		if(open_redactor)
		effects.hide(id.pablicate_place);
		else if(open_settings)
		modal.close(id.settingmodal);
		if(e.state&&start){
			global_title=e.state.title;
			if(e.state.pab_id&&e.state.path&&e.state.content_status){
				ajax.post(true,'open_pab.php','to_open_pab',
				{
					pab_id:e.state.pab_id,
					path:e.state.path,
					content_status:e.state.content_status
				},
				function(obj){
					history_api.open_pab(obj);
				});
			}else{
				if(e.state.user_id&&e.state.content_status){
					if((e.state.content_status==='auts')||
					(e.state.content_status==='fans')){
						history_api.load_profile(
							e.state.user_id,
					    	e.state.content_status,
					    	false
					    );
					}else{
						history_api.load_profile(
					    e.state.user_id,
					    e.state.content_status,
					    e.state.need_fn
					    );
					}
			    }else if(e.state.search_input){
			    	history_api.auto_search(e.state);
			    }else if(e.state.feed){
				   	  history_api.load_feed();
				}
				
				if(id.layer.style.display!=='none'){
				   history_api.hide_all();
				}
			}
		}else{
			start=true;
			first_pop_state=true;
		}
		npbody.style.overflowY='scroll';
	},
	
	open_pab:function(obj){
		var pab=obj.pab_info;
		response_functions.pab_tools(obj);
		if(pabs_queue.length!==0)
		response_functions.turn_pabs(pab);
		effects.hide(id.load_word);
		document.title=global_title;
	},
	
	resposne_profile:function(obj){
		response_functions.handler(obj,{
			func:function(){
				history_api.profile(obj.profile);
			}
		});
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
				}
				if(obj.list_data){
					history_api.load_auts_fans_list(obj);
				}else if(obj.pabs_data){
					history_api.load_pabs(obj);
				}
				document.title=global_title;
			},
			no_result_func:function(obj){
				if(obj.pabs_data!==undefined)
				load_work.default_params(obj.pabs_data.content_status);
				id.user_common_info.style.height='33px';
				effects.show(id.no_result,id.u_i);
				effects.hide(id.next_results,id.not_your_info);
				if(obj.request!==undefined)
				system.splash_html({
					res_div:'',
					no_result:'Профиль не найден',
					your_info:obj.request
				});
				else system.splash_html({
					res_div:'',no_result:record.no_result
				});
				document.title=global_title;
			}
		});
	},
	
	hide_all:function(){
		effects.hide(
			id.layer,id.pab_show_panel,
			id.pablication_tools_place,
			id.load_word
		);
		modal.close(
			id.deletemodal,id.crmodal,
			id.wrcrmodal,id.settingmodal
		);
		npbody.style.overflowY='auto';
		if(id.wholeworkplace){
			id.content.innerHTML='';
			id.title.value='';
			effects.src(id.ava,record.ppic);
			effects.hide(id.pablicate_place,
				id.pmerrpl,id.more_info);
			modal.close(id.textcolor,id.textfamdiv,
				id.pablicatemodal,id.text_edit,id.tdcolor);
			if(open_panel_index!==-1)
			modal.close(panelarray[open_panel_index]);
			tfs_sw_index=0;tc_sw_index=0;
			td_sw_index=0;p_sw_index=0;
			open_panel_index=-1; 
		}
		if(open_pab){
			id.pablication_place.innerHTML='';
		}
		for_remake.content_status='pabs';
		for_remake.pab_id=0;
		for_remake.ava='';
		for_remake.title='';
		for_remake.pab='';
	},
	
	load_pabs:function(obj){
		response_functions.handler(obj,{
			func:function(obj){
				response_functions.pabs(obj.pabs_data);
				document.title=global_title;
			}
		});
	},
	
	load_auts_fans_list:function(obj){
		response_functions.handler(obj,{
			func:function(obj){
				response_functions.auts_fans_list(obj.list_data);
			}
		})
	},
	
	auto_search:function(state){
		next_load_step=-1;
		search_to_history=false;
		auto_search=true;
		system.create_mouse_event(g.c(state.type_of_search)[0],'click');
		id.search_input.value=state.search_input;
		system.create_mouse_event(id.search_button,'click');
	},
	
	url_to_page:function(wl){
		url=wl.split('?')[1];
		if(url){
			components=url.split('&'),
			l=components.length,
			keys=[],values=[];
			for(i=0;i<l;i++){
				var expression=components[i].split('=');
				keys[i]=expression[0];
				values[i]=decodeURIComponent(expression[1]);
			}
			if(system.in_array(keys,'pab_id')){
				load_work.open_pab(
					values[0],
					values[1],
					values[2]
				);
			}else if(system.in_array(keys,'profile')){
			    load_work.load_profile(
			        values[0],
			        values[1],
			        values[2]
			    );
			    start=true;
			}else if(system.in_array(keys,'query')){
				next_load_step=-1;
				search_to_history=true;
				auto_search=false;
				start=true;
				system.create_mouse_event(g.c(values[1])[0],'click');
				id.search_input.value=values[0];
				system.create_mouse_event(id.search_button,'click');
			}else if(system.in_array(keys,'feed')){
				load_work.feed();
				start=true;
			}else{
			     id.res_div.style.marginTop='38px';
			     id.res_div.innerHTML=record.access_error;
			}
		}else{
			if(no_sess)
			load_work.feed();
			else load_work.load_pabs(0,'pabs');
			start=true;
		}
	}
	
}