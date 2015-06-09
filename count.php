<?php
	include("licence.php");
	
	if (isset($_POST['uid']) && isset($_POST['vid']))
	{
		$uid = $_POST['uid'];
		$vid = $_POST['vid'];
		$sql = "SELECT * FROM songinfo WHERE songid='$vid' AND userid='$uid'";
		$result = mysqli_query($link, $sql);
		
		$row = mysqli_fetch_array($result);
		$count = $row['coun'];
		if ($_POST['add'] == "Y")
		{
			$count = $row['coun'] + 1;
			$sql = "UPDATE songinfo SET coun='$count' WHERE songid='$vid' AND userid='$uid'";
			$result = mysqli_query($link, $sql);
			
		}
		echo "$count";
	}

?>