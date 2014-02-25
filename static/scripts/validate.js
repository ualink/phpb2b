<!--//
$(document).ready(function(){
	if($("#kw").length>0){
		$("#kw").val(input_keywords).css("color","#999").bind({
			blur:function()
			{
				if($(this).val()=="")
				{
					$(this).val(input_keywords);
					$(this).css("color","#999");
				}
			},
			focus:function()
			{
				$(this).css("color","#333");
				if($(this).val()==input_keywords)
				{
					$(this).val("")
				}
			}
		});
	}
    $("#LoggingFrm").validate({
		rules: {
			"data[login_name]": { required: true},
			"data[login_pass]": { required: true}
		},
		messages: {
			"data[login_name]": pb_lang.INPUT_CORRECT_UNAME,
			"data[login_pass]": pb_lang.INPUT_PASSWD
		},
		submitHandler: function(form) {        
			$(form).find(":submit").attr("disabled", true);        
			form.submit();     
		}
	});
    $("#regfrm").validate({
		rules: {
			"data[member][username]": { required: true,rangelength:[5,20]},
			"data[member][userpass]": { required: true,rangelength:[6,20]},
			"data[member][email]": { required: true, email:true},
			"re_memberpass": { required: true, equalTo: "#memberpass"}
		},
		messages: {
			"data[member][username]": {
				required:pb_lang.INPUT_CORRECT_UNAME,
				rangelength:pb_lang.INPUT_BT_UNAME
			},
			"data[member][userpass]": {
				required:pb_lang.INPUT_PASSWD,
				rangelength:pb_lang.INPUT_BT_PASSWD
			},
			"data[member][email]": pb_lang.INPUT_CORRECT_EMAIL,
			"re_memberpass": pb_lang.RE_INPUT_PASSWD
		},
		submitHandler: function(form) {        
			$(form).find(":submit").attr("disabled", true);        
			form.submit();     
		}
	}); 

   $('#dataMemberUsername').blur(function (){
	 var username = $("#dataMemberUsername").val();
	 var params = "username="+username;
	 var action = "checkusername";
	 if(username){
		 $.ajax({
		   url:'ajax.php?action='+action,
		   type:'get',
		   dataType:'json',
		   data:params,	
		   error:function(XMLResponse){alert(pb_lang.CHECK_FAIL+":"+XMLResponse.responseText)},
		   success: update_checkusernameDiv
		 });
	}
   });	
   
   $('#dataUsername').blur(function (){
	 var username = $("#dataMemberUsername").val();
	 var params = "username="+username;
	 var action = "checkusername";
     $.ajax({
       url:'ajax.php?action='+action,
       type:'get',
       dataType:'json',
       data:params,
	   error:function(XMLResponse){alert(pb_lang.CHECK_FAIL+":"+XMLResponse.responseText)},
       success:getpass_checkusernameDiv
     });
   });	

   $('#memberpass').blur(function (){
	 var userpass = $("#memberpass").val();
	  if(userpass.length<6){
		 return;
	    }
	 });

   $('#exchange_imgcapt').click(function (){
	 $('#imgcaptcha').attr('src','captcha.php?sid=' + Math.random());
	 $('#login_auth').focus();
	 return false;
   });	

   $('#dataMemberEmail').blur(function (){
	 var email = $("#dataMemberEmail").val();
	 if(email.length<5){
		 return;
	 }
	 var params = "email="+email;
	 var action = "checkemail";
     $.ajax({
       url:'ajax.php?action='+action,
       type:'get',
       dataType:'json',
       data:params,
	   error:function(XMLResponse){alert(pb_lang.CHECK_FAIL+":"+XMLResponse.responseText)},
       success:update_checkemailDiv
     });
   });	
});
function update_checkusernameDiv(data){
	var errorNumber = data.isError;
	if(errorNumber!="0")
	{
		$("#Submit").attr('disabled', true);
		$("#membernameDiv").html('<img src="static/images/check_error.gif">'+pb_lang.UNAME_EXIST);
	}else{
		$("#Submit").attr('disabled', false);
		$("#membernameDiv").html('<img src="static/images/check_right.gif">');
	}
}
function update_checkemailDiv(data){
	var errorNumber = data.isError;
	if(errorNumber!="0")
	{
		$("#Submit").attr('disabled', true);
		if(errorNumber=="1"){
			$("#memberemailDiv").html('<img src="static/images/check_error.gif">'+pb_lang.EMAIL_EXIST);
		}else{
			$("#memberemailDiv").html('<img src="static/images/check_error.gif">'+pb_lang.INPUT_CORRECT_EMAIL);
		}
	}else{
		$("#Submit").attr('disabled', false);
		$("#memberemailDiv").html('<img src="static/images/check_right.gif">');
	}
}
function getpass_checkusernameDiv(data){
	var errorNumber = data.isError;
	if(errorNumber!="0")
	{
		$("#GoNext").attr('disabled', true);
		$("#checkusernameDiv").html(pb_lang.CHECK_FAIL);
	}else{
		$("#GoNext").attr('disabled', false);
		$("#checkusernameDiv").html('');
	}
}
//-->