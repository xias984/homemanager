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
}
?>