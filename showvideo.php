<!DOCTYPE html>
<html>
  <head>
  	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
  </head>
  <body>
    <!-- 1. The <iframe> (and video player) will replace this <div> tag. -->
    <div id="player"></div>

    <script>
      // 2. This code loads the IFrame Player API code asynchronously.
      var tag = document.createElement('script');

      tag.src = "https://www.youtube.com/iframe_api";
      var firstScriptTag = document.getElementsByTagName('script')[0];
      firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

      // 3. This function creates an <iframe> (and YouTube player)
      //    after the API code downloads.
      var player;
	  
<?php
	  
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
      echo "}";
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
    </script>
	
	<div id="showCount"></div>
	</body>
</html>