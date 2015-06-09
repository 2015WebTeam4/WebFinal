	<?php
	/* test 20150602 */
	header('Content-Type: text/html; charset=utf-8');
	/* Info.php contains $DEVELOPER_KEY */
	include 'Info.php';
	$uid = getenv('HTTP_X_FORWARDED_FOR').time();
	$htmlBody = <<<EOT
	<form method="GET">
	<div>
	Title: <input class="input" type="search" id="q" name="q" placeholder="Title is required">
	</div>
	<div>
	Artist: <input class="input" type="search2" id="q2" name="q2" placeholder="Enter Artist">
	</div>
	
	<input class="btn" type="submit" value="Search">
	</form>
EOT;
	/* This code will execute if the user entered a search query in the form
	and submitted the form. Otherwise, the page displays the form above. */
	$ptn='/[&\|%<>;\'\"]/';
		$_GET['q']=preg_replace($ptn,'',$_GET['q']);
	if($_GET["q"]){
	/* Call set_include_path() as needed to point to your client library. */
		set_include_path($_SERVER["DOCUMENT_ROOT"].'/google-api-php-client-master/src');
		require_once ($_SERVER["DOCUMENT_ROOT"].'/google-api-php-client-master/src/Google/autoload.php');
		require_once 'Google/Client.php';
		require_once 'Google/Service/YouTube.php';
		/* combine two criteria */
		
		/*
		* Set $DEVELOPER_KEY to the "API key" value from the "Access" tab of the
		* Google Developers Console <https://console.developers.google.com/>
		* Please ensure that you have enabled the YouTube Data API for your project.
		*/
		$client = new Google_Client();
		$client->setDeveloperKey($DEVELOPER_KEY);
		/*Define an object that will be used to make all API requests.*/
		$youtube = new Google_Service_YouTube($client);
		try {
		/*Call the search.list method to retrieve results matching the specified
		query term.*/
			$searchResponse = $youtube->search->listSearch('id,snippet', array(
				'q' => $_GET['q'],
				'maxResults' => 10,
				));
			$videos = '';
			$channels = '';
			$playlists = '';
		/*Add each result to the appropriate list, and then display the lists of
		 matching videos, channels, and playlists.*/
		 if(preg_match('/\?v\=/',$_GET['q'])){
		 	header('Location:showvideo.php?v='.$searchResponse['items'][0]['id']['videoId'].'&title='.htmlspecialchars($searchResponse['items'][0]['snippet']['title']).'&uid='.$uid.'&utitle='.$searchResponse['items'][0]['snippet']['title']);
		 }
		 else{
		 	foreach ($searchResponse['items'] as $searchResult) {
				switch ($searchResult['id']['kind']) {
					case 'youtube#video':
					$videos .= sprintf('<li>%s <a href="javascript:showvideo(%s, %s, %s)" >Click me</a></li>',
						$searchResult['snippet']['title'], "'".$searchResult['id']['videoId']."'", "'".$_GET['q']."'", "'".$searchResult['snippet']['title']."'");
					break;
				}
			}
			$htmlBody .= <<<END
			<h3>Videos</h3>
			<ul>$videos</ul>
END;
		 }
			
		} catch (Google_Service_Exception $e) {
			$htmlBody .= sprintf('<p>A service error occurred: <code>%s</code></p>',
				htmlspecialchars($e->getMessage()));
		} catch (Google_Exception $e) {
			$htmlBody .= sprintf('<p>An client error occurred: <code>%s</code></p>',
				htmlspecialchars($e->getMessage()));
		}
	}
?>
<!doctype html>
<html>
<head>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
<link rel="stylesheet" type="text/css" href="myStyle.css">
<title>YouTube Search</title>
<script type='text/javascript'>
	<?php
		echo "if (!localStorage.getItem('userId'))";
		echo " localStorage.setItem('userId', $uid);";
	?>
</script>
</head>
<body>
<script type='text/javascript' src="ut3j.js"></script>
<?=$htmlBody?>
</body>
</html>
