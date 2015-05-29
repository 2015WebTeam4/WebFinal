<!DOCTYPE html>
<?php

	 $fp = fopen('count.txt', 'r');
	  $count = fread($fp, 10);
	  fclose($fp);
	   $fp = fopen('count.txt', 'w');
	  fwrite($fp, $count+1);
	  echo $count;
	  fclose($fp);
?>
</html>