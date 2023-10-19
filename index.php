<?php
	require_once("system/includes.php");
    
	if($maintenance == true)
	{
		echo "Sito in manutenzione";
	}
	elseif($maintenance == false)
	{
		require_once("frontend/base.php");
	}
?>