<?php
	require_once 'inc/header.php';
    require_once 'config.php';
?>


<!-- content starts here -->
<style>
body{ font-family:"Segoe UI", Tahoma; font-size:12px;}
select{ width:120px; border:1px solid #999; padding:3px; margin-top:2px;}
input[type="text"]{ width:150px; border:1px solid #999; padding:4px; margin-top:2px;}
.td{ padding:10px 3px 10px 20px;}
.field tr{ margin-bottom:10px; display:block; overflow:hidden }
.field td{ padding-right:20px}
.block{ display:block; overflow:hidden;}
.bb{ font-family:"Segoe UI", "Myriad Pro", Tahoma; font-weight:700; font-size:11px}
.dd{ border:1px solid #999; width:385px; margin:10px 10px 0; height:500px !important; overflow-y:auto;}
</style>


<form action="form_regWelcome.php" method="post" autocomplete="off" onsubmit="return chk();" name="register">


<?php
	if( isset($_GET['i']) ) {
		switch( $_GET['i']) {
			case 1:
				echo '<div class="info">Courses Successfully Registered</div>';
				
			break;
			default:
			break;
		}
	}
	//if isset(
	
?>
<div style=" width:890px;margin:0 auto; background:#CCC" class="block">
<table border="0" cellpadding="0" cellspacing="0" >
  <tr>  
    <td width="" class="td"><p>Move to Level</p>
      <select name="level" id="mtlevel">
      <option value="">Select</option>
		<?php
        $ac = mysqli_query( $GLOBALS['connect'], 'SELECT `level_id`, `level_name`, `programme_id` FROM `level` WHERE programme_id = '.$_SESSION['myprogramme_id'].' ORDER BY level_name');
        while( $l=mysqli_fetch_assoc($ac) ) {
			echo '<option value=',$l['level_id'],'>';
			if( $l['level_id'] > 10 && $l['level_id'] < 13 ) {
				echo $l['level_name'].' - DIPLOMA';
			} elseif( $l['level_id'] > 12 ) {
				//echo 'Contact '.substr($l['level_name'],0,1);
				echo $l['level_name'];
			} else
				echo $l['level_name'];

			echo '</option>';
        }
        mysqli_free_result($ac);
        ?>
      </select></td>

    <td width="" class="td"><p>Move to Year / Session</p><label for="select2"></label>
      <select name="ysess" id="mtsess">
		<?php 

            for ($year = (date('Y')-1); $year >= 1998; $year--) {

				switch( $_SESSION['myprogramme_id'] ) {
					case 7: case '7':
						echo "<option value='$year'>$year</option>\n";
					break;
					default:
						$yearnext =$year+1;
						echo "<option value='$year'>$year/$yearnext</option>\n";
					break;
				}

            } 
        ?> 
      </select></td>


      
      
      
    <td width="" class="td" style="padding-right:20px"><p>Season</p><label for="select2"></label>
      <select name="season" >
      <option value="NORMAL">NORMAL</option>
      <option value="VACATION">VACATION</option>
      </select></td>
            
  </tr>
</table>
</div>



<table border="0" cellpadding="0" cellspacing="0" style="background:#F6f6f6;margin:0 auto; font-size:11px;">
  <tr>
  
    <td width="" class="td"><p>Entry Year / Session</p><label for="select2"></label>
      <select name="yearsession" id="yearsession">
		<?php 

            for ($year = (date('Y')-1); $year >= 1998; $year--) {

				switch( $_SESSION['myprogramme_id'] ) {
					case 7: case '7':
						echo "<option value='$year'>$year</option>\n";
					break;
					default:
						$yearnext =$year+1;
						echo "<option value='$year'>$year/$yearnext</option>\n";
					break;
				}

            }			 
        ?> 
      </select></td>
      
        
      <td width="" class="td"><p>Entry Student Level</p>
      <select id="sl">
      <option value="">Select</option>
		<?php
        $ac = mysqli_query( $GLOBALS['connect'], 'SELECT `level_id`, `level_name`, `programme_id` FROM `level` WHERE programme_id = '.$_SESSION['myprogramme_id'].' ORDER BY level_name');
        while( $l=mysqli_fetch_assoc($ac) ) {
			echo '<option value=',$l['level_id'],'>';
			if( $l['level_id'] > 10 && $l['level_id'] < 13 ) {
				echo $l['level_name'].' - DIPLOMA';
			} elseif( $l['level_id'] > 12 ) {
				//echo 'Contact '.substr($l['level_name'],0,1);
				echo $l['level_name'];
			} else
				echo $l['level_name'];

			echo '</option>';
        }
        mysqli_free_result($ac);
        ?>
      </select></td>

    <td width="" class="td" style="padding-right:20px"><p>Student Department</p><label for="select2"></label>
		<?php
			$where = '';
			$disable = '';
			 if( $_SESSION['mymstatus']=='3' ) {
				 $where = ' WHERE departments_id = '.$_SESSION['mydepartment_id'];
				 $disable = 'disabled="disabled"';
			 }
        ?>      
      <select id="sd">
			<?php
				if( $_SESSION['mymstatus']!='3' ) {
					echo '<option value="">Select</option>';
				}
				$l_dept = mysqli_query( $GLOBALS['connect'], 'SELECT `departments_id`, `departments_name`, `fac_id`, `departments_code` FROM `departments` '.$where.' order by departments_name asc');
				
				while( $r=mysqli_fetch_assoc($l_dept) ) {
					echo '<option value="',$r['departments_id'],'">',$r['departments_name'],'</option>';
				}
				mysqli_free_result($l_dept);
            ?>
      </select></td>


      
    <td width="" class="td"><p>Course Semester</p>
      <select name="semester" id="semester">
      <option value="both">First/Second Semester</option>
      </select></td>
  
    <td width="" class="td"><p>Course Level</p>
      <select id="cl">
      <option value="">Select</option>
		<?php
        $ac = mysqli_query( $GLOBALS['connect'], 'SELECT `level_id`, `level_name`, `programme_id` FROM `level` WHERE programme_id = '.$_SESSION['myprogramme_id'].' ORDER BY level_name');
        while( $l=mysqli_fetch_assoc($ac) ) {
			echo '<option value=',$l['level_id'],'>';
			if( $l['level_id'] > 10 && $l['level_id'] < 13 ) {
				echo $l['level_name'].' - DIPLOMA';
			} elseif( $l['level_id'] > 12 ) {
				//echo 'Contact '.substr($l['level_name'],0,1);
				echo $l['level_name'];
			} else
				echo $l['level_name'];

			echo '</option>';
        }
        mysqli_free_result($ac);
        ?>
      </select></td>

    <td width="" class="td" style="padding-right:20px"><p>Course Department</p><label for="select2"></label>
 		<?php
			$where = '';
			$disable = '';
			 if( $_SESSION['mymstatus']=='3' ) {
				 $where = ' WHERE departments_id = '.$_SESSION['mydepartment_id'];
				 $disable = 'disabled="disabled"';
			 }
        ?>   
      <select id="cd">
			<?php
				if( $_SESSION['mymstatus']!='3' ) {
					echo '<option value="">Select</option>';
				}
				$l_dept = mysqli_query( $GLOBALS['connect'], 'SELECT `departments_id`, `departments_name`, `fac_id`, `departments_code` FROM `departments` '.$where.' order by departments_name asc');
				
				while( $r=mysqli_fetch_assoc($l_dept) ) {
					echo '<option value="',$r['departments_id'],'">',$r['departments_name'],'</option>';
				}
				mysqli_free_result($l_dept);
            ?>
      </select></td>

  </tr>
</table>
<div class="block" style="margin:0 auto; width:890px;">
<div id="s" style=" width:443px;margin:0 auto; /*margin-right:5px;*/ padding-bottom:10px;text-align:center; float:left; background:#F6F6F6" class="block">
<input style="margin:10px 10px 0" class="bb" type="button" value="Student Lists" onclick="return lss(this, 's')">
<label style="position:relative;top:3px;"><input name="prob" id="prob" type="checkbox" value="true" onclick="inform()" checked /><span id="chkinfo" style="background:#FEECDF;padding:0px 5px; position:relative; top:-2px; color:#808080">Hide Probation/Withdraw Student</span></label>
</div>
<div id="c" style=" width:444px; float:left; background:#F6F6F6;text-align:center; padding-bottom:10px;" class="block">
      <select name="cyear" id="cyear">
		<?php 

            for ($year = (date('Y')-1); $year >= 1998; $year--) {

				switch( $_SESSION['myprogramme_id'] ) {
					case 7: case '7':
						echo "<option value='$year'>$year</option>\n";
					break;
					default:
						$yearnext =$year+1;
						echo "<option value='$year'>$year/$yearnext</option>\n";
					break;
				}

            }			 
        ?> 
      </select>
      
      <input style="margin:10px 10px 0" class="bb" type="button" value="Courses Lists" onclick="return lss(this, 'c')">
</div>
</div>

<div id="s" style=" width:890px;margin:0 auto; text-align:center;" class="block">
<input type="submit" value="Register Course" name="submit" style="margin:10px; font-family:'Segoe UI', 'Myriad Pro', Tahoma" />
</div>
<?php 
 
?> 
</form>
  <!-- content ends here -->
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
function xid(id) {
	return document.getElementById(id);
}

function inform() {
	if( xid('prob').checked === true ){
		xid('chkinfo').innerHTML = 'Hide Probation/Withdraw Student';
		xid('chkinfo').style.background = '#FEECDF';
		xid('chkinfo').style.color = '#808080';
	}else{
		xid('chkinfo').innerHTML = 'Show Probation/Withdraw Student';
		xid('chkinfo').style.background = 'Green';
		xid('chkinfo').style.color = 'white';
	}
}

function lss( a, id ) {
	
	var v = a.value;
	
	ajax.reset();
	ajax.method = "GET";
	switch(v) {
		case 'Student Lists':
			if( xid("sl").value=='' || xid("sd").value=='' ){
				alert("Student Level And Department must be selected");
				return;
			}
			
			if( xid('prob').checked === true && xid('mtlevel').value =='' ) {
				alert('Please Select The "Move to Level and Move to Year/Session", so that probation student will be properly filtered');
				return;
			}
			
			if( xid('prob').checked === true ){
				ajax.setVar("ignoreprob", "true");
				ajax.setVar("mtlevel", xid('mtlevel').value);
				ajax.setVar("mtsess", xid('mtsess').value );
			} else {
				ajax.setVar("ignoreprob", "false");
			}
			ajax.setVar("a", 1);
			ajax.setVar("level", xid("sl").value);
			ajax.setVar("dept", xid("sd").value);
			ajax.setVar("yearsession", xid("yearsession").value );
		break;
		case 'Courses Lists':
			if( xid("cl").value=='' ){
				alert("Course Level must be selected");
				return;
			}		
			ajax.setVar("a", 2);
			ajax.setVar("level", xid("cl").value);
			ajax.setVar("dept", xid("cd").value);
			ajax.setVar("cyear", xid("cyear").value);
		break;
	}
	ajax.requestFile = "welcomeList.php";
	ajax.onLoading = function(){ a.disable = true; a.value = "Loading."; };
	ajax.onLoaded = function(){ a.value += "."; }; 
	ajax.onInteractive = function(){ a.value += "."; };
	ajax.onCompletion = function() {
		a.disable = false;
		
		if( ajax.response!=401 ) {
			xid(id).innerHTML = ajax.response;
		} else {
			alert("Loading Error. Please Try again");
		}
	};
	ajax.runAJAX();
}

function chk() {
	
	var msg='';
	if( document.forms["register"].elements["level"]=='' ) {
		msg = 'Level Cannot Be empty';
	} else if( typeof(document.forms["register"].elements["stds[]"]) == 'undefined' ) {
		msg = 'Student List Not Selected';
	} else if( typeof(document.forms["register"].elements["courses[]"]) =='undefined' ) {
		msg = 'Course(s) not Selected';
	}
	if( msg!='' ) {
		alert(msg);
		return false;
	} else {
		return true;
	}
	
}
</script>
<?php require_once 'inc/footer.php'; ?>