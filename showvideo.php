<!DOCTYPE html>
<html>
  <head>
  	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
	<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
	<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">

  </head>
  <body>
	 <a href="ut3.php">Back</a><div id="buttons">
		<input type="button" onclick="SectionHideshow()" value="playSection"></button>
		<input type="button" onclick="LyricHideshow()" value="Lyric"></button>
		<input type="button" onclick="ListHideshow()" value="PlayList"></button>
	</div><br />
    <!-- 1. The <iframe> (and video player) will replace this <div> tag. -->
    <div id="player"></div>
    <?php
	  include("licence.php");
	  header('Content-Type: text/html; charset=utf-8');
	  $vid = $_GET['v'];
	  /* pass video id to javascript */
      echo "<script>var vid = '".$vid."';</script>";
      
		/* check database */
		$uid = $_GET['uid'];
		$utitle = $_GET['utitle'];
		$sql = "SELECT * FROM songinfo WHERE songid='$vid' AND userid='$uid'";
        $result = mysql_query($sql);
		if (mysql_num_rows($result) == 0)
		{
			/* insert into database */
			$sql = "INSERT INTO songinfo VALUES ('$vid', '$uid', '0', 'N', '$utitle')";
			$result = mysql_query($sql);
		}
	?>
	<script src="showvideoj.js"></script>
	<div id="Count">Times Played:</div>
	<div id="showCount"></div>
	<br>
	<div id="playSection" style="display:none;">
		StartTime:<div id="startTime"></div>
		EndTime:<div id="endTime"></div>
		<div id="slider"></div>
	</div>

	
	<div id="lyrics" style="display:none;">
	<br /><div id='lyricArea'>hi</div><br />
	<?php
		$title = $_GET['title'];
	// Search Song Here	
		//TODO: replace some title tokens such as: official 
		$find = array("official", "Official", "OFFICIAL", "官方", "完整版", "MV", "/");
		$title = str_replace($find, "", $title);

		$result = file_get_contents("http://www.oiktv.com/search/lyrics/" . $title);
		$pattern = '/http:\/\/www.oiktv.com\/lyrics\/lyric-\d*\.html/';
		preg_match_all($pattern, $result, $matches, PREG_SET_ORDER);
	 	
		for ($i = 0; $i<5 ; $i++)
		{
			$link = $matches[$i];
			echo "<a href=\"javascript:GetLyric('$link[0]', '$title', '".$_GET['v']."', 'T')\">Other lyric-$i</a>";	//use javascript to send request
			echo "<br />";
		}	
			/* show the first lyric */
	 	echo "<script>GetLyric('".$matches[0][0]." ',' ".$title." ','".$_GET['v']."', 'F');</script>";
	?>
	</div>
	
	
	
	<div id="playList" style="display:none;">
		PlayList:<br />
<?php
		$sql = "SELECT * FROM songinfo WHERE userid='$uid'";
        $result = mysql_query($sql);
		echo "<ul>";
		while($row = mysql_fetch_array($result))
		{
			$sql2 = "SELECT * FROM lyrics WHERE songid='$row[songid]'";
			$result2 = mysql_query($sql2);
			$row2 = mysql_fetch_array($result2);
			$title = htmlspecialchars($row2['title']);
			echo "<li>";
			echo "<a href=\"showvideo.php?v=$row[songid]&uid=$uid&title=$title\">$row[Utitle]</a>";
			echo "</li>";
		}
		echo "</ul>";
?>
	</div>
	</body>
</html>
