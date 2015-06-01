<?php
if (isset($_GET['lyricId']))
{
	header('Content-Type: text/html; charset=utf-8');
	$result = file_get_contents($_GET['lyricId']);
	$str = explode("<p class=\"col-sm-12\" style=\"min-height: 250px;\">", $result);
	$str = explode("p>", $str[1]);
	$str[0] = str_replace("~查詢更多歌詞 http://www.oiktv.com","", $str[0]);
	$str[0] = str_replace("<a href=\"http://www.oiktv.com\">歌詞帝國</a>~","", $str[0]);
	echo "<br /><div id='lyricArea'>$str[0]</div><br />";
}
?>