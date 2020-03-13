<?php

function mxplayer_get_feed() {
	$client = new \GuzzleHttp\Client(array(
			'curl'            => array( CURLOPT_SSL_VERIFYPEER => false, CURLOPT_SSL_VERIFYHOST => false ),
			'allow_redirects' => false,
			'cookies'         => true,
			'verify'          => false
		) );
    $response = $client->get("https://api.mxplay.com/v1/web/live/channels");	
	$data = json_decode($response->getBody()->getContents(),true);
	$i =0;
	foreach($data['channels'] as $t) {
			if (!($t['id'] == "7021.SETHD.in" || $t['id'] == "7022.SABHD.in" ||$t['id'] == "7023.MAXHD.in" ||$t['id'] == "7032.SonyMixSD.in" ||$t['id'] == "7031.SonyMarathi.in" ||$t['id'] == "7027.SonyYay.in" ||$t['id'] == "7030.SonyAath.in" ||$t['id'] == "7026.SonyWah.in" || $t['id'] == "7029.SonyPal.in")) {		 
				$final[$i]['id'] = "mx:{$t['id']}";
				$final[$i]['poster'] = mxplayer_get_image($t['image']['1x1']);
				$final[$i]['name'] = $t['title'];		
				$final[$i]['description'] = "{$t['title']} Live Tv.";	
				$metas['metas'][] = generate_meta($final[$i]);
				$i++;
		}
	}
	return $metas;
}

function mxplayer_get_image($url) {
	$exp = explode("/",$url);
	$fin = "https://qqcdnpicweb.mxplay.com/media/images/{$exp['3']}/1x1/5x/{$exp['4']}";
	return $fin;
}


function mxplayer_get_meta($id) {
	$client = new \GuzzleHttp\Client(array(
			'curl'            => array( CURLOPT_SSL_VERIFYPEER => false, CURLOPT_SSL_VERIFYHOST => false ),
			'allow_redirects' => false,
			'cookies'         => true,
			'verify'          => false
		) );
    $response = $client->get("https://api.mxplay.com/v1/web/live/channels");	
	$data = json_decode($response->getBody()->getContents(),true);
	foreach($data['channels'] as $t) {
			if ($t['id'] == $id) {	 
				$final['id'] = "mx:{$t['id']}";
				$final['poster'] = mxplayer_get_image($t['image']['1x1']);
				$final['name'] = $t['title'];		
				$final['description'] = "{$t['title']} Live Tv.";	
		}
	}
	return $final;
}

function mxplayer_get_stream($id) {
	$client = new \GuzzleHttp\Client(array(
			'curl'            => array( CURLOPT_SSL_VERIFYPEER => false, CURLOPT_SSL_VERIFYHOST => false ),
			'allow_redirects' => false,
			'cookies'         => true,
			'verify'          => false
		) );
    $response = $client->get("https://api.mxplay.com/v1/web/live/channels");	
	$data = json_decode($response->getBody()->getContents(),true);
	foreach($data['channels'] as $t) {
			if ($t['id'] == $id) {	 
				$arr['name'] = $t['title'];
				$arr['title'] = "mxplayer";
				$arr['streams']['0']['url'] = $t['stream']['mxplay']['hls']['main'];
				$arr['streams']['0']['res'] = "Adaptive Bitrate";				
		}
	}	
	return $arr;
}
