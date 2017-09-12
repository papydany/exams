<?php 
	require_once 'inc/header.php';
    require_once '../config.php';
	

?>


<!-- content starts here -->
<style>
body{ font-family:"Segoe UI", Tahoma; font-size:12px;}
select{ width:120px; border:1px solid #999; padding:3px; margin-top:2px;}
input[type="text"]{ width:150px; border:1px solid #999; padding:4px; margin-top:2px;}
.td{ padding:10px 3px 10px 20px;}
.field tr{ margin-bottom:10px; display:block; overflow:hidden }
.field td{ padding-right:20px}
</style>


<table border="0" cellpadding="0" cellspacing="0" style="background:#F1F1F1">
  <tr>


    <td width="" class="td"><p>Year / Session</p><label for="select2"></label>
      <select id="ss">
		<?php 
            for ($year = (date('Y')-1); $year >= 1998; $year--) { $yearnext =$year+1;
                echo "<option value='$year'>$year/$yearnext</option>\n";
            } 
        ?> 
      </select></td>


    <td width="" class="td"><p>Faculty</p><label for="select2"></label>
      <select id="f">
      <option value="">All Faculty</option>
		<?php
        $l_dept = mysqli_query( $GLOBALS['connect'], 'SELECT `faculties_id`, `faculties_name`, `faculty_code` FROM `faculties` order by `faculties_name` asc');
        while( $r=mysqli_fetch_assoc($l_dept) ) {
            echo '<option value="',$r['faculties_id'],'">',$r['faculties_name'],'</option>';
        }
        mysqli_free_result($l_dept);
        ?>
      </select></td>
            

    <td width="" class="td"><p>Semester</p><label for="select2"></label>
      <select id="s">
      	<option value="1">First Semester</option>
      	<option value="2">Second Semester</option>
      </select></td>
      
    <td width="" class="td" style="padding-right:20px"><p>Level</p>
      <select id="l">
		<?php
        $ac = mysqli_query( $GLOBALS['connect'], 'SELECT `level_id`, `level_name`, `programme_id` FROM `level`');
        while( $l=mysqli_fetch_assoc($ac) ) {
			echo '<option value=',$l['level_id'],'>';
			if( $l['level_id'] > 10 && $l['level_id'] < 13 ) {
				echo $l['level_name'].' - DIPLOMA';
			} elseif( $l['level_id'] > 12 ) {
				echo $l['level_name'].' - SANDWICH';
			} else
				echo $l['level_name'];

			echo '</option>';
        }
        mysqli_free_result($ac);
        ?>
      </select></td>   
       


  </tr>
</table>

<table width="880" border="0" cellpadding="0" cellspacing="0" style="padding:5px 20px;" class="">
  <tr>
  <td colspan="5" ><input name="" type="button" value="View Status" onClick="return return_status();"></td>
  </tr>
</table>
<div style="background:#F1F1F1; width:569px; padding:5px 10px;" id="board">
</div>
  <!-- content ends here -->

<script type="text/javascript">
var UPLOADER={frame:function(c){var n='f'+Math.floor(Math.random()*99999);var d=document.createElement('DIV');
d.innerHTML='<iframe style="display:none" src="about:blank" id="'+n+'" name="'+n+'" onload="UPLOADER.loaded(\''+n+'\')"></iframe>';
document.body.appendChild(d);var i=document.getElementById(n);if(c&&typeof(c.onComplete)=='function'){i.onComplete=c.onComplete;}
return n;},form:function(f,name){f.setAttribute('target',name);},submit:function(f,c){UPLOADER.form(f,UPLOADER.frame(c));return(c&&typeof(c.onStart)=='function')?c.onStart():true;},loaded:function(id){var i=document.getElementById(id);var d='';if(i.contentDocument){d=i.contentDocument;}else if(i.contentWindow){d=i.contentWindow.document;}else{d=window.frames[id].document;}
if(d.location.href=="about:blank"){return;}
if(typeof(i.onComplete)=='function'){i.onComplete(d.body.innerHTML);}}};

function sack(file){this.xmlhttp=null;this.resetData=function(){this.method="POST";this.queryStringSeparator="?";this.argumentSeparator="&";this.URLString="";this.encodeURIString=true;this.execute=false;this.element=null;this.elementObj=null;this.requestFile=file;this.vars=new Object();this.responseStatus=new Array(2);};this.resetFunctions=function(){this.onLoading=function(){};this.onLoaded=function(){};this.onInteractive=function(){};this.onCompletion=function(){};this.onError=function(){};this.onFail=function(){};};this.reset=function(){this.resetFunctions();this.resetData();};this.createAJAX=function(){try{this.xmlhttp=new ActiveXObject("Msxml2.XMLHTTP");}catch(e1){try{this.xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");}catch(e2){this.xmlhttp=null;}}
if(!this.xmlhttp){if(typeof XMLHttpRequest!="undefined"){this.xmlhttp=new XMLHttpRequest();}else{this.failed=true;}}};this.setVar=function(name,value){this.vars[name]=Array(value,false);};this.encVar=function(name,value,returnvars){if(true==returnvars){return Array(encodeURIComponent(name),encodeURIComponent(value));}else{this.vars[encodeURIComponent(name)]=Array(encodeURIComponent(value),true);}}
this.processURLString=function(string,encode){encoded=encodeURIComponent(this.argumentSeparator);regexp=new RegExp(this.argumentSeparator+"|"+encoded);varArray=string.split(regexp);for(i=0;i<varArray.length;i++){urlVars=varArray[i].split("=");if(true==encode){this.encVar(urlVars[0],urlVars[1]);}else{this.setVar(urlVars[0],urlVars[1]);}}}
this.createURLString=function(urlstring){if(this.encodeURIString&&this.URLString.length){this.processURLString(this.URLString,true);}
if(urlstring){if(this.URLString.length){this.URLString+=this.argumentSeparator+urlstring;}else{this.URLString=urlstring;}}
this.setVar("rndval",new Date().getTime());urlstringtemp=new Array();for(key in this.vars){if(false==this.vars[key][1]&&true==this.encodeURIString){encoded=this.encVar(key,this.vars[key][0],true);delete this.vars[key];this.vars[encoded[0]]=Array(encoded[1],true);key=encoded[0];}
urlstringtemp[urlstringtemp.length]=key+"="+this.vars[key][0];}
if(urlstring){this.URLString+=this.argumentSeparator+urlstringtemp.join(this.argumentSeparator);}else{this.URLString+=urlstringtemp.join(this.argumentSeparator);}}
this.runResponse=function(){eval(this.response);}
this.runAJAX=function(urlstring){if(this.failed){this.onFail();}else{this.createURLString(urlstring);if(this.element){this.elementObj=document.getElementById(this.element);}
if(this.xmlhttp){var self=this;if(this.method=="GET"){totalurlstring=this.requestFile+this.queryStringSeparator+this.URLString;this.xmlhttp.open(this.method,totalurlstring,true);}else{this.xmlhttp.open(this.method,this.requestFile,true);try{this.xmlhttp.setRequestHeader("Content-Type","application/x-www-form-urlencoded")}catch(e){}}
this.xmlhttp.onreadystatechange=function(){switch(self.xmlhttp.readyState){case 1:self.onLoading();break;case 2:self.onLoaded();break;case 3:self.onInteractive();break;case 4:self.response=self.xmlhttp.responseText;self.responseXML=self.xmlhttp.responseXML;self.responseStatus[0]=self.xmlhttp.status;self.responseStatus[1]=self.xmlhttp.statusText;if(self.execute){self.runResponse();}
if(self.elementObj){elemNodeName=self.elementObj.nodeName;elemNodeName.toLowerCase();if(elemNodeName=="input"||elemNodeName=="select"||elemNodeName=="option"||elemNodeName=="textarea"){self.elementObj.value=self.response;}else{self.elementObj.innerHTML=self.response;}}
if(self.responseStatus[0]=="200"){self.onCompletion();}else{self.onError();}
self.URLString="";break;}};this.xmlhttp.send(this.URLString);}}};this.reset();this.createAJAX();}
var ajax=new sack();

function xid(id) {return document.getElementById(id);}

function s(){
	return true;
}

function e(response){
	if( response == '1' ){
		return_status();
	}
}

function checkUncheckAll(theElement) {

     var theForm = theElement.form, z = 0;

	 for(z=0; z<theForm.length;z++){

      if(theForm[z].type == 'checkbox' ){

	  theForm[z].checked = theElement.checked;

	  }

     }

}

function return_status() {
	// board
	var board = xid('board');
	ajax.reset();
	ajax.requestFile = "load_publish.php";
	ajax.setVar("f", xid("f").value );
	ajax.setVar("s", xid("s").value );
	ajax.setVar("l", xid("l").value );
	ajax.setVar("ss", xid("ss").value );
	ajax.onLoading = function(){ board.innerHTML = "Loading."; };
	ajax.onLoaded = function(){ board.innerHTML += "."; }; 
	ajax.onInteractive = function(){ board.innerHTML += "."; };
	ajax.onCompletion = function() {
		if( ajax.response!=401 ) {
			board.innerHTML = ajax.response;
		} else {
			alert("Loading Error. Please Try again");
		}
	};
	ajax.runAJAX();
		
}

</script>
  
<?php require_once 'inc/footer.php'; ?>