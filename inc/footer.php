
</div></div></div>
<div style="width:100%; display:block; overflow:hidden;background:#4CAF50;">
<div style="background:#4CAF50; padding:20px 0 30px !important; font-size:14px; text-align:center; color:#FFFFFF; width:100%">Powered By <a href="#" style="color:#FFF; text-decoration:none; font-weight:bolder;">Unical Database</a></div>
</div>
<script type="text/javascript">
var addEvent = function(elem, type, eventHandle) {
    if (elem == null || elem == undefined) return;
    if ( elem.addEventListener ) {
        elem.addEventListener( type, eventHandle, false );
    } else if ( elem.attachEvent ) {
        elem.attachEvent( "on" + type, eventHandle );
    }
};

function xBody() {
    if (document.body) {
        return document.body;
    }
    if (document.getElementsByTagName) {
        var bodies = document.getElementsByTagName("BODY");
        if (bodies != null && bodies.length > 0) {
            return bodies[0];
        }
    }
    return null;
}
function ietruebody(){
return (document.compatMode && document.compatMode!="BackCompat")? document.documentElement : document.body
}
function autoheight(){
	var a = document.all ? true : false;
	wH = a && !window.opera ? ietruebody().clientHeight : window.innerHeight;
	var appcontainer = document.getElementById('appcontainer');
	appcontainer.style.height = wH - (70+28+43) + 'px';
}
autoheight();
addEvent(window, "resize", function() { autoheight(); } );
</script>
<script src="js/jquery1.js" type="text/javascript"></script>
<script type="text/javascript" src="js/script.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

<script src="js/jquery1.js" type="text/javascript"></script>
</body>
</html>
