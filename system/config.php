<?php
require("./configuration/controller/ConfigurationController.class.php");

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$config = new ConfigurationController();
$row = $config->selectConfiguration();

if ($row) {
	$title 			= $row['name'];
	$description 	= $row['description'];
	$logo 			= $row['logo'];
	$maintenance 	= $row['maintenance'];

	$colors			= $config->selectThemeFromConfiguration($row['id_color']);
	
	if ($colors) {
		$colori = explode(',',$colors['palette']);
		foreach ($colori as $colore) {
			$sfondo     = $colori[0];
			$sfondo2    = $colori[1];
			$principale = $colori[2];
			$subtitle   = $colori[3];
			$secondario = $colori[4];
			$link       = $colori[5];
		}
	}
}
?>