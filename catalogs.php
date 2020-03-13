<?php

//includes
include 'zee5.php';
include 'sonyliv.php';
include 'mxplayer.php';

//Get Request Params
$catalog = getRequestParams();
setHeaders();	
$get_id = explode("_",$catalog->id);
$cache = cache_check("{$get_id['0']}-{$get_id['1']}");
if ($cache['status']) {
	echo $cache['data'];
}
else {
	if ($get_id['0'] == "zee5") {
		$data = json_encode(zee5_get_feed());
		echo $data;
	}
	elseif ($get_id['0'] == "mx") {
		$data = json_encode(mxplayer_get_feed());
		echo $data;		
	}
	else {
		$data = json_encode(liv_get_feed());
		echo $data;	
	}
}
?>