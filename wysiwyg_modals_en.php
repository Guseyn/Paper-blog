	
<div id="quoteshowmodal" class="modal showm">
<div class="triangle-up"></div>	
<div class="modalinfo">
<div id="quotecontext"><div id="quotetext" title="Write a quote" contenteditable="true"></div></div>
<div id="quoteauthor"><input type="text" id="quoteaut" placeholder="Author of the quote"></div>
<button class="button_interface" id="quoteshowbut"><span>OK</span></button>
<button class="button_interface" id="quoteshowbackbut"><span>Cancel</span></button>
</div>
</div>	


<div id="imgshow" class="modal showm">
<div class="triangle-up"></div>	
<div class="modalinfo">
<input type="text" id="imgurl" placeholder="url of picture">
<div style="margin-top:5px;"><button class="button_interface" id="imgshowbut"><span>OK</span></button>
<button class="button_interface" id="imgshowbackbut"><span>Cancel</span></button>
</div>
</div>
</div>

<div  id= "movieshow" class="modal showm">
<div class="triangle-up"></div>		
<div class="modalinfo">	
<div><input type="text" id="movieurl" placeholder="HTML code(YouTube)"></div>
<div style="margin-top:5px;">
<button class="button_interface" id="movieshowbut"><span>OK</span></button>
<button class="button_interface" id="movieshowbackbut"><span>Cancel</span></button>
</div>
</div>
</div>

<div id="linkshow" title="Before pressing the 'OK' select fragment of the text which will serve as reference" class="modal showm">
<div class="triangle-up"></div>	
<div class="modalinfo">
<div><input type="text" id="linkurl" placeholder="http(s)://link"></div>
<div style="margin-top:5px;"><button class="button_interface" id="linkshowbut"><span>OK</span></button>
<button class="button_interface" id="linkshowbackbut"><span>Cancel</span></button>
</div>
</div>
</div>

<div id="tableshow" class="modal showm">
<div class="triangle-up"></div>	
<div class="modalinfo">
<div style="margin-left:5px;float:left;">
<div id="tablework" title="The maximum number of columns and rows is 20">
<div style="width:205px;"><span style="float:left;">Number of the columns :</span><input type="text" maxlength="2" id="twval"></div>  <div><span style="float:left;">Number of the rows:</span><input type="text" maxlength="2" id="thval"></div>
</div>
<div id="tablecolorsset" title="You can adjust the color of the cells. Just click on them. The cursor must be in the common mode">
<span id="cts">Color of the cells:</span>
</div>
<div id="tabcolorscreen" title="Selected color"></div>
</div>
<div  style="float:left;margin-top:2px;margin-left:5px;">
<button class="button_interface" id="tabshowbut"><span>OK</span></button>
<button class="button_interface" id="tabshowbackbut" ><span>Cancel</span></button>
</div>
</div>
</div>

<div id="pablicatemodal" class="modal showm" style="z-index:2100;">
<div class="triangle-up" style="left:221px;"></div>	
<div id="pablicateinfo" class="infoplace">
<div id='load_block' class="load_block">
	<div style="margin-top:10px;margin-left:22px;font-size:14px;margin-bottom:8px;"><b>Please wait ... There is treatment, it may take a few seconds to a minute.</b></div>
	<div style="background: url(http://paper-blog.ru/img/lb.gif) no-repeat top -1px left -1px;height:20px;width:218px;border:1px solid #454545;border-radius:4px;margin-left:11px;"> </div>
</div>	
<input id="title" type="text" placeholder="Title of the publication"></input>
<div id="pmerrpl" class="aj_err s_t"></div>
<div id='more_info'>
Possible causes:
<div>1. Make sure you typed the tilte of the publication.</div>
<div>2. It is possible that the publication title is too long (more than 65 characters), remember that the title should reflect only the main thrust of your article.</div>
<div>3. Your article without content or the amount of content is too light.</div>
</div>
<div style="float:left;width:240px;">
<button id="make_pab" class="button_interface"><span>Publicate</span></button>
<button id="to_copy" class="button_interface"><span>Save in achievements</span></button>
</div>
</div>	
</div>
</div>



<div id="tdcolor" class="modal" unselectable="on">
<div class="triangle-left" ></div>
<div style="margin-left:15px;margin-top:3px;float:left;">R <input type="number" min="0" max="255" step="1" id="tdR" value="255"></div>
<div style="margin-left:5px;margin-top:3px;float:left;">G <input type="number" min="0" max="255" step="1" id="tdG" value="255"></div>
<div style="margin-left:5px;margin-top:3px;float:left;">B <input type="number" min="0" max="255" step="1" id="tdB" value="255"></div>
</div>

<div id="textcolor" unselectable="on" class="modal">
<div class="triangle-up" style="left:40;"></div>	
<div style="margin-left:5px;margin-top:5px;">R <input type="number" min="0" max="255" step="1" id="R" value="0"></div>
<div style="margin-left:5px;margin-top:5px;">G <input type="number" min="0" max="255" step="1" id="G" value="0"></div>
<div style="margin-left:5px;margin-top:5px;">B <input type="number" min="0" max="255" step="1" id="B" value="0"></div>
</div>

<div id="textfamdiv" unselectable="on">
<table id="textfamilies" title="Стиль шрифта" class="interface">
<tr>
<td class="init1"  id="f0" style="font-family: Times New Roman;" onclick="NP_wysiwyg.changefamilyoftext(0)" title="Times New Roman">Aa</td>
<td class="ini2" style="font-family: Tahoma" id="f1" onclick="NP_wysiwyg.changefamilyoftext(1)" title="Tahoma">Aa</td>
<td class="ini2" style="font-family: Arial" id="f2" onclick="NP_wysiwyg.changefamilyoftext(2)" title="Arial">Aa</td>
<td class="ini2" style="font-family:Courier" id="f3" onclick="NP_wysiwyg.changefamilyoftext(3)" title="Courier">Aa</td>
<td class="ini2" style="font-family: Comic Sans MS" id="f4" onclick="NP_wysiwyg.changefamilyoftext(4)" title="Comic Sans MS">Aa</td>
</tr>
<tr>
<td class="ini2" style="font-family:Sylfaen" id="f5" onclick="NP_wysiwyg.changefamilyoftext(5)" title="Sylfaen">Aa</td>
<td class="ini2" style="font-family: Verdana" id="f6" onclick="NP_wysiwyg.changefamilyoftext(6)" title="Verdana">Aa</td>
<td class="ini2" style="font-family: Georgia" id="f7" onclick="NP_wysiwyg.changefamilyoftext(7)" title="Georgia">Aa</td>
<td class="ini2" style="font-family: Lucida Sans Unicode" id="f8" onclick="NP_wysiwyg.changefamilyoftext(8)" title="Lucida Sans Unicode">Aa</td>
<td class="ini2" style="font-family: Calibri" id="f9" onclick="NP_wysiwyg.changefamilyoftext(9)" title="Calibri">Aa</td>
</tr>
</table>
</div>

<div id="elms_edit">Edit</div>
<div id="text_edit" class="modal">
<div id="teditmoveplace" class="interface moveplace"><span class="modaltitle">Text</span>
<img id="teditclose" class="modalclose" src='http://paper-blog.ru/img/del_u_a.png' title="Close">
</div>
<div id="teditwork"><div id="align" style="margin-bottom: 3px;margin-top:4px;"><span style="margin-left:6px;">Text Alignment:</span><br><select id="talign"><option>By left side</option><option>By center</option><option>By right side</option></select></div>
<div id="tsymbols" style="margin-bottom: 3px;margin-top:4px;margin-left:3px;float:left;"><span style="margin-left:3px;">Insert a symbol:</span><br><select id="tsymbmath">
<option></option><option>&#163;</option><option>&#8364;</option><option>$</option><option>&#167;</option><option>&#182;</option><option>&#169;</option><option>&#174;</option><option>&#8482;</option><option>&#176;</option><option>&#177;</option><option>&#188;</option><option>&#189;</option><option>&#190;</option>
<option>&#215;</option><option>&#247;</option><option>&#402;</option></select><select id="tsymbgreece" ><option></option><option>&#931;</option><option>&#936;</option><option>&#937;</option><option>&#945;</option><option>&#946;</option><option>&#947;</option><option>&#916;</option><option>&#948;</option><option>&#949;</option><option>&#950;</option><option>&#951;</option><option>&#952;</option>
<option>&#955;</option><option>&#956;</option><option>&#958;</option><option>&#960;</option><option>&#961;</option><option>&#963;</option><option>&#966;</option><option>&#968;</option><option>&#969;</option></select><select id="tsymbarror" style="margin-top:1px;margin-left:3px;"><option></option><option>&#8592;</option><option>&#8593;</option><option>&#8594;</option><option>&#8595;</option><option>&#8596;</option></select>
<select id="tsymbother"><option></option><option>&#9824;</option><option>&#9827;</option><option>&#9829;</option><option>&#9830;</option><option>&#34;</option><option>&#38;</option><option>&#60;</option><option>&#62;</option><option>&#8242;</option><option>&#8243;</option><option>&#8211;</option><option>&#8212;</option><option>&#171;</option><option>&#187;</option>
</select><br><input type="text" id="symbin"  style="margin-top:5px;margin-left:3px;width:188px;"></div> <button class="button_interface" id="teditok"><span>OK</span></button></div>
</div>
