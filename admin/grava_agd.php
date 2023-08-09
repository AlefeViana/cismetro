<?php

define("DIRECT_ACCESS",  true);

$i = 0;
$j = 0;
//recebe os registros da grid e organiza-os em um array
foreach($_POST as $campo => $valor){
	//echo $campo." = ".trim(strip_tags($valor))."<br />";
	switch($j){
		case 0:	$FormVars[$i][data_cons] = $valor;
				$FormVars[$i][valor]     = $valor;
				echo $valor."<br>";
				
				break;
	}
	$j++;
	if ($j == 4){
		$j = 0;
		$i++;
	}
}




?>