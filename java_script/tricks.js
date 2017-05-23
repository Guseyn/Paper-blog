var tricks=(function(){
	
var scroll={
	top:function(){
	return window.pageYOffset 
	|| document.documentElement.scrollTop 
	|| document.body.scrollTop;
	},
	left:function(){
		return window.pageXOffset 
		|| document.documentElement.scrollLeft 
		|| document.body.scrollLeft;
	}
}

function getelmcrd(elm){
	var rect = elm.getBoundingClientRect(),
	clientTop = document.documentElement.clientTop 
	|| document.body.clientTop || 0,
	clientLeft = document.documentElement.clientLeft 
	|| document.body.clientLeft || 0,
	top=rect.top - clientTop+scroll.top(),
	left=rect.left - clientTop+scroll.left(),
	bottom=rect.bottom - clientTop+scroll.top(),
	right=rect.right - clientTop+scroll.left();
	return {
		 top: Math.round(top),
		 left: Math.round(left),
		 bottom: Math.round(bottom),
		 right: Math.round(right)
	};
}

function stopprocessing(elm){
	document.onmouseup=function(){
		document.onmousemove=null;
	}
};

function notricks(){
	document.body.onselectstart=function() { 
		return true;
	}
	document.onmousedown=function(){
		document.onmousemove=null;
	}
}

function noselection(){
	if(window.getSelection){
		window.getSelection().removeAllRanges();
	}
}

function dragdrop(elm,rectelm){
	var able=getelmcrd(rectelm);
	var topable = able.top+scroll.top(),
	leftable=able.left+scroll.left(),
	bottomable=topable+rectelm.offsetHeight,
	rightable=leftable+rectelm.offsetWidth,
	h=elm.offsetHeight,w=elm.offsetWidth;
	document.onmousedown=function(event){
		var e=event||window.event;
		if((e.which && e.which==1)||(e.button && e.button==1)){
			var elmcrd=getelmcrd(elm),
			elmcrdtop=elmcrd.top,
			elmcrdleft=elmcrd.left,
			Ystartmove=e.clientY,
			Xstartmove=e.clientX,
			Yable=Ystartmove-elmcrdtop,
			Xable=Xstartmove-elmcrdleft;
			document.onmousemove=function(event){
				noselection();
				var e=event||window.event;
				var Y=e.clientY,X=e.clientX,
				Ymove=elmcrdtop+Y-Ystartmove,
				Xmove=elmcrdleft+X-Xstartmove,
				Ymoveable=Y-Yable+scroll.top(),
				Xmoveable=X-Xable+scroll.left();
				if(Ymoveable>=topable){
					elm.style.top=Ymove+'px';
				}else{
					elm.style.top=0;
				}
				if(Xmoveable>=leftable){
					elm.style.left=Xmove+'px';
				}else{
					elm.style.left=0;
				}
			}
		}
		stopprocessing(elm);
		return false;
	}
}

function dragdropbymargin(elm,rectableelm){
	var able=getelmcrd(rectableelm),
	topable = able.top,
	leftable=able.left;
	document.onmousedown=function(event){
		var e=event||window.event;
		if((e.which && e.which==1)||(e.button && e.button==1)){
		var elmcrd=getelmcrd(elm),
		elmcrdtop=elmcrd.top,
		elmcrdleft=elmcrd.left,
		Ystartmove=e.clientY,
		Xstartmove=e.clientX,
		Ymargin=elmcrdtop-topable,
		Xmargin=elmcrdleft-leftable,
		Yable=Ystartmove-elmcrdtop,
		Xable=Xstartmove-elmcrdleft;
		document.onmousemove=function(event){
			noselection();
			var e=event||window.event,
			Y=e.clientY,X=e.clientX,
			Ymove=Ymargin + Y-Ystartmove+rectableelm.scrollTop,
			Xmove=Xmargin + X-Xstartmove+rectableelm.scrollLeft,
			Ymoveable=Y-Yable+scroll.top(),Xmoveable=X-Xable+scroll.left();
			if(Ymoveable>=topable+scroll.top()-rectableelm.scrollTop){
				elm.style.marginTop=Ymove+'px';
			}else{
				elm.style.marginTop=0;
			}
			if(Xmoveable>=leftable+scroll.left()-rectableelm.scrollLeft){
				elm.style.marginLeft=Xmove+'px';
			}else{
				elm.style.marginLeft=0;
			}
		}
	}
		stopprocessing(elm);
		return false;
}

}

function dragdropfree(elm,rectelm){
var able=getelmcrd(rectelm);
var topable = able.top,
leftable=able.left,
h=elm.offsetHeight,
w=elm.offsetWidth;
document.onmousedown=function(event){
	var e=event||window.event;
	if((e.which && e.which==1)||(e.button && e.button==1)){
		var elmcrd=getelmcrd(elm),elmcrdtop=elmcrd.top,
		elmcrdleft=elmcrd.left,Ystartmove=e.clientY,
		Xstartmove=e.clientX,Yable=Ystartmove-elmcrdtop,
		Xable=Xstartmove-elmcrdleft;
		document.onmousemove=function(event){
			noselection();
			e=event||window.event,
			Y=e.clientY,X=e.clientX,
			Ymove=elmcrdtop+Y-Ystartmove+scroll.top(),
			Xmove=elmcrdleft+X-Xstartmove+scroll.left(),
			Ymoveable=Y-Yable+scroll.top(),
			Xmoveable=X-Xable+scroll.left();
			if(Ymoveable>=topable  && Xmoveable>=leftable){
				elm.style.top=Ymove+'px';
				elm.style.left=Xmove+'px';
			}
	}
}
stopprocessing(elm);
return false;
}
}

function sizecursordirect(y,x,elm){
if(x===0){if(y>0){elm.style.cursor="n-resize";}
else if(y<0){elm.style.cursor="s-resize";}}
else if(y===0){if(x>0){elm.style.cursor="e-resize";}
else if(x<0){elm.style.cursor="w-resize";}}
else if(y>0){if(x>0){elm.style.cursor="nw-resize";}
else if(x<0){elm.style.cursor="ne-resize";}}
else if(y<0){if(x>0){elm.style.cursor="sw-resize";}
else if(x<0){elm.style.cursor="se-resize";}}}

function resize(elm,minh,minw){
document.body.onselectstart=function() { return false; }
document.onmousedown=function(event){
	noselection();
	var e=event||window.event;
	if((e.which && e.which==1)||(e.button && e.button==1)){
		var elmheight=elm.offsetHeight,
		elmwidth=elm.offsetWidth,
		Ystart=e.clientY,
		Xstart=e.clientX,
		Yable=Ystart-getelmcrd(elm).top,
		Xable=Xstart-getelmcrd(elm).left;
		document.onmousemove=function(event){var e=event||window.event;
			var Y=e.clientY-Ystart,
			X=e.clientX-Xstart,
			newheight=elmheight+Y,
			newwidth=elmwidth+X;
			if(newheight>=minh && newwidth>=minw){
				sizecursordirect(Y,X,elm);
				elm.style.height=newheight+'px';
				elm.style.width=newwidth+'px';
			}
		}
		document.onmouseup=function(){
			document.onmousemove=null;elm.style.cursor="default";
			};
		}
		return false;
	}
}

return{
	scroll:scroll,
	getelmcrd:getelmcrd,
	notricks:notricks,
	noselection:noselection,
	dragdrop:dragdrop,
	dragdropbymargin:dragdropbymargin,
	dragdropfree:dragdropfree,
	resize:resize
}

})();

