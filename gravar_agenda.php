<?php
	include("../conecta.php");
	session_start();
	function addTime($hora,$ivm)
	{
		$horaNova = strtotime("$hora + $ivm minutes");
		$horaNovaFormatada = date("H:i",$horaNova);
		return $horaNovaFormatada;
	}
		
    $periodo    = $_POST[cmb_per];
	$periodo2   = $_POST[cmb_per2];
	
	$quant 	    = $_POST[txt_qtd];
	$quant2	    = $_POST[txt_qtd2];
	
	$espec      = $_POST[cmb_espec];
	$espec2     = $_POST[cmb_espec2];
	
	$hora       = $_POST[txt_hora];
	$hora2      = $_POST[txt_hora2];
	
	$intervalo  = $_POST[txt_inter];
	$intervalo2 = $_POST[txt_inter2];
	
	//$inter      = $_POST[txt_inter];
	$cdforn     = $_POST[cdforn];
	$cdprof     = $_POST[cdprof];
	$data       = $_POST[data];
	
	$ultimo_dia = $_POST[t_ultimo_dia];
	
	$i = 1;
	while($i <= $ultimo_dia){		
	/* echo "#### $i<br />
	      Periodo: $periodo[$i]    <br />
		  Quantidade: $quant[$i]   <br />
		  Especialidade: $espec[$i]<br />
		  Hora: $hora[$i]          <br />
		  Intervalo: $intervalo[$i]    <br />
		  Cod. Forn.: $cdforn      <br />
		  Cod. Prof:  $cdprof      <br />
		  Data:       $data[$i]    <br /><br /><br /><br /><br /><br />"; */
		  	
			if($quant[$i] != "" || $quant2[$i] != ""){
				$sql = "UPDATE tbdias_aten SET cdforn 		= '$cdforn',
											   cdprof 		= '$cdprof',
											   cdespec1		= '$espec[$i]',
											   cdespec2		= '$espec2[$i]',
											   periodo1 	= '$periodo[$i]',
											   qtdp1 		= '$quant[$i]',
											   horainiciop1 = '$hora[$i]',
											   periodo2		= '$periodo2[$i]',
											   qtdp2		= '$quant2[$i]',
											   horainiciop2 = '$hora2[$i]',
											   intervalo1   = '$intervalo[$i]',
											   intervalo2   = '$intervalo2[$i]'
						WHERE data = '$data[$i]' AND cdforn = '$cdforn' AND cdprof = '$cdprof'";
				$sql = mysqli_query($db,$sql) or die("<br />Erro: ".mysqli_error()); 

				for($m=1;$m<=$quant[$i]; $m++){
					/*
					echo "Teste FOR quant - ".$m." preriodo: $periodo[$i] data: $data[$i]<br>";
					$obs             = "";
					$cdpref          = 0;
					$status          = "A"; */
								
					$sql_cdproc = "SELECT cdprocedimento
					FROM tbfornecedor, tbespecproc, tbfornespec
					WHERE tbfornecedor.CdForn = tbfornespec.CdForn
					AND tbfornecedor.CdForn = '$cdforn'
					AND tbespecproc.CdEspecProc = '$espec[$i]'
					GROUP BY CdProcedimento";
					$res_cdproc = mysqli_query($db,$sql_cdproc) or die("<br />Erro: ".mysqli_error());
					
					$cdprocedimento = mysqli_fetch_array($res_cdproc); 
					
					/*echo "cdfornecedor    : ".$cdforn." <br> ";
					echo "cdprocedimento  : ".$cdprocedimento[0]." <br> ";
					echo "obs             : ".$obs." <br> ";
					echo "cdespecificacao : ".$espec[$i]." <br> ";
					echo "cdpref          : ".$cdpref." <br> ";
					echo "data            : ".$data[$i]." <br> ";
					echo "hora            : ".$hora[$i]." <br> ";
					echo "status          : ".$status." <br> ";
					echo "usrexc          : "." <br> ";
					echo "dtexc           : "." <br> ";
					echo "<br> <br> ";*/
			        
					
					$sql_manha = mysqli_query($db,"
					INSERT INTO `tbagenda_fornecedor` 
					(`cdfornecedor`, `cdprocedimento`, `obs`, `cdespecificacao`, `cdpref`, `data`, `hora`, `status`, `usrexc`, `dtexc`,`usrinc`,`dtinc`) 
					VALUES 
					('$cdforn', '$cdprocedimento[0]', '', '$espec[$i]', '999', '$data[$i]', '$hora[$i]', 'A', '', '','$_SESSION[CdUsuario]','".date("Y-m-d H:i:s")."') 
					") or die (mysqli_error()); 
					
					if($intervalo[$i])
						$hora[$i] = addTime($hora[$i],$intervalo[$i]);
					//echo "$hora[$i] -- $intervalo[$i]<br />";
		
				}
				//echo " <br> ";
				for($m=1;$m<=$quant2[$i]; $m++){
					
					/*echo "Teste FOR quant2 - ".$m." periodo: $periodo2[$i] data: $data[$i]<br>";
					$obs             = "";
					$cdpref          = 0;
					$status          = "A"; */
					
								
					$sql_cdproc = "SELECT cdprocedimento
					FROM tbfornecedor, tbespecproc, tbfornespec
					WHERE tbfornecedor.CdForn = tbfornespec.CdForn
					AND tbfornecedor.CdForn = '$cdforn'
					AND tbespecproc.CdEspecProc = '$espec2[$i]'
					GROUP BY CdProcedimento";
					$res_cdproc = mysqli_query($db,$sql_cdproc) or die("<br />Erro: ".mysqli_error());
					
					$cdprocedimento = mysqli_fetch_array($res_cdproc); 
					
					/*
					echo "cdfornecedor    : ".$cdforn." <br> ";
					echo "cdprocedimento  : ".$cdprocedimento[0]." <br> ";
					echo "obs             : ".$obs." <br> ";
					echo "cdespecificacao : ".$espec2[$i]." <br> ";
					echo "cdpref          : ".$cdpref." <br> ";
					echo "data            : ".$data[$i]." <br> ";
					echo "hora            : ".$hora2[$i]." <br> ";
					echo "status          : ".$status." <br> ";
					echo "usrexc          : "." <br> ";
					echo "dtexc           : "." <br> ";
					echo "<br> <br> "; */
					
					$sql_tarde = mysqli_query($db,"
					INSERT INTO `tbagenda_fornecedor` 
					(`cdfornecedor`, `cdprocedimento`, `obs`, `cdespecificacao`, `cdpref`, `data`, `hora`, `status`, `usrexc`, `dtexc`) 
					VALUES 
					('$cdforn', '$cdprocedimento[0]', '', '$espec2[$i]', '999', '$data[$i]', '$hora2[$i]', 'A', '', '') 
					") or die (mysqli_error()); 
					
					if($intervalo2[$i])
						$hora2[$i] = addTime($hora2[$i],$intervalo2[$i]);
					//echo "<br />$hora2[$i] -- $intervalo2[$i]<br />";
				}
			}
		$i++;
	}
	
    $mes = substr($data[1],5,2); 
	echo '<script language="JavaScript" type="text/javascript">
			//alert("Quant.: '.$ultimo_dia.'");
			alert("Agendas gravadas com sucesso!");
			window.location.href="../index.php?i=5&s=pgforn&cdforn='.$cdforn.'&cdprof='.$cdprof.'&f=d&mes='.$mes.'&ano='.date("Y").'";
	      </script>	'; 
	
?>