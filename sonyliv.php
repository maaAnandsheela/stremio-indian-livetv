<?php
//includes 

define("liv_base_url", "https://www.sonyliv.com/");
define("liv_build", "1e2f1b8ff0eb2e79b57f0246f6c743477c736c846c92dc7bbd779bf5d2ce4f2ead589deebbd542f09183b22b5e720de729b1ae1fdb678beb6577fb5197f5c3940e6190cd9fdef7ff80287022cc512b410513ebcee73c084e230597b2fa8e23709dd0da1bf0c27381c50678bfe00305a1"); //Needs to be updated if stream doesnt work from view source.

function liv_get_feed() {
	//sending request to retrieve list
	$client = new \GuzzleHttp\Client(array(
			'curl'            => array( CURLOPT_SSL_VERIFYPEER => false, CURLOPT_SSL_VERIFYHOST => false ),
			'allow_redirects' => false,
			'cookies'         => true,
			'verify'          => false
		) );
	$json = '{"detailsType":"basic","searchSet":[{"pageSize":110,"pageNumber":0,"sortOrder":"START_DATE:DESC","data":"all=classification:channel&all=type:live","type":"search","id":"live_channels","itemsUsed":0,"language":""}],"deviceDetails":{"mfg":"WEB","os":"others","osVer":"XXX","model":"WEB","deviceId":"f45c45fe-022","platform":"web"},"isSearchable":true}';
	
    $options = [
    'body' => $json,
    'headers' => ['Content-Type' => 'application/json','x-build' => liv_build],
    ];
    $response = $client->post(liv_base_url."api/v4/vod/search", $options);	
	$data = json_decode($response->getBody()->getContents(),true);
	$i =0;
	foreach ($data['0']['assets'] as $d) {
		//id
		$final[$i]['id'] = "sliv:{$d['id']}";
		
		//images
	    $final[$i]['poster'] = $d['thumbnailUrl'];

		//name
		if (empty($d['channel'])) 
			$name = $d['showname'];
		else
			$name = $d['channel'];
		
		$final[$i]['name'] = $name;
		
		$final[$i]['description'] = $d['shortDesc'];
		
	    $final[$i]['genre'] = array("{$d['genre']}");
		
	    $metas['metas'][] = generate_meta($final[$i]);
		$i++;
	}
	return $metas;
}

function liv_get_info_id($id) {
	$client = new \GuzzleHttp\Client(array(
			'curl'            => array( CURLOPT_SSL_VERIFYPEER => false, CURLOPT_SSL_VERIFYHOST => false ),
			'allow_redirects' => false,
			'cookies'         => true,
			'verify'          => false
		) );
	$json = '{"detailsType":"all","isDetailedView":true,"asset_ids":"'.$id.'","timestamp":"2017-11-23T21:23:27.178Z","deviceDetails":{"mfg":"WEB","os":"others","osVer":"XXX","model":"WEB","deviceId":"f45c45fe-022e-4865-a26f-19794a8ae30d"}}';
	
    $options = [
    'body' => $json,
    'headers' => ['Content-Type' => 'application/json','x-build' => liv_build],
    ];
    $response = $client->post(liv_base_url."api/v4/vod/asset/details", $options);	
	$data = json_decode($response->getBody()->getContents(),true);
    $d = $data['assets']['0']['details'];
	
	//vid_links
	$url = preg_replace('/\s/', '', $d['hlsUrl']);
	$final['streams'] = liv_parse_m3u8($url);
	//$final['streams']['0']['url'] = $d['hlsUrl'];
	//$final['streams']['0']['res'] = "Adaptive Bitrate";
	
	//genres
	$final['genre'] = array("{$d['genre']}");
	
	//images only one size to reduce bandwidth on server because they will be served in base64. Due to referrer problem in beeg.com
	$final['img'] = $d['tvBackgroundImage'];
	$final['poster'] = $d['thumbnailUrl'];
	
	//title and description
	if (empty($d['channel'])) 
		$name = $d['showname'];
	else
		$name = $d['channel'];
	$final['title'] = "SonyLiv";
	$final['name'] = $name;
	$final['description'] = $d['metaDesc'];
	
	//id
	$final['id'] = "sliv:{$id}";
	
	return $final;
}

function liv_parse_m3u8($url) {
	$data = file_get_contents($url);
	$data = explode("#",$data);
	$len = count($data);
	$ur = parse_url($url);
	$ur = "https://{$ur['host']}{$ur['path']}";
	if (strpos($url, 'yupptvtest') !== false)
		$yupp = 1;	
	for ($i =3; $i < $len; $i++) {
		$c = $i - 3;
		$s = explode("\n",$data[$i]);
		$res = explode(",",$data[$i]);
		if (!empty($s['1']) && !$yupp) {
			$l = parse_url($s['1']);
			if (empty($l['host'])) {
				$s['1'] = str_replace("master.m3u8",$l['path'],$ur);
			}
		}
		if (!empty($s['1']) && $yupp) {
			$s['1'] = str_replace("../../","https://sony-yupp.akamaized.net/hls/live/",$s['1']);
		}
		$streams[$c]['res'] = $res['4'];
        $streams[$c]['url'] = $s['1'];	
	}
	return $streams;
}