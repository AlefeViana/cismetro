<?php 

function addCaracter($var, $caracter, $lim){
	$tamanho = strlen($var);
	if($tamanho > $lim){	
		$quebra = $tamanho/$lim;
		$ini = 0;
		$fim = $lim;
	
		for($i=0; $i <= intval($quebra); $i++){
			if($i == intval($quebra))
				$nova.= substr($var, $ini, $lim);
			else
				$nova.= substr($var, $ini, $lim).$caracter;
		
			$ini = $fim;
			$fim = $fim+$lim;
		}
	
		return $nova;
		
	} else {
		return $var;
	}

}


	$stringtest = "Amanhã, Depois, Proximo, Next, Back, Black, White, Lindo, Demais, Perfeitin, Viu Guarani!!!!";
	
	addCaracter($stringtest, "|", "25");
	
	
	echo $stringtest;

	

?>