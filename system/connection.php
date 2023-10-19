<?php
require_once('env.php');

$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
	
//CHECK CONNECTION
if ($conn->connect_error)
{
    die("Connection failed: ".$conn->connect_error);
}