<?php
//includes
include 'zee5.php';
include 'sonyliv.php';
include 'mxplayer.php';

//Get Request Params
$catalog = getRequestParams();
setHeaders();

$get_id = explode(":",$catalog->id);
if ($get_id['0'] == "zee5") {
    $fin = zee5_stream($get_id['1']);
    echo json_encode(generate_stream($fin),JSON_UNESCAPED_SLASHES);
}
elseif ($get_id['0'] == "sliv") {
    $fin = liv_get_info_id($get_id['1']);
    echo json_encode(generate_stream($fin),JSON_UNESCAPED_SLASHES);
}
elseif ($get_id['0'] == "mx") {
    $fin = mxplayer_get_stream($get_id['1']);
    echo json_encode(generate_stream($fin),JSON_UNESCAPED_SLASHES);
}
else
	echo "null";
?>