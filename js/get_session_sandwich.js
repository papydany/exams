

$(document).ready(function(){
$("#yearsession").show();

$("#month").change( function() {
   
$("#yearsession").hide();

$("#result").html('...Retrieving...');
$.ajax({
type: "POST",
data: "data=" + $(this).val(),
url: "get_session_sandwich.php",
success: function(msg){
if (msg != ''){

$("#yearsession").html(msg).show();
$("#result").html('');
}
else{
$("#result").html('<em>No item result</em>');
}
}
});
});
});
