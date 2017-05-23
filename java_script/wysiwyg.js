var NP_wysiwyg=(function(){
	
//////////////count elms&vars////////////////
var o_o_i=0,
qshitind,
tshitind,
tabshitind,
textcount=1,
styleindarray=[0,0,0,0],
quotecount=1,
imgcount=1,
tablecount=1,
moviecount=1,
udtind=0,
f_b_i=record.f_b_i,
s_b_i=record.s_b_i,
ff_b_i=record.ff_b_i,
ss_b_i=record.ss_b_i,
talignarray = [];
SAVE_status=false;
talignarray[record.right_side]='right';
talignarray[record.left_side]='left';
talignarray[record.middle]='center';
///////////////////////////////////////////

panelarray=[id.imgshow,id.tableshow,id.movieshow,id.linkshow,id.quoteshowmodal],
panel_pointer_array=[id.imageinsert,id.tableinsert,id.movieinsert,id.linkinsert,id.quoteinsert],
panel_left_array=[6,10,7,7,7],
open_panel_index=-1;

function panelshow(index){
	if((open_panel_index!==-1)){
		modal.close(panelarray[open_panel_index],id.tdcolor);
		td_sw_index=0;
	}
	if((index!==open_panel_index)||(open_panel_index===-1)){
		open_panel_index=index;
		panelarray[open_panel_index].style.top=tricks.getelmcrd(panel_pointer_array[open_panel_index]).bottom+11;
		panelarray[open_panel_index].style.left=tricks.getelmcrd(panel_pointer_array[open_panel_index]).left-panel_left_array[open_panel_index];
	}else{
		open_panel_index=-1;
	}
}

var tools=[id.move,id.resize,id.text,id.remove,id.natural],
toolsarray=["move","resize","text","remove","natural"],
toolable="text",
NPfont ={};
NPfont.size="17px";
NPfont.family="Times New Roman_";

var NPfamiliesarray=[
"Times New Roman_",
"Tahoma_",
"Arial_",
"Courier_",
"Comic Sans MS_",
"Sylfaen_",
"Verdana_",
"Georgia_",
"Lucida Sans Unicode_",
"Calibri_"],

famelmsarray=[
id.f0,id.f1,id.f2,id.f3,
id.f4,id.f5,id.f6,
id.f7,id.f8,id.f9],

NPtextmini={};
NPtextmini.Bold=0;
NPtextmini.Italic=0;
NPtextmini.Strikethrough=0;
NPtextmini.Underline=0;
var NPtextminicolor="black",
tabprint,
ie=!!top.execScript;

function changetool(tool){
	toolable=tool;
	for (i=0;i<5;i++){
		var t=tools[i];
		if(tool===toolsarray[i]){
			t.className='tools1'
		}else{
			t.className="tools2";
		}
	}
}

function changecoloroftext(){
	var color='rgb('+id.R.value+','+id.G.value+','+id.B.value+')'; 
	id.colorscreen.style.backgroundColor=color;
	NPtextminicolor=color;
}

system.add_event_to_elms([id.R,id.G,id.B],'keyup',function(){changecoloroftext();})
system.add_event_to_elms([id.R,id.G,id.B],'blur',
function(){
	if(id.R.value===''){id.R.value=0;}
	if(id.G.value===''){id.G.value=0;}
	if(id.B.value===''){id.B.value=0;}
	changecoloroftext();
})

id.R.onchange=function(){
	changecoloroftext();
}

id.G.onchange=function(){
	changecoloroftext();
}

id.B.onchange=function(){
	changecoloroftext();
}
			
system.no_s_char(id.thval);
system.no_s_char(id.twval);
system.int_sw([id.R,id.G,id.B,id.tdR,id.tdG,id.tdB]);

var bistuarray=[0,0,0,0],
tstylesarray=["Bold","Italic","Strikethrough","Underline"];

function bolditalicstrikeunder(index){
	document.execCommand(tstylesarray[index],false,null);
	if(window.getSelection().toString==="" && !(ie)){
		bistuarray[index]=(bistuarray[index]===0)?1:0;
	}else if(ie){
		bistuarray[index]=(bistuarray[index]===0)?1:0;
		tricks.noselection();
	}
};

id.colorscreen.onclick=function(){
	if(tc_sw_index===0){
		id.textcolor.style.top=tricks.getelmcrd(id.colorscreen).bottom+11;
		id.textcolor.style.left=tricks.getelmcrd(id.colorscreen).left-44;
		tc_sw_index=1;
	}else{
		modal.close(id.textcolor);
		tc_sw_index=0;
	}
}

id.textfamscreen.onclick=function(){
	if(tfs_sw_index===0){
		effects.show(id.textfamdiv);
		id.textfamdiv.style.top=tricks.getelmcrd(id.textfamscreen).bottom+1;
		id.textfamdiv.style.left=tricks.getelmcrd(id.textfamscreen).left;
		tfs_sw_index=1;
	}else{
		effects.hide(id.textfamdiv);
		tfs_sw_index=0;
	}
};

system.no_s_char(id.tsizeinit);

id.tsizeinit.onkeyup=function(){
	var val=this.value;
	if(val!=="" && val!=="0" && val!=="00" && (val*1)>11){
		id.fixedsize.innerHTML=this.value;
	}else{
		id.fixedsize.innerHTML="12";
	};
	NPfont.size=id.fixedsize.innerHTML+"px";
};

id.tsizeinit.onblur=function(){
	id.tsizeinit.style.display="none";
	id.fixedsize.style.display="block";
}

id.textsizescreen.onclick=function(e){
	id.tsizeinit.style.display="block";
	id.tsizeinit.focus();
	id.fixedsize.style.display="none";
};

function changefamilyoftext(index){
	for (i=0;i<=9;i++){
		var idindex=famelmsarray[i];
		if(i==index){
			NPfont.family=NPfamiliesarray[i];
			var NPfont_sh=NPfont.family.split('_')[0];if(NPfont_sh==='Lucida Sans Unicode'){NPfont_sh='Lusida.S.U'}
			id.textfamscreen.innerHTML="<span style='font-family:"+NPfont.family+"'>"+NPfont_sh+"</span>";
			idindex.className="init1";
		}else{
			idindex.className="ini2";
		}
	}
}

id.tabcolorscreen.onclick=function(e){
	if(td_sw_index===0){
		id.tdcolor.style.top=tricks.getelmcrd(id.tabcolorscreen).bottom-23;
		id.tdcolor.style.left=tricks.getelmcrd(id.tabcolorscreen).left+26;
		td_sw_index=1;
	}else{
		modal.close(id.tdcolor);
		td_sw_index=0;
	}
};
		
function changetdcoloroftext(){
	if(id.tdR.value===''){id.tdR.value=255;}
	if(id.tdG.value===''){id.tdG.value=255;}
	if(id.tdB.value===''){id.tdB.value=255;}
	var color='rgb('+id.tdR.value+','+id.tdG.value+','+id.tdB.value+')'; 
	id.tabcolorscreen.style.backgroundColor=color;
	tabprint=color;
}

system.add_event_to_elms([id.tdR,id.tdG,id.tdB],'keyup',function(){changetdcoloroftext();})
system.add_event_to_elms([id.tdR,id.tdG,id.tdB],'blur',
function(){
	if(id.R.value===''){id.R.value=0;}
	if(id.G.value===''){id.G.value=0;}
	if(id.B.value===''){id.B.value=0;}
	changecoloroftext();
})

id.tdR.onchange=function(){
	changetdcoloroftext();
}

id.tdG.onchange=function(){
	changetdcoloroftext();
}

id.tdB.onchange=function(){
	changetdcoloroftext();
}

//////text///////////////////////////////////////////////////////////////////////// 

function TEXT(elm){	
	elm.onmouseover= function(){
		o_o_i=1;hot_cake(this);
		if(toolable!=="remove")
		this.style.border="1px #454545 dashed";
		if(toolable==="move"){
			this.style.cursor="move";
			tricks.dragdropbymargin(this,id.content);
			this.setAttribute('contenteditable','false');
		}else if(toolable==="resize"){
			this.style.cursor="default";
			tricks.resize(this,20,20); 
			this.setAttribute('contenteditable','false');
		}else if(toolable==="text"){
			this.style.cursor="text";
			tricks.notricks();
			this.setAttribute('contenteditable','true');
		}else if(toolable==="remove"){
				this.style.border="2px red dashed";
				this.style.cursor="default";
				this.setAttribute('contenteditable','false');
		}else if(toolable==="natural"){
				this.style.cursor="default";tricks.notricks();
				this.setAttribute('contenteditable','false');
		}
	}

	elm.onmouseout=function(){
		o_o_i=0;
		this.style.border="1px #454545 dashed";
		tricks.notricks();
	}
	
	elm.onmousedown=function(){
		effects.text_color(NPtextminicolor);
	};
	
	elm.onkeydown=function(){
		effects.text_color(NPtextminicolor);
	}
	
	elm.onclick=function(e){
		if(toolable==="natural"){
			modal.eo(e,id.elms_edit);
			id.teditok.onclick=function(){
				elm.style.textAlign=(talignarray[id.talign.value]);
				elm.innerHTML+=id.symbin.value;
				id.symbin.value="";
				id.tsymbmath.value="";
				id.tsymbgreece.value="";
				id.tsymbarror.value="";
				id.tsymbother.value="";
				modal.close(id.text_edit)
			};
		}else if(toolable==="remove"){
			id.content.removeChild(this);
			if(tshitind)
			clearInterval(tshitind);
		}else if(toolable==="text"){
			elm.style.fontSize=NPfont.size;
			elm.style.fontFamily=NPfont.family;
			this.style.cursor='text';
			for(i=3;i>=0;i--){
				if(styleindarray[i]!==bistuarray[i]){
					document.execCommand(tstylesarray[i], false, null);
					styleindarray[i]=bistuarray[i];
				}
			};
		}
	};

    elm.onpaste=function(){
    	tshitind=setInterval(function(){
    		system.clean_shit(elm)
    	},1000);
    	setTimeout(function(){
    		if(tshitind)
			clearInterval(tshitind);
    	},2500);
    }
}

	
id.textinsert.onclick=function(){
	var NPtext=document.createElement("div");
	NPtext.id="text"+textcount;
	NPtext.className="NPTEXT";
	design.setStyles(NPtext,{
		minHeight:'20px',
		height:'auto',
		width:'208px',
		position:'absolute',
		border:'1px #454545 dashed',
		overflow:'auto',
		display:'block',
		outline:'none',
		padding:'4px',
		wordWrap:'break-word',
		zIndex:textcount,
		marginTop:system.content_size(id.content)
	})
	id.content.appendChild(NPtext);
	id.content.scrollTop = id.content.scrollHeight;
	TEXT(NPtext);
	textcount+=1
} 

//////image///////////////////////////////////////////// 

function IMAGE(elm){
	elm.onmouseover= function(){
		o_o_i=1;hot_cake(this);
		if(toolable!=="remove")
		this.style.border="0px";
		if(toolable==="move"){
			this.style.cursor="move";
			tricks.dragdropbymargin(this,id.content);
		}else if(toolable==="text"){
			this.style.cursor="default";tricks.notricks();
		}else if(toolable==="resize"){
			this.style.cursor="default";
			tricks.resize(this,50,100);
		}else if(toolable=="remove"){
			this.style.cursor="default";
			this.style.border="2px red solid";
		}else if(toolable==="natural"){
			this.style.cursor="default";
			tricks.notricks();
		}
	}
	
	elm.onmouseout=function(){
		o_o_i=0;this.style.border="0px";
		tricks.notricks();
	}
		
	elm.onclick=function(){
		if(toolable==="remove"){
			content.removeChild(this);
		}
	};
} 

id.imgshowbackbut.onclick=function(){
	modal.close(id.imgshow);
	open_panel_index=-1;
}


id.imgshowbut.onclick=function(){
	var imgurlvalue=id.imgurl.value;
	if (imgurlvalue===""){
		errors.form(['imgurl']);
	}else{
		var NPimg= document.createElement("img");
		NPimg.id="img"+imgcount;
		NPimg.className="NPIMAGE";
		design.setStyles(NPimg,{
			height:'300px',
			width:'300px',
			position:'absolute',
			display:'block',
			zIndex:imgcount,
			marginTop:system.content_size(id.content)
			})
		NPimg.setAttribute('src',system.strip_tags(imgurlvalue));
		NPimg.setAttribute('draggable','false');
		id.content.appendChild(NPimg);
		id.content.scrollTop = id.content.scrollHeight;
		toolable="move";
		changetool("move");id.imgurl.value="";
		IMAGE(NPimg);
		modal.close(id.imgshow);
		open_panel_index=-1;
		imgcount+=1;
}}

////quote////////////////////////////////////////////////////

function ema(elm,main_td,aut_td,ind,cur){
	main_td.setAttribute('contenteditable',ind);
	if(aut_td!==undefined)
	aut_td.setAttribute('contenteditable',ind);
	main_td.style.cursor=cur;
	aut_td.style.cursor=cur;
	elm.style.cursor=cur;
}

function QUOTE(elm,main_td,aut_td){
	
	elm.onmouseover= function(){
		o_o_i=1;
		hot_cake(this);
		if(toolable==="move"){
			elm.style.cursor="move";
			tricks.dragdropbymargin(this,id.content);
			ema(elm,main_td,aut_td,'false','move');
		}else if(toolable=="remove"){
			elm.style.cursor="default";
			elm.style.border="2px red solid";
			elm.setAttribute('contenteditable','false');
		}else if(toolable==="text"){
			elm.setAttribute('contenteditable','true');
			ema(elm,main_td,aut_td,'true','text');
		}else{
			elm.style.cursor="default";
			ema(elm,main_td,aut_td,'false','default');
			tricks.notricks();
		}
	}
	
	if(aut_td!==undefined)
	aut_td.onclick=main_td.onclick=function(){
		if(toolable==='text'){
			this.style.fontSize=NPfont.size;
			this.style.fontFamily=NPfont.family;
			this.style.height=NPfont.size;
		}
	}
	
	elm.onclick=function(){
		if(toolable==="remove"){
			id.content.removeChild(this);
		}
	} 
	                        	
	elm.onmouseout=function(){
		o_o_i=0;this.style.border="1px solid #dedede";
		tricks.notricks();
	};
	
	elm.onpaste=function(){
		qshitind=setInterval(function(){
			system.clean_shit(elm)
    	},1000);
    	setTimeout(function(){
    		if(qshitind)
			clearInterval(qshitind);
    	},2500);
    }
} 


id.quoteinsert.onclick=function(){
	NP_wysiwyg.panelshow(4);
	quotetext.onpaste=function(){
		qshitind=setInterval(function(){
			system.clean_shit(quotetext)
    	},1000);
    	setTimeout(function(){
    		if(qshitind)
			clearInterval(qshitind);
    	},2500);
    }
}

id.quoteshowbut.onclick=function(){
	var NPquote=document.createElement("div");
	NPquote.id="quote"+quotecount;
	NPquote.className="NPQUOTE";
	design.setStyles(NPquote,{
		fontfamily:id.quotetext.style.fontFamily,
		position:'absolute',
		display:"table",
		zIndex:quotecount,
		border:'1px solid #dedede',
		padding:'3px',
		background:'#fbfbfb',
		marginTop:system.content_size(id.content)
	})
	NPquote.innerHTML="<table style='font-size:"+
	id.quotetext.style.fontSize+
	";'><tr><td></td><td class='main_td_q' id='main_td_"+
	quotecount+
	"' style='color:#727272;'>&#171;"+
	id.quotetext.innerHTML+
	"&#187;</td><td></td><tr><td></td><td></td><td style='color:#454545;font-size:"+
	id.quotetext.style.fontSize+"' class='aut_td_q' id='td_aut_"+
	quotecount+"' >"+
	system.strip_tags(id.quoteaut.value+'&nbsp;') +
	"</td></tr></table>";
	id.quotetext.innerHTML="";
	id.quoteaut.value="";
	id.content.appendChild(NPquote);
	id.content.scrollTop = id.content.scrollHeight;
	NPquote.style.height=NPquote.offsetHeight;
	NPquote.style.width=NPquote.offsetWidth;
	main_td=g.i('main_td_'+quotecount);
	aut_td=g.i('td_aut_'+quotecount);
	NPquote.style.height='auto';
	QUOTE(NPquote,main_td,aut_td);
	modal.close(id.quoteshowmodal);
	open_panel_index=-1;
	quotecount+=1;
}

id.quotetext.onclick=function(){
	this.style.fontSize=NPfont.size;
	this.style.fontFamily=NPfont.family;
	for(i=3;i>=0;i--){
		if(styleindarray[i]!==bistuarray[i]){
			document.execCommand( tstylesarray[i], false, null);
			styleindarray[i]=bistuarray[i];
		}
	}
}

id.quoteshowbackbut.onclick=function(){
	modal.close(id.quoteshowmodal);
	if(qshitind)
	clearInterval(qshitind);
	open_panel_index=-1;
}

////table/////////////////////////////////////////////////////
	
function set_tr_td_f(table,cur){
	tds=table.getElementsByTagName('td');
	tdl=tds.length;
	for (i=0;i<tdl;i++){
		var td=tds[i];
	    td.style.cursor=cur;
	}
}
	
function TABLE(elm,tabdiv){
	elm.onmouseover= function(){
		o_o_i=1;hot_cake(this);
		if(toolable!=="remove")
		this.style.border="0px";
		if(toolable==="move"){
			set_tr_td_f(elm,'move');
			tricks.dragdropbymargin(tabdiv,id.content);
			system.for_ie_and_ff(tabdiv,this,"false");
		}else if(toolable==="text"){
			set_tr_td_f(elm,'text');
			tricks.notricks();
			system.for_ie_and_ff(tabdiv,this,"true");
		}else if(toolable==="remove"){
			set_tr_td_f(elm,'default');
			this.style.border="2px red solid";
			this.setAttribute('contenteditable','false');
			this.contentEditable="false";
		}else{
			set_tr_td_f(elm,'default');
			tricks.notricks();
			system.for_ie_and_ff(tabdiv,this,"false");
		}
   }
   
	elm.onmouseout=function(){
		o_o_i=0;tricks.notricks();
		this.style.border="0px";
	}
	
	elm.onmousedown=function(){
		effects.text_color(NPtextminicolor);
	}
	
	elm.onkeydown=function(){
		effects.text_color(NPtextminicolor);
	}
	
	tabdiv.onmousedown=function(){
		effects.text_color(NPtextminicolor);
	}
	
	tabdiv.onkeydown=function(){
		effects.text_color(NPtextminicolor);
	}
	
	elm.onclick=function(){
		if(toolable==="remove"){
			id.content.removeChild(tabdiv);
			this.style.cursor="default";
			tricks.notricks();clearInterval(tabshitind);
		}else if(toolable==="text"){
			this.style.fontSize=NPfont.size;
			this.style.fontFamily=NPfont.family;
		}
	}
	
    elm.onpaste=function(){
    	tabshitind=setInterval(function(){
    		system.clean_shit(elm)
    	},1000);
    	setTimeout(function(){
    		if(tabshitind)
			clearInterval(tabshitind);
    	},2500);
    }
		
}

id.tabshowbackbut.onclick=function(){
	modal.close(id.tableshow,id.tdcolor);
	td_sw_index=0;
	open_panel_index=-1;
}

id.tabshowbut.onclick= function (){
	var thvalvalue=id.thval.value,
	twvalvalue=id.twval.value,
	ti=0;
	if ((twvalvalue==="") || isNaN(twvalvalue) || twvalvalue<=0){
		errors.form(['twval']);ti+=1;
	}
	if ((thvalvalue==="")||isNaN(thvalvalue) || thvalvalue<=0){
		errors.form(['thval']);ti+=1;
	}
	if(thvalvalue>20)thvalvalue=20;if(twvalvalue>20)twvalvalue=20;
	if(ti===0){
		var NPtable=document.createElement("table");
		design.setStyles(NPtable,{
			backgroundColor:"white",
			outline:"none",
			borderCollapse:"collapse",
			zIndex:tablecount
		})
		NPtable.id="table"+tablecount;
		var tb=document.createElement('tbody');
		NPtable.appendChild(tb);
		for(i=0;i<thvalvalue;i++){
			var NPtr= NPtable.insertRow(i);
			NPtr.style.border="1px black solid";
			NPtr.border=1;tb.appendChild(NPtr);
			for (j=0;j<twvalvalue;j++){
				var NPtd=NPtr.insertCell(j);
			 	design.setStyles(NPtd,{
			 	width:"auto",
			 	height:"22px",
			 	border:"1px black solid",
			 	padding:'2px'
			})
			NPtd.border=1;
			NPtd.innerHTML="&#8203;&#8197;";
			NPtd.onclick=function(){
				if(toolable==="natural")this.style.backgroundColor=tabprint;
			}
			td_sw_index=0;
		}
	};
	thval.value="";twval.value="" ;
	var tabdiv=document.createElement('div');
	tabdiv.className="NPTABLE";
	design.setStyles(tabdiv,{
		outline:"none",
		position:'absolute',
		zIndex:tablecount,
		float:'left',
		marginTop:system.content_size(id.content)
	})
	tabdiv.appendChild(NPtable);
	id.content.appendChild(tabdiv);
	id.content.scrollTop = id.content.scrollHeight;
	TABLE(NPtable,tabdiv);
	modal.close(id.tableshow,tdcolor);
	open_panel_index=-1;
	tablecount+=1;
	}
}

///////////////////////////////////////////////////////////////////////////

function MOVIE(elm){
	elm.onmouseover= function(){
	hot_cake(this);o_o_i=1;
	if(toolable!=="remove")
	this.style.border="1px #c5c5c5 solid";
	if(toolable==="move"){
		this.style.cursor="move";
		tricks.dragdropbymargin(this,id.content);
		this.setAttribute('title',record.movie_title);
		this.style.height="320px";
		this.style.width="370px";
		if(this.firstChild.style.marginTop==='0px'&&this.firstChild.style.marginLeft==='0px'){
			this.style.marginTop=this.style.marginTop.split('px')[0]*1-35+'px';
			this.style.marginLeft=this.style.marginLeft.split('px')[0]*1-35+'px';
			this.firstChild.style.marginTop='35px';
			this.firstChild.style.marginLeft='35px';
		}
	}else if(toolable=="remove"){
		this.style.cursor="default";
		this.style.border="2px red solid";
		this.style.height="320px";
		this.style.width="370px";
		if(this.firstChild.style.marginTop==='0px'&&this.firstChild.style.marginLeft==='0px'){
			this.style.marginTop=this.style.marginTop.split('px')[0]*1-35+'px';
			this.style.marginLeft=this.style.marginLeft.split('px')[0]*1-35+'px';
			this.firstChild.style.marginTop='35px';
			this.firstChild.style.marginLeft='35px';
		}
		this.setAttribute('title',record.del_title);
	}else {
		this.style.cursor="default";
		tricks.notricks();
		this.style.height="250px";
		this.style.width="300px";
		if(this.firstChild.style.marginTop!=='0px'&&this.firstChild.style.marginLeft!=='0px'){
			this.style.marginTop=this.style.marginTop.split('px')[0]*1+35+'px';
			this.style.marginLeft=this.style.marginLeft.split('px')[0]*1+35+'px';
			this.firstChild.style.marginTop='0px';
			this.firstChild.style.marginLeft='0px';
		}
	}
}

	elm.onclick=function(){
		if(toolable==="remove"){
			id.content.removeChild(this);
		}
	}
	
	elm.onmouseout=function(){
		o_o_i=0;this.style.border="1px #c5c5c5 solid";
		tricks.notricks();
	}
	
}

id.movieshowbackbut.onclick=function(){
	modal.close(id.movieshow);
	open_panel_index=-1;
}

id.movieshowbut.onclick=function(){
	var movieurlvalue=id.movieurl.value;
	if(movieurlvalue!==""){
		if(/www.youtube.com/.test(movieurlvalue)){
			var NPmovie=document.createElement('div');
			NPmovie.async="true";
			NPmovie.id="movie"+moviecount;
			NPmovie.className="NPMOVIE";
			design.setStyles(NPmovie,{
				height:"320px",
			 	width:"370px",
			 	position:'absolute',
			 	border:"1px #c5c5c5 solid",
			 	display:'block',
			 	background:'#f6f6f6',
			 	zIndex:imgcount,
			 	marginTop:system.content_size(id.content)
			 })
			id.movieurl.value="";
			NPmovie.style.zIndex=moviecount;
			var realsrc=movieurlvalue.split(" ")[3].split("\"")[1];
			movieytiframe=document.createElement('iframe');
			design.setStyles(movieytiframe,{
				height:"250px",
			 	width:"300px",
			 	marginTop:"35px",
			 	marginLeft:"35px"
			})
			movieytiframe.setAttribute('src',realsrc);
			movieytiframe.src=system.strip_tags(realsrc);
			movieytiframe.frameBorder="0";
			movieytiframe.setAttribute('frameborder','0');
			movieytiframe.setAttribute('allowfullscreen',true);
			id.content.appendChild(NPmovie);
			id.content.scrollTop = id.content.scrollHeight;
			NPmovie.appendChild(movieytiframe);
			modal.close(id.movieshow);
			open_panel_index=-1;
			MOVIE(NPmovie);
			changetool("move");
			moviecount+=1;
		}else {
			errors.form(['movieurl']);
		}
	}else{
		errors.form(['movieurl']);
	}
}

////////////////////////////////////////////////

function selin(select,input){
	input.value+=select.value;
};

id.elms_edit.onmouseout=function(){
	modal.close(this)};
	id.elms_edit.onclick=function(e){
    modal.open_free(
    	e,id.text_edit,id.teditmoveplace,id.teditclose,npbody
    )
};

id.tsymbmath.onchange=function(){
	selin(this,id.symbin)
};
id.tsymbgreece.onchange=function(){
	selin(this,id.symbin)
};
id.tsymbarror.onchange=function(){
	selin(this,id.symbin)
};
id.tsymbother.onchange=function(){
	selin(this,id.symbin)
};
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function o_y_b(elm){
	if(o_o_i===1){def_bord(elm);
		system.create_mouse_event(elm,'mouseover');
	};
}

function def_bord(elm,bs){
	system.create_mouse_event(elm,'mouseout');
	system.create_mouse_event(elm,'mouseover');
}
	
function hot_cake(elm){
	document.onkeydown=function(e){
		e=e||event;
		if(e.ctrlKey){
			var ek=e.keyCode;
			if (ek == '1'.charCodeAt(0)){
				changetool('move');
				if(o_o_i===1){
					def_bord(elm);
				};
				return false;
			}else if(ek=='2'.charCodeAt(0)){
				changetool('resize');o_y_b(elm);
				return false;
			}else if(ek=='3'.charCodeAt(0)){
				changetool('text');o_y_b(elm);
				return false;
			}else if(ek=='4'.charCodeAt(0)){
				changetool('remove');o_y_b(elm);
				return false;
			}else if(ek=='5'.charCodeAt(0)){
				changetool('natural');o_y_b(elm);
				return false;
			}
		}
	}
};

//////link////////////////////////////////////////////////////

function createLink(href){
	if(window.getSelection && window.getSelection().toString()===""){
		errors.form(['linkurl']);
	}else{
		document.execCommand('CreateLink',
		false,system.strip_tags(id.linkurl.value));
		document.execCommand('forecolor',false,'#0000FF');
		document.execCommand("fontName", false,NPfont.family);
		system.insert_char('&nbsp;');
		id.linkurl.value="";
		modal.close(id.linkshow);
		open_panel_index=-1;
	}
};

id.linkshowbut.onclick=function(){
	var linkval=id.linkurl.value;
	if(linkval ==="" || linkval ==="http://" || linkval ==="https://" ){
		errors.form(['linkurl']);
	}else{
		createLink(linkval,id.linkurl);
	}
};

id.linkshowbackbut.onclick=function(){
	modal.close(id.linkshow);
	open_panel_index=-1;
}

hot_cake(npbody);

function d_tool_color(table){
	tds=table.getElementsByTagName('td');
	tdl=tds.length;
	if(tdl===1){
		td=tds[0];
		td.onclick=function(){
			if(toolable==="natural")this.style.backgroundColor=tabprint;
		}
	}else{
		for (i=0;i<tdl;i++){
			td=tds[i];
			td.onclick=function(){
				if(toolable==="natural")this.style.backgroundColor=tabprint;
			}
		}
	}

}

id.pmerrpl.onclick=function(){
	if(more_info_sw_index===0){
		effects.show(id.more_info);
		more_info_sw_index=1;
	}else{
		effects.hide(id.more_info);
		more_info_sw_index=0;
	}
	effects.switch_images(id.aj_e_p,'http://paper-blog.ru/img/down2.png','http://paper-blog.ru/img/up2.png');
}

system.add_event_to_elms([id.make_pab,id.to_copy],
	function(){
		effects.switch_images(id.aj_e_p,'http://paper-blog.ru/img/down2.png','http://paper-blog.ru/img/up2.png');
});

id.save_pab_button.onclick=function(){
	if(p_sw_index===0){
		id.pablicatemodal.style.top=tricks.getelmcrd(id.save_pab_button).bottom+11;
		id.pablicatemodal.style.left=tricks.getelmcrd(id.save_pab_button).left-221;
	    p_sw_index=1;
	}else{
		modal.close(id.pablicatemodal);
		p_sw_index=0;
	}
}

id.preview.onclick=function(){
		var r=255,g=255,b=145,sid;
		id.content.style.background="rgb("+r+","+g+","+b+")";
		if(sid){
			clearInterval(sid);
		}
		sid=setInterval(
			function(){
				if(b!==255)
				b+=10;
				id.content.style.background="rgb("+r+","+g+","+b+")";
				if(b===255)clearInterval(sid);
			},40);
			system.defaultelms(id.content);
}

function OverClassParser(elm){
	var tags=g.et(elm),
	len=tags.length;
	for(i=0;i<len;i++){
		var ti=tags[i];
		var class_name=ti.getAttribute('class');
		if(class_name){
			if(ti.classList.contains('NPTEXT')){
				TEXT(ti);
			}else if(ti.classList.contains('NPQUOTE')){
				main_td=g.ec(ti,'main_td_q')[0];
				aut_td=g.ec(ti,'aut_td_q')[0];
				QUOTE(ti,main_td,aut_td);
			}else if(ti.classList.contains('NPIMAGE')){
				IMAGE(ti);
			}else if(ti.classList.contains('NPTABLE')){
				var t=ti.getElementsByTagName('table')[0];
				TABLE(t,ti);
				d_tool_color(t);
			}else if(ti.classList.contains('NPMOVIE')){
				MOVIE(ti);
			}
		}
	}
}

return{
	changetool:changetool,
	bisu:bolditalicstrikeunder,
	changefamilyoftext:changefamilyoftext,
	changecoloroftext:changecoloroftext,
	panelshow:panelshow,
	OverClassParser:OverClassParser
	};
})();
NP_wysiwyg.OverClassParser(id.content);
OverClassParser=function(){
	NP_wysiwyg.OverClassParser(id.content);
}
