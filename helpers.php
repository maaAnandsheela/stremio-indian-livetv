<?php
//includes
include 'config.php';
include 'vendor/autoload.php';
use Phpfastcache\CacheManager;
use Phpfastcache\Config\ConfigurationOption;

//Default Addon Functions
function str_replace_last( $search , $replace , $str ) {
		if( ( $pos = strrpos( $str , $search ) ) !== false ) {
			$search_length  = strlen( $search );
			$str    = substr_replace( $str , $replace , $pos , $search_length );
		}
		return $str;
}

function getRequestParams() {
    $requestArgs = new stdClass();
    $requestArgs->type = clean_input($_GET["type"]);
    $requestArgs->id = clean_input($_GET["id"]);
    if (isset($_GET["extra"])) {
	     parse_str($_GET["extra"], $requestArgs->extra);
		$requestArgs->extra = (object) $requestArgs->extra;
	}

	return $requestArgs;

}

function setHeaders() {
	header("Access-Control-Allow-Origin: *");
	header("Access-Control-Allow-Headers: *");
    header("Content-Type: application/json");
	header("Cache-Control: max-age=3600");
}

function page404() {
	header("HTTP/1.1 404 Not Found");
    echo "404 Page Not Found";
}

//This Addon Functions
function clean_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

function generate_meta($arr) {
	$meta_arr = array("type" => "tv", 
	                  "id" => $arr['id'],
					  "name" => $arr['name'],
					  "genre" => $arr['genre'],
					  "banner" => $arr['img'],
					  "poster" => $arr['poster'],
					  "background" => $arr['img'],
					  "posterShape" => "landscape",
					  "description" => $arr['description'],
					  "runtime" => "Duration {$arr['runtime']}",
					  "cast" => $arr['cast']);
    return $meta_arr;					  
}

function generate_stream($arr) {
	$name = $arr['title'];
	$title = $arr['name'];
	$i = 0;
	foreach ($arr['streams'] as $d) {
		$stream_arr['streams'][$i]['url'] = $d['url'];
		$stream_arr['streams'][$i]['title'] = "{$title} \n {$d['res']}" ;
		$stream_arr['streams'][$i]['name'] = $name;
		$i++;
	}
	return $stream_arr;
}

//Caching Functions
function cache_create($key,$data,$ttl) {
	if (cache_status) {
       CacheManager::setDefaultConfig(new ConfigurationOption([
        'path' => cache_path
       ]));
	   $objFilesCache = CacheManager::getInstance('files');
	   $CachedString = $objFilesCache->getItem($key);
       if (is_null($CachedString->get())) {
          $CachedString->set($data)->expiresAfter($ttl);
          $objFilesCache->save($CachedString); 
	      return array("status" => 1);
       }
    } 
   else
	   return array("status" => 0);
}

function cache_check($key) {
	if (cache_status) {
       CacheManager::setDefaultConfig(new ConfigurationOption([
        'path' => cache_path, 
       ]));
	   $objFilesCache = CacheManager::getInstance('files');
	   $CachedString = $objFilesCache->getItem($key);
       if (is_null($CachedString->get())) { 
	      return array("status" => 0);
       }
       else
          return array("status" => 1, "key" => $key, "data" => $CachedString->get());
    } 
   else
	   return array("status" => 0);
}
