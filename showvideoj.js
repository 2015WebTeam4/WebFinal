
/* 2. This code loads the IFrame Player API code asynchronously.*/
var tag = document.createElement('script');

tag.src = "https://www.youtube.com/iframe_api";
var firstScriptTag = document.getElementsByTagName('script')[0];
firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

/* 3. This function creates an <iframe> (and YouTube player)
after the API code downloads.*/
var player;
var startTime;
var endTime;

function onYouTubeIframeAPIReady() {
	player = new YT.Player('player', {
		height: '390',
		width: '640',
		videoId: vid,
		events: {
			'onReady': onPlayerReady,
			'onStateChange': onPlayerStateChange
		}
	});
}


/*4. The API will call this function when the video player is ready. */
function onPlayerReady(event) {
	event.target.playVideo();
	setInterval("checkTime()", 100);
	getCount('N');

	$(function() {
		$("#slider").slider({
			range: true,
			min: 0,
			max: player.getDuration(),
			values: [ 0, 1000 ],
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
		if (Math.abs(time-endTime) < 1 || Math.abs(time-startTime) < 1){
			getCount('Y');
		}
	}

}
/*5. The API calls this function when the player's state changes.
   The function indicates that when playing a video (state=1),
  the player should play for six seconds and then stop.*/
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

function GetLyric(id, title, vid, update)
{
	$.get("getSongLyric.php", 
		{lyricId:id,
			title:title,
			vid:vid,
			update: update}, 
			function(data)
			{
				$('#lyricArea').html(data);
			});

}

document.ready = function() {
	getCount('N');
};	  

