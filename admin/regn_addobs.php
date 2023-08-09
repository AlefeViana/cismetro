<?php
    define("DIRECT_ACCESS", true);

//verifica se o usuario esta logado no sistema
require_once("verifica.php");
//verifica se o usuario tem permiss�o para acessar a pagina
if ((int)$_SESSION["CdTpUsuario"] != 1 && (int)$_SESSION["CdTpUsuario"] != 2)	
{
	echo '<script language="JavaScript" type="text/javascript"> 
		window.location.href="index.php?p=inicial";				
	  </script>';	
}
/*<body onUnload="javascript:window.opener.location.href='../index.php?p=lista_agendamento'">
</body>*/
//recebe as variaveis do formulario
$Obs       = $_POST["obs1"];
$CdSolCons = (int)$_POST["codigo"];

//recebe o tipo de acao
$Acao       = $_POST["acao"];

if ($Acao == "" && $CdSolCons === 0){
	echo '<script language="JavaScript" type="text/javascript"> 
		alert("Nada foi executado!");
		window.close();
	  </script>';
}
else
{
	//alterar
	$Status = '1';
	
	require("../conecta.php");
	
	if ($Acao == 'del'){			
		//cancelar
		$Status = 2;
		$qry = mysqli_query($db,"SELECT CdPref,Valor FROM tbsolcons s INNER JOIN tbpaciente p ON s.CdPaciente=p.CdPaciente
															     INNER JOIN tbbairro b ON p.CdBairro=b.CdBairro	
																 INNER JOIN tbagendacons ag ON s.CdSolCons=ag.CdSolCons
								WHERE s.CdSolCons=$CdSolCons") or die (mysqli_error());
			if(mysqli_num_rows($qry) == 1)
			{						   
				$CdPref = mysqli_result($qry,0,'CdPref');
				$Valor  = mysqli_result($qry,0,'Valor');
				
				$qry = mysqli_query($db,"SELECT MAX(CdMov) FROM tbmovimentacao")
								or die (mysqli_error());	
				$CdMov = mysqli_result($qry,0) + 1;
				
				$sql = "INSERT INTO tbmovimentacao(CdMov,CdPref,CdUsuario,CdSolCons,TpMov,Credito)
							VALUES($CdMov,$CdPref,$_SESSION[CdUsuario],$CdSolCons,'4','$Valor')";
				$qry = mysqli_query($db,$sql)or die (mysqli_error());				
			}
	}
	
	$sql = "UPDATE tbsolcons
				SET Status = '$Status',";
	if ($Obs != "")
		  $sql .= " Obs1   = '$Obs',";
		  
		  $sql .= "	DtAlt  = NOW(),
					UserAlt= $_SESSION[CdUsuario]
				WHERE CdSolCons=$CdSolCons";
		
	$qry = mysqli_query($db,$sql) or die (mysqli_error());
	
	//realizacao da consulta
	$Realizado = $_POST["realizado"];
	if (isset($Realizado)){
		
		$qry = mysqli_query($db,"UPDATE tbagendacons SET Status='2', DtAlt=NOW(), UserAlt=$_SESSION[CdUsuario] WHERE CdSolCons=$CdSolCons") 
						or die (mysqli_error());
		
	}
	echo '<script language="JavaScript" type="text/javascript"> 
		alert("Dados alterados com sucesso!");
		
		window.close();			
	  </script>';
		
	@mysqli_close();
	@mysqli_free_result($qry);
}
?>