<?php
//includes
include 'config.php';
include 'helpers.php';
// enable CORS and set JSON Content-Type
setHeaders();

//Manifest Begin
$manifest = new stdClass();
$manifest->id = $config['id'];
$manifest->version = $config['version'];
$manifest->name = $config['name'];
$manifest->description = $config['description'];
$manifest->icon = $config['icon'];
$manifest->resources = array("catalog", "meta", "stream");
$manifest->types = array("tv");
$manifest->idPrefixes = array("sliv","zee5","mx");

// define catalog
$catalog[0]['type'] = "tv";
$catalog[0]['name'] = "zee5 TV";
$catalog[0]['id'] = "zee5_home";
$catalog[1]['type'] = "tv";
$catalog[1]['name'] = "SonyLiv TV";
$catalog[1]['id'] = "sliv_home";
$catalog[2]['type'] = "tv";
$catalog[2]['name'] = "MxPlayer TV";
$catalog[2]['id'] = "mx_home";

// set catalogs in manifest
$manifest->catalogs = $catalog;

//Final JSON
echo json_encode((array)$manifest);

?>