function showvideo(v, title, utitle)
{
	var uid = localStorage.getItem('userId');
	window.location.assign("showvideo.php?v="+v+"&title="+title+"&uid="+uid+"&utitle="+utitle);	
}
