<?php
/* test 20150602 */
header('Content-Type: text/html; charset=utf-8');
/* Info.php contains $DEVELOPER_KEY */
include 'Info.php';
$htmlBody = <<<EOT
<form method="GET">
<div>
Title: <input class="input" type="search" id="q" name="q" placeholder="Enter title">
</div>
<div>
Artist: <input class="input" type="search2" id="q2" name="q2" placeholder="Enter Artist">
</div>
<div>
Max Results: <input class="input" type="number" id="maxResults" name="maxResults" min="1" max="25" step="1" value="10" class="input">
</div>
<input class="btn" type="submit" value="Search">
</form>
EOT;
// This code will execute if the user entered a search query in the form
// and submitted the form. Otherwise, the page displays the form above.
if (isset($_GET['q']) && isset($_GET['maxResults']) && strlen($_GET['q'])>0 && strlen($_GET['maxResults'])>0) {
// Call set_include_path() as needed to point to your client library.
set_include_path($_SERVER["DOCUMENT_ROOT"].'/google-api-php-client-master/src');
require_once ($_SERVER["DOCUMENT_ROOT"].'/google-api-php-client-master/src/Google/autoload.php');
require_once 'Google/Client.php';
require_once 'Google/Service/YouTube.php';
/* combine two criteria */
$_GET['q']=$_GET['q'].' '.$_GET['q2'];
/*
* Set $DEVELOPER_KEY to the "API key" value from the "Access" tab of the
* Google Developers Console <https://console.developers.google.com/>
* Please ensure that you have enabled the YouTube Data API for your project.
*/
$client = new Google_Client();
$client->setDeveloperKey($DEVELOPER_KEY);
// Define an object that will be used to make all API requests.
$youtube = new Google_Service_YouTube($client);
try {
// Call the search.list method to retrieve results matching the specified
// query term.
$searchResponse = $youtube->search->listSearch('id,snippet', array(
'q' => $_GET['q'],
'maxResults' => $_GET['maxResults'],
));
$videos = '';
$channels = '';
$playlists = '';
// Add each result to the appropriate list, and then display the lists of
// matching videos, channels, and playlists.
foreach ($searchResponse['items'] as $searchResult) {
switch ($searchResult['id']['kind']) {
case 'youtube#video':
$videos .= sprintf('<li>%s <a href="javascript:showvideo(%s, %s)" >Click me</a></li>',
$searchResult['snippet']['title'], "'".$searchResult['id']['videoId']."'", "'".$_GET['q']."'");
break;
}
}
$htmlBody .= <<<END
<h3>Videos</h3>
<ul>$videos</ul>
END;
} catch (Google_Service_Exception $e) {
$htmlBody .= sprintf('<p>A service error occurred: <code>%s</code></p>',
htmlspecialchars($e->getMessage()));
} catch (Google_Exception $e) {
$htmlBody .= sprintf('<p>An client error occurred: <code>%s</code></p>',
htmlspecialchars($e->getMessage()));
}
}
else if (isset($_GET['q']) && isset($_GET['maxResults'])){
	/* warn if no title */
	echo '<script>alert("Please enter the title");</script>';
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
		$uid = getenv('HTTP_X_FORWARDED_FOR').time();
		echo "if (!localStorage.getItem('userId'))";
		echo " localStorage.setItem('userId', $uid);";
	?>
	function showvideo(v, title)
	{
		var uid = localStorage.getItem('userId');
		window.location.assign("showvideo.php?v="+v+"&title="+title+"&uid="+uid);	
	}
</script>
</head>
<body>
<?=$htmlBody?>
</body>
</html>
