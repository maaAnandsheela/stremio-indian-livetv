<?php

//Manifest Config
$config['id'] = "live.indian.tv";
$config['description'] = "Watch Indian Live TV channels from many sources. Some of them works only on Indian IP's as the content provided is Indian.";
$config['name'] = "Indian Live TV";
$config['icon'] = "https://www.materialui.co/materialIcons/notification/live_tv_black_192x192.png";
$config['version'] = "1.0.0";

//Cache Settings
define("cache_status", 0); //defaults to 1 that is enabled. 
define('cache_path', dirname(__FILE__).'/temp/'); //replace it with your path.	Dont change the directory if deploying on heroku and probably wont work on heroku.
define('cache_meta_ttl',86400); //meta of stream cache time	in s; defaults to 1 day
define('cache_catalog_ttl',3600); //catalog of tag cache time in s; defaults to 1 hour