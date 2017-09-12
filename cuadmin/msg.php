<?php require ("inc/header.php"); ?>

<table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="50%" style=" vertical-align:top">
    	<form method="post" action="form_msg.php">
		<div style="display:block; overflow:hidden; padding:3px 0;">
        <input name="del" type="submit" value="Delete">
        </div>
        <table width="99%" border="0" cellspacing="0" cellpadding="0" class="newtable">
        <thead>
        <tr>
        <th width="3%"><input name="" type="checkbox" checked disabled value=""></th>
        <th width="20%">Date</th>
        <th width="20%">By</th>
        <th width="57%">Subject</th>
        </tr>
        </thead>
        <tbody>
		<?php
			
			function users() {
				$_ = mysqli_query( $GLOBALS['connect'], 'SELECT examofficer_id, eo_username FROM exam_officers' );
				if( 0!=mysqli_num_rows($_) ) {
					$r = array();
					while( $d = mysqli_fetch_array($_) ) {
						$r[ $d['examofficer_id'] ] = $d['eo_username'];
					}
					mysqli_free_result($_);
					return $r;
				}
				return array();
			}

			$load = mysqli_query( $GLOBALS['connect'], 'SELECT * FROM messaging WHERE m_to = "ADMIN"');
			if( 0!=mysqli_num_rows($load) ) {
				$users = users();
				
				while( $data = mysqli_fetch_assoc($load) ) {
					$dt = explode('-', $data['m_dt']);
					$expressed_dt = date('M jS, Y',mktime(0,0,0,$dt[1], $dt[2], $dt[0]));
					echo '<tr>',
							'<td><input name="chk[]" type="checkbox" value="',$data['m_id'],'"></td>',
							'<td>',$expressed_dt,'</td>',
							'<td>',$users[ $data['m_by'] ],'</td>',
							'<td><a href="#" onclick="return show(',$data['m_id'],')">',$data['m_subj'],'</a></td>',
						'</tr>';
				}
				mysqli_free_result($load);
			}
		?>               
        </tbody>
        </table>
		</form>
    
</td>
    <td width="50%" style=" border-left:1px solid #EDF0F8; vertical-align:top"><div id="mpane">
    <p style="font-size:30px; text-align:center; color:#CCC; padding:100px 0;">No Message Selected</p>
    </div>
</td>
  </tr>
</table>


<script type="text/javascript">
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


function show(i){
	var a = xId('mpane');
	ajax.reset();
	ajax.setVar("mid", i);
	ajax.requestFile = "form_msg.php";
	ajax.onLoading = function(){ a.innerHTML = "Loading."; };
	ajax.onLoaded = function(){ a.innerHTML += "."; }; 
	ajax.onInteractive = function(){ a.innerHTML += "."; };
	ajax.onCompletion = function() {
		
		if( ajax.response!=401 ) {
			a.innerHTML = ajax.response;
		} else {
			alert("Loading Error. Please Try again");
		}
	};
	ajax.runAJAX();
		
}
</script>

<?php 
echo '</div>';
require ("inc/footer.php"); 
?>