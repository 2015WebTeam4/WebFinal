<!DOCTYPE html>
<html>
  <head>
  	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
	<script>
		
		
	</script>
  </head>
  <body>
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
	  
<?php
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
      

?>

      // 4. The API will call this function when the video player is ready.
      function onPlayerReady(event) {
        event.target.playVideo();
      }

      // 5. The API calls this function when the player's state changes.
      //    The function indicates that when playing a video (state=1),
      //    the player should play for six seconds and then stop.
      function onPlayerStateChange(event) {
        if (event.data == YT.PlayerState.ENDED) {
  //      location.reload(true);
		  $("#showCount").load("count.php");
		  // should be change to send request only
		  player.playVideo();
        }
      }
      function stopVideo() {
        player.stopVideo();
      }
	  
	
	
	function GetLyric(id)
	{
		$.get("http://localhost/getSongLyric.php", 
		      {lyricId:id}, 
			  function(data)
			  {
					$('#lyricArea').html(data);
			  });
		
	}
	  
    </script>
	
	<div id="showCount"></div>
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
	
	
	foreach ($matches as $link)
	{
//		echo "<a href='$link[0]'>link</a>  ";
		echo "<a href=\"javascript:GetLyric('$link[0]')\">Lyric</a>";	//use javascript to send request
		echo "<br />";
	}			
		echo "<br/><br/><br/><br/><br/>";
	?>
	
	
	
	</div>
	<?php
		echo "<br /><div id='lyricArea'>haha</div><br />";
	
	?>
	
	</body>
</html>