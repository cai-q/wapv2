<?php
	$c = curl_init();

	curl_setopt($c, CURLOPT_URL,"http://test.cnhubei.com/wap/c_mobile.php?url=http://news.cnhubei.com/xw/jj/201409/t3044631.shtml");

	curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);

	$output = curl_exec($c);
	echo "output:".$output;
	curl_close($c);

?>