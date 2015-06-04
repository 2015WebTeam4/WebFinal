<!DOCTYPE html>
<html>
  <head>
  	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
	<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
	<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">

	<script>
		
		
	</script>
  </head>
  <body>
	 <a href="ut3.php">Back</a><br />
    <!-- 1. The <iframe> (and video player) will replace this <div> tag. -->
    <div id="player"></div>

    <script type="text/javascript">
      // 2. This code loads the IFrame Player API code asynchronously.
      var tag = document.createElement('script');

      tag.src = "https://www.youtube.com/iframe_api";
      var firstScriptTag = document.getElementsByTagName('script')[0];
      firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

      // 3. This function creates an <iframe> (and YouTube player)
      //    after the API code downloads.
      var player;
	  var startTime;
	  var endTime;
<?php
	  include("licence.php");
	  header('Content-Type: text/html; charset=utf-8');
	  $vid = $_GET['v'];
      echo "function onYouTubeIframeAPIReady() {";
	  echo "player = new YT.Player('player', {";
	  echo "height: '390',";
	  echo "width: '640',";
	  echo "videoId: '$vid',";
	  echo "events: {";
	  echo "'onReady': onPlayerReady,";
	  echo "'onStateChange': onPlayerStateChange";
	  echo "}";
      echo "  });";
      echo "}\n";
      
		// check database
		$uid = $_GET['uid'];
		$sql = "SELECT * FROM songinfo WHERE songid='$vid' AND userid='$uid'";
        $result = mysql_query($sql);
		if (mysql_num_rows($result) == 0)
		{
			//insert into database
			$sql = "INSERT INTO songinfo VALUES ('$vid', '$uid', '0', 'N')";
			$result = mysql_query($sql);
		}
?>

      // 4. The API will call this function when the video player is ready.
      function onPlayerReady(event) {
        event.target.playVideo();
		setInterval("checkTime()", 100);
		getCount('N');
		
		$(function() {
    $("#slider").slider({
      range: true,
      min: 0,
      max: player.getDuration(),
      values: [ 0, 10 ],
      slide: function( event, ui ) {
			startTime = ui.values[ 0 ];
			endTime = ui.values[ 1 ];
			$("#startTime").html(Math.floor(startTime/60)+"."+startTime%60);
			$("#endTime").html(Math.floor(endTime/60)+"."+endTime%60);
	  }
    });
	startTime = $("#slider").slider( "values", 0 );
	endTime = $("#slider").slider( "values", 1 );
	$("#startTime").html(Math.floor(startTime/60)+"."+startTime%60);
	$("#endTime").html(Math.floor(endTime/60)+"."+endTime%60);
  });
      }
	  function checkTime(){
		//console.log(player.getCurrentTime());
		var time = player.getCurrentTime();
		if (time>endTime || time<startTime){
//			player.pauseVideo();
			
			player.seekTo(startTime);
			player.playVideo();
			if (Math.abs(time-endTime) < 1 || Math.abs(time-startTime) < 1)
				getCount('Y');
		}
		
	  }
      // 5. The API calls this function when the player's state changes.
      //    The function indicates that when playing a video (state=1),
      //    the player should play for six seconds and then stop.
      function onPlayerStateChange(event) {
		var time = player.getCurrentTime();
        if (event.data == YT.PlayerState.ENDED) {
		  getCount('Y');
		  player.playVideo();
        }
		/*
		else if (event.data == YT.PlayerState.PAUSED && (time >= endTime || time <= startTime)) {
			getCount('Y');
			player.seekTo(startTime);
			player.playVideo();
		}
		*/
      }
      function stopVideo() {
        player.stopVideo();
      }
	  
	function getCount(add)
	{
		$("#showCount").load("count.php",
		  {vid:player.getVideoData().video_id,
		  uid:localStorage.getItem('userId'),
		  add:add});
	}
	
	function GetLyric(id, title, vid)
	{
		$.get("getSongLyric.php", 
		      {lyricId:id,
			  title:title,
			  vid:vid}, 
			  function(data)
			  {
					$('#lyricArea').html(data);
			  });
		
	}
	
	document.ready = function() {
		getCount('N');
	};	  
	
	
	
    </script>
	<div id="Count">Times Played:</div>
	<div id="showCount"></div>
	StartTime:<div id="startTime"></div>
	EndTime:<div id="endTime"></div>
	<div id="slider"></div>
	
	<div id="lyrics">
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
		echo "<a href=\"javascript:GetLyric('$link[0]', '$title', '".$_GET['v']."')\">Lyric</a>";	//use javascript to send request
		echo "<br />";
	}	
		echo "<br/><br/><br/><br/><br/>";
	?>
	
	
	
	</div>
	<?php
		echo "<br /><div id='lyricArea'>Lyric is here</div><br />";
	
	?>
	<div id="playList">
		PlayList:<br />
<?php
		$sql = "SELECT * FROM songinfo WHERE userid='$uid'";
        $result = mysql_query($sql);
		echo "<ul>";
		while($row = mysql_fetch_array($result))
		{
			$sql2 = "SELECT * FROM lyrics WHERE songid='$row[songid]'";
			$result2 = mysql_query($sql2);
			if ($result2 && mysql_num_rows($result2) > 0)
			{
				$row2 = mysql_fetch_array($result2);
				$title = htmlspecialchars($row2['title']);
				echo "<li>";
				echo "<a href=\"showvideo.php?v=$row[songid]&uid=$uid&title=$title\">$row2[title]</a>";
				echo "</li>";
			}
			else
			{
				echo "<li>";
				echo "<a href=\"showvideo.php?v=$row[songid]&uid=$uid\">$row[songid]</a>";
				echo "</li>";
			}
		}
		echo "</ul>";
?>
	</div>
	</body>
</html>
