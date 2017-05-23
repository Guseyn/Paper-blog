<! Doctype html>
<html lang="en-US">
	<head>
		<title> Paper Blog is a new way to blogging </title>
		<meta http-equiv='content-type' content='text/html; charset=utf-8'>
		<meta name="Description" content="Paper Blog will allow you to plunge into makroboging, providing convenient intrumenty for writing articles">
		<meta content="Paper Blog is a new way to blogging ">
		<meta name="document-state" content="dynamic">
		<meta name="robots" content="ALL"> 
		<link id="icon" href="http://paper-blog.ru/img/icon_new2.ico?123456" rel="shortcut icon" type="image/x-icon/gif" />
		<link href="http://paper-blog.ru/css/main.css" rel="stylesheet" type="text/css">	
		<link href="http://paper-blog.ru/css/redactor.css" rel="stylesheet" type="text/css">
		<script src =  "http://paper-blog.ru/java_script/url.js" type="text/javascript" > </script>
		<script src =  "http://paper-blog.ru/java_script/tricks.js" type="text/javascript" > </script>
		<script src =  "http://paper-blog.ru/java_script/n_p.js" type="text/javascript" > </script>
		<script src =  "http://paper-blog.ru/java_script/ajnp.js" type="text/javascript" > </script>
		<script>
		(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
			(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
			m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		})(window,document,'script','//www.google-analytics.com/analytics.js','ga');
		ga('create', 'UA-55204430-1', 'auto');
		ga('send', 'pageview');
		</script>
		<script>
		if(!h_api){
				window.location='http://paper-blog.ru/bad_brow.html';
		}else{
			if(web_drivers.mobile){
				var wl=window.location.toString(),
				cps=wl.split('?')[1];
				if(cps!==undefined)
				window.location='http://m.paper-blog.ru/?'+cps;
				else window.location='http://m.paper-blog.ru';
			}
		}
		</script>
      <!-- Put this script tag to the <head> of your page -->
      <script type="text/javascript" src="http://vk.com/js/api/share.js?90" charset="windows-1251"></script>
	</head>
	<body style="height:100%;width:100%;">
		
		<noscript>
			<div class='bbm' style="display:block;">
			Please enable javascript.
			</div>		
		</noscript>
		
		<div id="hm" class="bbm">
			Enable cookies.
		</div>	
		
		<div id='main_ajax_response_place'></div>
		
		<div id='layer'></div>
		
		<div id='load_word'>Load...</div>
		
		<div id="modal_space"></div>
		
		<div id="settingmodal" class="modal">
			<div id="settingmove"  class="interface moveplace">
				<span  class="modaltitle">Setting</span>
				<div class="ajax_stop_img"></div>
				<img id="settingclose" class="modalclose" src='/img/del_u_a.png' title="Close"></img>
				</div>
				<div id="settinginfo" class="infoplace">
					<div class="atl" style="margin-top:5px;margin-bottom:15px;float:left;" >
						<input class="enter_able"  id="change_old_pword" placeholder="You must enter your password" type="password"></div>	
					<div class="atl"><input id="change_name"  placeholder="New name" type="text" maxlength="30"></div>
					<div class="atl" style="margin-top:5px;"><input  id="change_fname" class="enter_able" placeholder="New last name" type="text" maxlength="30"></div>
					<div class="atl" style="margin-top:5px;"><input  id="change_login" class="enter_able" placeholder="New login" type="text" maxlength="30"></div>
					<div class="atl" style="margin-top:5px;margin-bottom:20px;float:left;" ><input class="enter_able"   id="change_email" placeholder="New email" type="text" maxlength="50"></div>
					<div class="atl" style="margin-bottom:5px;float:left;" ><input class="enter_able"  id="change_pword" placeholder="New password" type="password"></div>
					<div class="atl" style="margin-bottom:10px;float:left;"><input class="enter_able"  id="change_pword2" placeholder="Repeat password" type="password"></div>
				<button class="button_interface" id="save_changes" type="submit" style="margin-right:10px;margin-top:0px;">
					<span>Save<span></button>
					<span id="settingep" class="errorresponseplace" style="font-size:15px;"></span>
					<div id='set_err'></div>
				</div>
			</div>
			
			<div id="deletemodal" class="modal" style="z-index:3100;">
				<div id="deletemove"  class="interface moveplace_m">
					<span  class="modaltitle">Remove publication</span>
					<img src="/img/ajaxstop.gif" id="deleteajaxloadimg" class="ajaxplace">
					<img id="deleteclose" class="modalclose m_opacity" src='/img/del_u_a.png' title="Close">
				</div>
				<div id="deleteinfo" class="infoplace">
					<p>Do you really want to remove this publication?</p>
					<div id="del_div_res">
					</div>
					<button class="styletextout button_interface" id="del_yes"><span>Yes</span></button>
					<button class="styletextout button_interface" id="del_no" onclick="modal.close(g.i('deletemodal'));g.i('layer').style.zIndex='1000';nohideall=false; "><span>No</span></button>
				</div>
			</div>
			
			<div id="clredmodal" class="modal" style="z-index:3100;">
				<div id="clredmove"  class="interface moveplace_m">
					<span  class="modaltitle">Close wysiwyg</span>
					<img src="/img/ajaxstop.gif" id="clredajaxloadimg" class="ajaxplace">
					<img id="clredclose" class="modalclose m_opacity" src='/img/del_u_a.png' title="Close">
				</div>
				<div id="clredinfo" class="infoplace">
					<p>Are you sure you want to continue without saving?</p>
					<div id="clred_div_res">
					</div>
					<button class="styletextout button_interface" id="clred_yes"><span>Yes</span></button>
					<button class="styletextout button_interface" id="clred_no" onclick="modal.close(g.i('clredmodal'));g.i('layer').style.zIndex='1000';nohideall=false; "><span>No</span></button>
				</div>
			</div>
			
			<div id="complainmodal" class="modal" style="width:410px;z-index:3100;">
				<div id="complainmove" class="interface moveplace" style="width:410px;height:20px;">
					<span class="modaltitle">Complain</span>
					<img src="/img/ajaxstop.gif" id="complainajaxloadimg" class="ajaxplace">
					<img id="complainclose" class="modalclose m_opacity" src='/img/del_u_a.png' title="Close">
				</div>
				<div id="complaininfo" class="infoplace" style="width:auto;margin-left:5px;margin-top:5px;padding-bottom:4px;">
					<div><textarea id='reason' placeholder="Enter a reason for complaint" style="width:398px;height:100px;resize:none;margin-top:4px;margin-left:1px;"></textarea></div>
					<button id="complain_ok" class="button_interface" style="margin-top:5px;margin-left:1px;"><span>Complain</span></button>
				</div>
			</div>
			
			<div id="crmodal" class="modal">
				<div id="crmove"  class="interface moveplace">
					<span  class="modaltitle">Remarks</span>
					<img src="/img/ajaxstop.gif" id="crajaxloadimg" class="ajaxplace">
					<img id="crclose" class="modalclose m_opacity" src='/img/del_u_a.png' title="Close">
				</div>
				<div id="crinfo" class="infoplace">
					<div id="cr_text"></div>	
					<button class="styletextout button_interface m_opacity" id="cr_clear"><span>Clean all</span></button>
				</div>
			</div>
			
			<div id="wrcrmodal" class="modal">
				<div id="wrcrmove"  class="interface moveplace">
					<span  class="modaltitle">Write,    rewrite a remark</span>
					<img src="/img/ajaxstop.gif" id="wrcrajaxloadimg" class="ajaxplace">
					<img id="wrcrclose" class="modalclose m_opacity" src='/img/del_u_a.png' title="Close">
				</div>
				<div id="wrcrinfo" class="infoplace">
					<textarea id="wrcr_text" placeholder="Ваше замечание" style="resize: none;"></textarea>	
					<button class="styletextout button_interface" id="wrcr_clear"><span>Delete</span></button>
					<button class="styletextout button_interface" id="wrcr_write"><span>Send</span></button>
					</div>
				</div>
				
				<div id="wysiwyg_modals"></div>
				
				<div id='corner_error_display'></div>	
				
				<div id="pablicate_place"></div>
				
				<div id="pab_show_panel">
					<div class='turn_pab_2' id='b_p_q' draggable="false"><img src='http://paper-blog.ru/img/lqr.png' id="lqr" draggable="false"></div>
					<div class='no_open_pab_ajax_img' id='comm_aj_open_img' style="z-index:2550;margin-right:50%;"></div>
					<div id="pablication_place" draggable="false"></div>
					<div class='turn_pab_2' id='f_p_q' draggable="false"><img src='http://paper-blog.ru/img/pqr.png' id="pqr" draggable="false"></div>
					<div id="pablication_tools_place">
						<div id="aut_static_info" style="font-size:15px;float:left;margin-top:8px;background:white;">
							<div style="float:left;margin-left:8px;border:1px solid #dedede;padding:3px;height:auto;">
								<div style="margin-top:2px">Title: <span id='title_info'></span></div>
								<div style="margin-top:2px;">Author: <span id='fn_info'></span></div>
								<div id="vq_info_div" style="margin-top:2px">Number of views: <span id='vq_info'></span></div>
								<div style="margin-top:2px">Latest editing: <span id='date_info'></span></div>
							</div>
						</div>
						<div id='pab_manipulation_tools' >
							<div id="to_remake_add_to_elect" class="pspdivs" style="margin-top:6px;">Edit</div>
							<div id="to_remark_to_read_remark" class="pspdivs" style="margin-top:6px;">Write a remark</div>
							<div id="to_remove_to_complain" class="pspdivs" style="margin-top:6px;">Remove</div>
						</div>
						<div id="pab_open_tools" style="float:right;margin-right:10px;margin-top:12px;width:auto">
							<div>
								<button id='like' class="interface">
									<img src='http://paper-blog.ru/img/pd.png' id="like_img">
								</button>
								<span id="like_quans"></span>
							</div>
							<div style="margin-top:4px;">
								<button id='dislike' class="interface">
									<img src='http://paper-blog.ru/img/md.png' id="dislike_img" >
								</button>
								<span id="dislike_quans"></span>
							</div>
						</div>
					</div>
				</div>
				
				<div id='main_div'>
					<div id='top' class="interface">
						<img id='logo' src='http://paper-blog.ru/img/logo_v1.png' style="float:left;margin:-2px 10px;">
						<span style="float:left;margin-left:-1px;">Paper Blog</span>	
					</div>
						<div id='links_line'>
							<span  class="link_ind_1 links l_m_h_opacity"  id="aproj">
								<a href="http://paper-blog.ru/?pab_id=2&path=pabs/1Oproekte484.html&content_status=pabs" >
									About project
								</a>
							</span>
							<span class="link_ind_1 links l_m_h_opacity" id="to_pablicate" style="display:block;">Demonstration of wysiwyg</span>
							<span class="link_ind_1 links l_m_h_opacity" id="settings">Settings</span>
							<span  class="link_ind_1 links l_m_h_opacity"  id="feed_link">Feed</span>
							<span  class="link_ind_1 links l_m_h_opacity"  id="exit">Log out</span>
						</div>
						<div id="user_div_back">
							<div id='user_div' class="di">
								<div id='search_div' >
									<div id='search_input_div'>
										<img id="search_ajax_img" class="ajax_stop_img" src="http://paper-blog.ru/img/ajaxstop.gif">
										<input type='text' id='search_input' class="" placeholder="Search">
										<div id='search_choose'></div>	
										</div>	
										<div>
											<button type="button" class='styletextout button_interface' id='search_button' value="Search"><span>Search</span></button>
											<table id="type_of_search"> 
												<tr>
													<td id='value_of_type_search' class="title_search">
														By titles
													</td>
												</tr>	
													<tr  style="display:none;">
														<td class="aut_search">
															By authors
														</td>
													</tr>
													<tr  style="display:none;">
														<td class="aut_id_search" >
															By id of author
														</td>
													</tr>
												</table>
												</div>
											</div>
											
											
											<div id="enter_system">
												<span  class="modaltitle">Sign in</span> 
												<div id='enter_ajax_img' class='ajax_stop_img'></div>
												<input class="enter_able" type="text" id="enterloginemail" maxlength="30" placeholder="Login or email">
												<input class="enter_able" type="password" id="enterpword" placeholder="Password">
												<div id='entererrorres' class="errorresponseplace"></div>
												<button type="button" id="enterok" class="button_interface" ><span>sing in</span></button>
												<span id="forget_pword" style="float:left;margin-left:8px;margin-top:7px;text-decoration:underline;cursor:pointer;font-size:13px;">Forgot your password?</span>
												<button id="to_reg" style="clear:left;margin-top:4px;width:183px;" class="button_interface"><span>Sign up</span></button>
											</div>
												
												<div id="change_fpword_modal">
													<span  class="modaltitle" style="font-size:14px;">Password recovery</span> 
													<div id='chfpw_ajax_img' class='ajax_stop_img'></div>
													<img id="chfpwclose" class="modalclose m_opacity" src='http://paper-blog.ru/img/close.png' title="Close">
													<input class="enter_able" type="text" id="change_fpw_input" maxlength="30" placeholder="Access code">
													<input class="enter_able" type="password" id="change_fpword" placeholder="Password">
													<input class="enter_able" type="password" id="change_fpword2" placeholder="Confirm the password">
													<div id='fpword_errorres' class="errorresponseplace" style="margin-top:2px;margin-left:2px;width:182px;"></div>
													<button type="button" id="chfpw_ok" class="button_interface" style="clear:left;" ><span>Restore password</span></button>
												</div>
												
												<div id="email_for_code">
													<span  class="modaltitle">Enter Email</span>
													<div id='email_for_code_img' class='ajax_stop_img'></div>
													<img id="email_for_code_close" class="modalclose m_opacity" src='http://paper-blog.ru/img/close.png' title="Close">
													<input class="enter_able" type="text" id="email_for_code_input" placeholder="Your Email">
													<button type="button"  id="email_for_code_ok" class="button_interface" style="clear:left;" ><span>Get a code</span></button>	
												</div>
												
												<div id="registration">
													<span  class="modaltitle">Registration</span>
													<div id='reg_ajax_img' class='ajax_stop_img'></div>
													<img id="regclose" class="modalclose m_opacity" src='http://paper-blog.ru/img/close.png' title="Close">
													<img src="http://paper-blog.ru/img/ajaxstop.gif" id="regajaxloadimg" class="ajaxplace">
													<input class="enter_able" type="text" id="regname" maxlength="50" placeholder="Name">
													<input class="enter_able" type="text" id="regfname" maxlength="50" placeholder="Last name">
													<input class="enter_able" type="text" id="reglogin" maxlength="30" placeholder="Login">
													<input class="enter_able" type="text" id="regemail" maxlength="50" placeholder="Email" disabled="true">
													<input class="enter_able" type="password" id="regpword" placeholder="Passport">
													<input class="enter_able" type="password" id="regpword2" placeholder="Confirm the password">
													<input class="enter_able" type="text" id="reg_code" placeholder="Accsess code for Sign up">
													<img id='captcha' src=''>
													<input class="enter_able" type="text" id="captcha_input" placeholder="Enter text from the picture" style="width:159px;float:left;font-size:14px;">
													<button  id="re_captcha" style="width:22px;font-weight:bold;float:left;height:23px;font-size:14px;" type="button" class="button_interface no_ajax" title="Обновить картинку"> &#8634;</button>
													<button type="button"  id="regok" class="button_interface" ><span>Sign up</span></button>
													</div>
												<div id="errorres" style="margin-bottom:5px;" class="errorresponseplace"></div>
												
												<div id='user_display' class="di">
													<div style=""><span id='fn'></span></div>
													<div id='my_pabs' style="clear:left;" class="div_ind_1 pointer i_out">My publications<div id="my_pabs_ajax_img" class="ajax_stop_img my_aj"></div></div>
													<div id='my_copies' class="div_ind_1 pointer i_out">My achievements<div id="my_copies_ajax_img" class="ajax_stop_img my_aj"></div></div>
													<div id='my_faves' class="div_ind_1 pointer i_out">My fave publications<div id="my_faves_ajax_img" class="ajax_stop_img my_aj"></div></div>
													<div id='my_fans' class="div_ind_1 pointer i_out">My readers<div id="my_fans_ajax_img" class="ajax_stop_img my_aj"></div></div>
													<div id='my_auts' class="div_ind_1 pointer i_out">My authors<div id="my_auts_ajax_img" class="ajax_stop_img my_aj"></div></div>	
													<div id='load_feed' class="div_ind_1 pointer i_out">Feed<div id="feed_ajax_img" class="ajax_stop_img my_aj"></div></div>
													</div>
												</div>
												
												<div id="c_c_div">
													
													<div id='user_common_info'>
														<div id='u_i'>
															<div id='your_info' style="margin:9px 8px;font-size:17px;float:left;"></div>
															<div id="u_ajax_img" class="ajax_stop_img" style="margin-top:9px;"></div>
														</div>
														<div id='not_your_info'>
															<div style="margin-bottom:10px;float:left;">
																<span id='loaded_fn_info' style="float:left;"></span>
																<div id="n_u_ajax_img" class="ajax_stop_img" style="margin-top:6px;"></div>
															</div>
															<div style="clear:left;">
																<span class="not_you_load_span_selected" id='not_your_pabs' style="margin-left:0px;">Publications</span>
																<span class="not_you_load_span" id='not_your_faves'>Fave publications</span>
																<span class="not_you_load_span" id='not_your_fans'>Readers</span>
																<span class="not_you_load_span" id='not_your_auts'>Authors</span>
																<button id="add_to_auts" class="interface" style="position:relative;"><img id='add_to_auts_img' src='http://paper-blog.ru/img/fd.png'></button>
															</div>
														</div>
													</div>
													
													<div id="no_result" ></div>
													<div id='load_pabs_ajax_img'></div>
													<div id='res_div'></div>
													
													<div id="next_results">
														<img id='next_res_ajax_img' src='http://paper-blog.ru/img/ajaxstop.gif'>
														<img id='i_next_res' src='http://paper-blog.ru/img/down2.png'>
														<span id="next_span">Load next results<span>
													</div>
													<div style="height:42px;margin-left:-1px;width:668px;background:white;bottom:0;position:absolute;border-top:1px solid #cecece;z-index:100;"></div>
												</div>
									
									
								</div>
							</div>
						</div>
						
						<script>
						var npbody=document.body||document.documentElement,
						record = record_en,
						id=system.domStart(id,npbody),
						nph=npbody.offsetHeight,
						npw=npbody.offsetWidth;
						id.npbody=npbody;
						id.nph=nph;id.npw=npw;
						send_functions.start_load();
						system.add_event(id.layer,'click',
						function(){
							load_work.hide_all();
						});
						</script>
						
						</body>
					</html>
