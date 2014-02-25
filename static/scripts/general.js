<!--
var cookiedomain = isUndefined(cookiedomain) ? '' : cookiedomain;
var cookiepath = isUndefined(cookiepath) ? '' : cookiepath;

function preview(){	
	var x = $("#uploadfile");	
	var y = $("#uploadpic");
	var z = $("#uploadpic_hover");
	if(!x || !x.val() || !y) return;	
	var patn = /\.jpg$|\.jpeg$|\.gif$|\.png$/i;	
	if(patn.test(x.val())){		
		y.attr("src", "file://localhost/"+x.val());	
		z.attr("href", "file://localhost/"+x.val());	
	}else{		
		alert("What you select is not a picture?");	
	}
}

function showPreview(mypic,imgfile) {
	if (imgfile.value){
	mypic.src=imgfile.value;
	mypic.style.display="";
	mypic.border=1;
	}
}

function checkLength(which) {  
	var maxChars = 250;  
	if (which.value.length > maxChars)  
	which.value = which.value.substring(0,maxChars);  
	var curr = maxChars - which.value.length;  
	document.getElementById("chLeft").innerHTML = curr.toString();  
}

function BookmarkIt(pageUrl, pageTitle)
{
	window.external.addFavorite(pageUrl, pageTitle);
}

function   myAddPanel(title,url,desc)  
{  
	if   ((typeof   window.sidebar   ==   'object')   &&   (typeof   window.sidebar.addPanel   ==   'function'))
	{  
		window.sidebar.addPanel(title,url,desc);  
	}  
	else//IE  
	{  
		window.external.AddFavorite(url,title);  
	}  
}

function redirect(url){         
	window.location.href = url;
}

function jump( url, secs)
{
	var secs = parseInt( secs ) > 0 ? parseInt( secs ) * 1000 : 0;
	if( !-[1,] )
	{
		var a = document.createElement( 'a' );
		a.href = url;
		document.body.appendChild( a );
		setTimeout(function(){a.click()}, secs);
	}
	else
	{
		setTimeout("location.href = '" + url + "'", secs);
	}
}

function setcookie(cookieName, cookieValue, seconds, path, domain, secure) {
	var expires = new Date();
	if(cookieValue == '' || seconds < 0) {
		cookieValue = '';
		seconds = -2592000;
	}
	expires.setTime(expires.getTime() + seconds * 1000);
	domain = !domain ? cookiedomain : domain;
	path = !path ? cookiepath : path;
	document.cookie = escape(cookiepre + cookieName) + '=' + escape(cookieValue)
		+ (expires ? '; expires=' + expires.toGMTString() : '')
		+ (path ? '; path=' + path : '/')
		+ (domain ? '; domain=' + domain : '')
		+ (secure ? '; secure' : '');
}

function getcookie(name, nounescape) {
	name = cookiepre + name;
	var cookie_start = document.cookie.indexOf(name);
	var cookie_end = document.cookie.indexOf(";", cookie_start);
	if(cookie_start == -1) {
		return '';
	} else {
		var v = document.cookie.substring(cookie_start + name.length + 1, (cookie_end > cookie_start ? cookie_end : document.cookie.length));
		return !nounescape ? unescape(v) : v;
	}
}

function isUndefined(variable) {
	return typeof variable == 'undefined' ? true : false;
}
//-->