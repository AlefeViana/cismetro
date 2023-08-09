<?php
//Paliativo
session_start();
include("../funcoes.php");

		  	$cdespecificacao = $_GET[cdespecificacao];
		  	$periodo = $_GET[mes];

		  	$parte = explode('-',$periodo);

		  	$mesaux = $parte[1];
		  	$year = $parte[0];

			$pref = "AND sc.CdPref = $_SESSION[CdOrigem]";



			 $sqlres = "SELECT Count(ac.CdSolCons) AS qts,sc.CdPref
				  FROM
				  tbagendacons ac
				  INNER JOIN tbsolcons sc ON sc.CdSolCons = ac.CdSolCons
				  WHERE (sc.Status='1' AND ac.Status='1' OR sc.Status='1' AND ac.Status='2')
				  $pref
				  AND sc.CdUnid is NULL
				  AND sc.extra is NULL
				  AND MONTH(ac.DtAgCons) = $mesaux
				  AND YEAR(ac.DtAgCons) = $year
				  AND sc.CdEspecProc = $cdespecificacao";
	   //echo $sqlres;
	   $sqlres = mysqli_query($db,$sqlres) or die("Erro sqlres: ".mysqli_error());
	   $lres = mysqli_fetch_array($sqlres);
	   
		$sqlf = "SELECT Count(ac.CdSolCons) AS qts,sc.CdPref
			  FROM
			  tbagendacons ac
			  INNER JOIN tbsolcons sc ON sc.CdSolCons = ac.CdSolCons
			  WHERE (sc.Status='F')
				AND sc.CdUnid is NULL
				AND sc.extra is NULL
			  AND sc.CdPref = $_SESSION[CdOrigem]
			  AND MONTH(ac.DtAgCons) = $mesaux
			  AND YEAR(ac.DtAgCons) = $year
			  AND sc.CdEspecProc = $cdespecificacao";
		//echo $sqlf;
		$sqlf = mysqli_query($db,$sqlf) or die("Erro 123");
		$lf = mysqli_fetch_array($sqlf);
		
		$sqla = "SELECT af.cdagenda_fornecedor,af.`status`,af.cdpref
						  FROM
						  tbagenda_fornecedor AS af
						  WHERE af.cdpref = '$_SESSION[CdOrigem]'
						  AND af.`status` = 'A'
						  AND DATE_FORMAT(af.`data`,'%m-%Y') = '$mesaux-$year'
						  AND af.cdespecificacao = '$lin[CdEspecProc]' 
						  /*AND af.CdUnid is NULL*/";
			//echo $sqla;			 
			$sqlreserva = mysqli_query($db,$sqla) or die("Erro: ".mysqli_error());
			$la = mysqli_num_rows($sqlreserva);	
	   
	  
	   $qtdres = $lres[qts]+$lf[qts]+$la;

		echo '<input type="hidden" name="gasto" id="gasto" value="'.$qtdres.'">';
