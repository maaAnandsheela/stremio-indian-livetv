<?php

include 'helpers.php';

function zee5_get_feed() {
	$client = new \GuzzleHttp\Client(array(
			'curl'            => array( CURLOPT_SSL_VERIFYPEER => false, CURLOPT_SSL_VERIFYHOST => false ),
			'allow_redirects' => false,
			'cookies'         => true,
			'verify'          => false
		) );
    $response = $client->get("https://catalogapi.zee5.com/v1/channel/bygenre?sort_by_field=channel_number&sort_order=ASC&genres=FREE%20Channels,Hindi%20Entertainment,Hindi%20Movies,English%20Entertainment,Entertainment,Movie,News,Hindi%20News,English%20News,Marathi,Tamil,Telugu,Bengali,Malayalam,Kannada,Punjabi,Kids,Gujarati,Odiya,Music,Lifestyle,Devotional,Comedy,Drama,Sports,Infotainment&country=IN&translation=en&languages=en,hi,te");	
	$data = json_decode($response->getBody()->getContents(),true);
	$i =0;
	foreach($data['items'] as $t) {
		foreach ($t['items'] as $d) {
			if (!($d['id'] == "0-9-214" || $d['id'] == "0-9-channel_477236247" || $d['id'] == "0-9-210")) {		 
				$final[$i]['id'] = "zee5:{$d['id']}";
				$final[$i]['poster'] = "https://akamaividz2.zee5.com/image/upload/w_386,h_386,c_scale/resources/{$d['id']}/channel_web/{$d['list_image']}";
				$final[$i]['name'] = $d['title'];		
				$final[$i]['description'] = $d['description'];		
				$metas['metas'][] = generate_meta($final[$i]);
				$i++;
			}
		}
	}
	return $metas;
}

function zee5_meta($id) {
	$client = new \GuzzleHttp\Client(array(
			'curl'            => array( CURLOPT_SSL_VERIFYPEER => false, CURLOPT_SSL_VERIFYHOST => false ),
			'allow_redirects' => false,
			'cookies'         => true,
			'verify'          => false
	) );
    $response = $client->get("https://catalogapi.zee5.com/v1/channel/{$id}?translation=en&country=IN");	
	$data = json_decode($response->getBody()->getContents(),true);
	$final['poster'] = "https://akamaividz2.zee5.com/image/upload/w_386,h_386,c_scale/resources/{$id}/channel_web/{$data['list_image']}";	
	$final['name'] = $data['title'];
	$final['description'] = $data['description'];	
	$final['id'] = "zee5:{$id}";	
	return $final;
}

function zee5_get_token() {
	$client = new \GuzzleHttp\Client(array(
			'curl'            => array( CURLOPT_SSL_VERIFYPEER => false, CURLOPT_SSL_VERIFYHOST => false ),
			'allow_redirects' => false,
			'cookies'         => true,
			'verify'          => false
	) );
    $response = $client->get("https://gwapi.zee5.com/user/videoStreamingToken?channel_id=0-9-zing&country=IN&device_id=WebBrowser");	
	$data = json_decode($response->getBody()->getContents(),true);
	return $data['video_token'];
}

function zee5_stream($id) {
	$client = new \GuzzleHttp\Client(array(
			'curl'            => array( CURLOPT_SSL_VERIFYPEER => false, CURLOPT_SSL_VERIFYHOST => false ),
			'allow_redirects' => false,
			'cookies'         => true,
			'verify'          => false
	) );
    $response = $client->get("https://catalogapi.zee5.com/v1/channel/{$id}?translation=en&country=IN");	
	$data = json_decode($response->getBody()->getContents(),true);
	$token = zee5_get_token();
	$arr = array();
	$arr['name'] = $data['title'];
	$arr['title'] = "zee5";
	$arr['streams']['0']['url'] = "{$data['stream_url_hls']}{$token}";
	$arr['streams']['0']['res'] = "Adaptive Bitrate";		
	return $arr;
}
