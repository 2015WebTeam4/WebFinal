<?php
include("licence.php");
if (isset($_GET['lyricId']))
{
	header('Content-Type: text/html; charset=utf-8');
	$result = file_get_contents($_GET['lyricId']);
	$str = explode("<p class=\"col-sm-12\" style=\"min-height: 250px;\">", $result);
	$str = explode("p>", $str[1]);
	$str[0] = str_replace("~查詢更多歌詞 http://www.oiktv.com","", $str[0]);
	$str[0] = str_replace("<a href=\"http://www.oiktv.com\">歌詞帝國</a>~","", $str[0]);
	echo "<br /><div id='lyricArea'>$str[0]</div><br />";
	
	//update database
	
	$sql = "SELECT * FROM lyrics WHERE songid='".$_GET['vid']."'";
	$result = mysql_query($sql);
	if (mysql_num_rows($result) > 0)
	{
		$sql = "UPDATE lyrics SET content='OH' WHERE songid='".$_GET['vid']."'";
		$result = mysql_query($sql);
	}
	else
	{
		$vid = $_GET['vid'];
		$title = $_GET['title'];
		$sql = "INSERT INTO lyrics VALUES ('$vid', '$title', 'hahaha')";
		$result = mysql_query($sql);
	}
}
?>