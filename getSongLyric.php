<?php
include("licence.php");
if (isset($_GET['lyricId']) && isset($_GET['update']) && isset($_GET['vid']))
{
	header('Content-Type: text/html; charset=utf-8');
	$update = $_GET['update'];
	//check database first
	$sql = "SELECT * FROM lyrics WHERE songid='".$_GET['vid']."';";
	$result = mysqli_query($link, $sql);
	
	//send request
	if (mysqli_num_rows($result) == 0 || "$update" === 'T')
	{
		$result = file_get_contents($_GET['lyricId']);
		$str = explode("<p class=\"col-sm-12\" style=\"min-height: 250px;\">", $result);
		$str = explode("</p>", $str[1]);
		$str[0] = str_replace("~查詢更多歌詞 http://www.oiktv.com","", $str[0]);
		$str[0] = str_replace("<a href=\"http://www.oiktv.com\">歌詞帝國</a>~","", $str[0]);
		$str[0] = str_replace("\n","", $str[0]);
		
		 
		echo "<br /><div id='lyricArea'>$str[0]</div><br />";
	
		//update database
	
		$sql = "SELECT * FROM lyrics WHERE songid='".$_GET['vid']."';";
		$result = mysqli_query($link, $sql);
		if (mysqli_num_rows($result) > 0)
		{
			$str[0] = str_replace("'","&#039;", $str[0]);
			$sql = "UPDATE lyrics SET content='".htmlspecialchars($str[0], ENT_QUOTES)."' WHERE songid='".$_GET['vid']."';";
			$result = mysqli_query($link, $sql);
		}
		else
		{
			$vid = $_GET['vid'];
			$title = $_GET['title'];
			$str[0] = str_replace("'","&#039;", $str[0]);
			$sql = "INSERT INTO lyrics VALUES ('$vid', '$title', '".htmlspecialchars($str[0], ENT_QUOTES)."');";
			$result = mysqli_query($link, $sql);
		}
	}
	else
	{
		$row = mysqli_fetch_array($result);
		echo "<br /><div id='lyricArea'>".htmlspecialchars_decode($row['content'])."</div><br />";
	}
}
?>
