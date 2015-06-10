<!DOCTYPE html>
<html>
  <head>
  	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
	<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
	<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
	<link rel="stylesheet" type="text/css" href="myStyle.css">
	<script type = "text/javascript" src = "playbutton.js"></script>
  </head>

  <body class="videopage">
	 <a class="btn" href="ut3.php">Back</a><div id="buttons">
	</div><br />
    <!-- 1. The <iframe> (and video player) will replace this <div> tag. -->
   
   
	<div class="playlistbox">
		<button id="SwitchPlaylist" onclick="javascript:switchplaylist()">Switch PlayList</button>
		
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
	        $result = mysqli_query($link, $sql);
			if (mysqli_num_rows($result) == 0)
			{
				/* insert into database */
				$sql = "INSERT INTO songinfo VALUES ('$vid', '$uid', '0', 'N', '$utitle')";
				$result = mysqli_query($link, $sql);
			}
		?>
		<script src="showvideoj.js"></script>
		<div id="playList">
			Recently played:<br />
		<?php
			$sql = "SELECT * FROM songinfo WHERE userid='$uid'";
	        $result = mysqli_query($link, $sql);
			echo "<ul>";
			while($row = mysqli_fetch_array($result))
			{
				$sql2 = "SELECT * FROM lyrics WHERE songid='$row[songid]'";
				$result2 = mysqli_query($link, $sql2);
				if (mysqli_num_rows($result2) > 0)
				{
					$row2 = mysqli_fetch_array($result2);
					$title = htmlspecialchars($row2['title']);
				}
				else
				{
					$title = $_GET['title'];
				}
				echo "<li>";
				echo "<a href=\"showvideo.php?v=$row[songid]&uid=$uid&title=$title\">$row[Utitle]</a>";
				echo "</li>";
			}
			echo "</ul>";
		?>
		</div>
		<div id="playList2" style="display:none;">
		<br />Favorite List:<br />
		<?php
			$sql = "SELECT * FROM songinfo WHERE userid='$uid' AND addtof='Y'";
			$result = mysqli_query($link, $sql);
			echo "<ul>";
			while($row = mysqli_fetch_array($result))
			{
				$sql2 = "SELECT * FROM lyrics WHERE songid='$row[songid]'";
				$result2 = mysqli_query($link, $sql2);
				if (mysqli_num_rows($result2) > 0)
				{
					$row2 = mysqli_fetch_array($result2);
					$title = htmlspecialchars($row2['title']);
				}
				else
				{
					$title = $_GET['title'];
				}
				echo "<li>";
				echo "<a href=\"showvideo.php?v=$row[songid]&uid=$uid&title=$title\">$row[Utitle]</a>";
				echo "</li>";
			}
			echo "</ul>";
		?>
		</div>
	</div>

    <div class="playvideobox">
	    	<div id="player"></div>
	    
		<div>
			<img id = "pandp" class = "mybutton" src = "http://assam1231.esy.es/pause.png" onclick = "switchpp()"/>
			<img id = "sandm" class = "mybutton" src = "http://assam1231.esy.es/mute.png" onclick = "switchsm()"/>
		</div>
		<?php
			echo "<button id = \"favorite\" style = \"float: left; margin-right: 5px; display: block;\" onclick = \"javascript:addtof('$uid', '".$_GET['v']."')\">";
			echo "		Add to/Remove from Favorite";
			echo "</button>";
		?>
		<br/>
		<div id="Count">Times Played:</div>
		<div id="showCount"></div>
		<br>
		<div id="playSection">
			StartTime:<div id="startTime"></div> 
			EndTime:<div id="endTime"></div>
			<div class="sli" id="slider"></div>
		</div>
	</div>


	<div class="lyricbox">
		<div id="lyrics">	
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
				$links = $matches[$i];
				echo "<a href=\"javascript:GetLyric('$links[0]', '$title', '".$_GET['v']."', 'T')\">Other lyric-$i</a>";	//use javascript to send request
				echo "<br />";
			}	
				/* show the first lyric */
		 	echo "<script>GetLyric('".$matches[0][0]." ',' ".$title." ','".$_GET['v']."', 'F');</script>";
		?>
		</div>
		<a href="mailto:assam1231@gmail.com?Subject=Lyric%20Error" target="_blank">Report lyrics</a>
	</div>



	

	</body>
</html>
