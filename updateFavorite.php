<?php
include("licence.php");
if(isset($_GET['userid']) && isset($_GET['songid'])){
	$_SESSION['a'] = 0;
	header('Content-Type: text/html; charset=utf-8');
	$uid = $_GET['userid'];
	$sid = $_GET['songid'];
	$req = "SELECT * FROM songinfo WHERE songid = '$sid' AND userid = '$uid'";
	$res = mysqli_query($link, $req);
	$row = mysqli_fetch_array($res);
	if($row['addtof'] == 'Y'){
		$req2 = "UPDATE songinfo SET addtof = 'N' WHERE userid = '$uid' AND songid = '$sid'";
		$row2 = mysqli_query($link, $req2);
	}
	else if($row['addtof'] == 'N'){
		$req2 = "UPDATE songinfo SET addtof = 'Y' WHERE userid = '$uid' AND songid = '$sid'";
		$row2 = mysqli_query($link, $req2);
	}
}
	$sql = "SELECT * FROM songinfo WHERE userid='$uid' AND addtof='Y'";
	$result = mysqli_query($link, $sql);
	echo "<ul>";
		while($row = mysqli_fetch_array($result))
		{
			$sql2 = "SELECT * FROM lyrics WHERE songid='$row[songid]'";
			$result2 = mysqli_query($link, $sql2);
			$row2 = mysqli_fetch_array($result2);
			$title = htmlspecialchars($row2['title']);
			echo "<li>";
			echo "<a href=\"showvideo.php?v=$row[songid]&uid=$uid&title=$title\">$row[Utitle]</a>";
			echo "</li>";
		}
	echo "</ul>";
?>
