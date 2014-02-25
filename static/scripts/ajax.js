<!--//
$(document).ready(function() {    
   $('#dataMemberUsername').blur(function (){
	 var params = $('#getPasswdFrm').serialize();
	 var action = "checkusername";
     $.ajax({
       url:'ajax.php?action='+action,
       type:'get',
       dataType:'json',
       data:params,
       success:update_checkusernameDiv
     });
   });
});
//-->