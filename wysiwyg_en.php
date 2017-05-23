
<div id="wholeworkplace">
<div id="mainworkpanel" class="interface">
<div id="insertpanel">
<div  id="textinsert" class="insert" title="Insert text"></div>
<div  id="quoteinsert" class="insert"  title="Insert a quote" ></div>
<div  id="imageinsert" class="insert"  title="Insert a picture" onclick="NP_wysiwyg.panelshow(0)"></div>
<div  id="tableinsert" class="insert"  title="Insert a table"  onclick="NP_wysiwyg.panelshow(1)"></div>
<div  id="movieinsert" class="insert"  title="Insert a video"  onclick="NP_wysiwyg.panelshow(2)"></div>
<div  id="linkinsert" class="insert"  title="Insert a link"  onclick="NP_wysiwyg.panelshow(3)"></div>
</div>
<div id="toolspanel" title="Панель управления элементами">
<div  class="tools2" id="move" onclick="NP_wysiwyg.changetool('move')" title="Moving Ctrl+1"></div>
<div  class="tools2" id="resize" onclick="NP_wysiwyg.changetool('resize')" title="Resizing Ctrl+2"></div>
<div  class="tools1" id="text"  onclick="NP_wysiwyg.changetool('text')" title="Writing text Ctrl+3"></div>
<div  class="tools2" id="remove" onclick="NP_wysiwyg.changetool('remove')" title="Removing elements Ctrl+4"></div>
<div  class="tools2" id="natural" onclick="NP_wysiwyg.changetool('natural')" title="Common Ctrl+5"></div>
</div>
<div id="wysiwygpanel">
<a id="boldmini" title="Жирный" class="interface button_interface" onclick="NP_wysiwyg.bisu(0);" href="javascript:;" ><img src="http://paper-blog.ru/img/BOLD.png" ></a>
<a id="cursivemini" title="Курсив" class="interface button_interface" href="javascript:;" onclick="NP_wysiwyg.bisu(1)"><img src="http://paper-blog.ru/img/ITALIC.png" ></a>
<a id="linethrough" title="Перечёркнутый" class="interface button_interface" href="javascript:;" onclick="NP_wysiwyg.bisu(2)"><img src="http://paper-blog.ru/img/ST.png"></a>
<a id="lineunder" title="Подчёркнутый" class="interface button_interface" href="javascript:;" onclick="NP_wysiwyg.bisu(3)"><img src="http://paper-blog.ru/img/U.png"></a>
</div>
<div style="float:left;border-right:1px solid #b5b5b5;height:20px;width:222px;">
<div id="colorscreen" title="Font color"></div>
<button type="button" id="textfamscreen" class="interface button_interface" title="Font style"><span>Times New Roman</span></button>
<div id="textsizescreen" title="Font size" style="cursor:pointer">
<div id="size"><input id="tsizeinit" type="text" maxlength="2" value="17"><div id="fixedsize">17</div> px</div>
</div>
</div>
<button type="button" id="save_pab_button" class="interface button_interface" style="border:1px solid #b5b5b5;"><img src='http://paper-blog.ru/img/ic_save_black_24dp_1x.png' style="margin-top:-2px;margin-left:2px;"></button>
<button class="interface button_interface" style="font-size:12px;float:right;margin-top:-2px;margin-right:5px;height:24px;width:85px;text-align:center;border:1px solid #b5b5b5;" id='preview'><span style="font-weight:bold;">Preview</span></button>
</div>

<div id="workspace">
<div id="content">
</div>
</div>
</div>
