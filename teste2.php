<?php 


 function moeda($get_valor) {
                $source = array('.', ','); 
                $replace = array('', '.');
                $valor = str_replace($source, $replace, $get_valor); //remove os pontos e substitui a virgula pelo ponto
                return $valor; //retorna o valor formatado para gravar no banco
        }

  
	$a = "5000,30";
	$b ="5,20";
	
	
	$a =moeda($a);
	$b =moeda($b);
	
	
	
	
	// $a = moeda2($a);
	// $b = moeda2($b);
	
	
	
	$c = $a*$b;
	
	$k= number_format($c, 2, ',', '.'); 
	
	// $h = number_format($c, 2, ',', '.'); /* exibe */
	
	$h = moeda($k);
		
	echo $h;
?>


