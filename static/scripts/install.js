$(document).ready(function(){
    $('img[tag]').css({cursor:'pointer'}).click(function(){
       var flag=$(this).attr('tag');
       var fck=$('#'+$(this).attr('fck')+'___Frame');

       var fckh=fck.height();
       (flag==1)?fck.height(fckh+120):fck.height(fckh-120) ;
    });

});
function checkform()
{
	if($('#username').val().length<2 || $('#username').val().length>20)
	{
		alert(pb_lang.SUPER_ADMIN_UNAME);
		return false;
	}
	if($('#password').val().length<3 || $('#username').val().length>20)
	{
		alert(pb_lang.SUPER_ADMIN_PASSWD);
		return false;
	}
	if($('#password').val()!=$('#pwdconfirm').val())
	{
		alert(pb_lang.NOT_EQUAL_PASSWD);
		return false;
	}
	if($('#dbname').val()=='')
	{
		alert(pb_lang.INPUT_DB_NAME);
		$('#dbname').focus();
		return false;
	}
	if($('#email').val()=='')
	{
		alert(pb_lang.INPUT_EMAIL);
		$('#email').focus();
		return false;
	}
	else
	{
		var emailPattern = /^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/;
		if (emailPattern.test($('#email').val())==false)
		{
			alert(pb_lang.INPUT_CORRECT_EMAIL);
			return false;
		}
	}
	$('#install').submit();
    return false;
}
function suggestPassword() {
    var pwchars = "abcdefhjmnpqrstuvwxyz23456789ABCDEFGHJKLMNPQRSTUVWYXZ!@#$%^&*()";
    var passwordlength = 8;    // do we want that to be dynamic?  no, keep it simple :)
    var passwd = '';
    for ( i = 0; i < passwordlength; i++ ) {
        passwd += pwchars.charAt( Math.floor( Math.random() * pwchars.length ) )
    }
    return passwd;
}