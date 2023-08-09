<?php
require_once("verifica.php");
require("../funcoes.php");
//verifica se o usuario tem permissão para acessar a pagina
if ((int)$_SESSION["CdTpUsuario"] != 1 && (int)$_SESSION["CdTpUsuario"] != 2)	
{
	echo '<script language="JavaScript" type="text/javascript"> 
		window.location.href="index.php";				
	  </script>';	
}
//funcao para tratar erro
require("function_trata_erro.php");
$conf = $_POST['conf'];
$canc = $_POST['canc'];
$falta = $_POST['falta'];
$op = $_POST['op'];
$i2 = $_POST['i'];
$pag = $_POST['pag'];
if($i2 == "")
	$i2 = 7;
$busca = ""; $cbopor = "";
if($_POST['busca'] != "" || $_POST['cbopor'] != "0"){		
	$busca    = "&pesq=".$_POST['busca'];
	$cbopor   = "&cbopesq=".(int)$_POST['cbopor'];
}
		
if($conf>0){
	//require("../conecta.php");
	$usr = (int)$_SESSION["CdUsuario"];
	$dt = date("Y-m-d")." ".date("H:i:s");
	$hj = date("Y-m-d");
	for($i=0;$i<count($conf);$i++){
		$cd = $conf[$i];
			set_realizado($cd,1);	
		//$sql = mysqli_query($db,"UPDATE tbagendacons SET Status='2',DtAlt='$dt',UserAlt=$usr WHERE (CdSolCons='$conf[$i]') AND DtAgCons <= '$hj'") or die (mysqli_error());
	}	
	echo '<script language="JavaScript" type="text/javascript"> 
			alert("Agenda(s) confirmada(s) com sucesso!");
			window.location.href="../index.php?i=6&op='.$op.$busca.$cbopor.'&pag='.$pag.'";				
		  </script>';	
}

if($canc>0){
	//require("../conecta.php");
	$usr = (int)$_SESSION["CdUsuario"];
	$dt = date("Y-m-d")." ".date("H:i:s");
	for($i=0;$i<count($canc);$i++){
			$cd = $canc[$i];
				set_canc_novo($cd,"1","");		
		//$sql = mysqli_query($db,"UPDATE tbsolcons SET Status='2',dtcanc='$dt',usercanc='$usr' WHERE (CdSolCons='$canc[$i]')") or die (mysqli_error());
	}		
		echo '<script language="JavaScript" type="text/javascript"> 
							alert("Agenda(s) cancelada(s) com sucesso!");
							window.location.href="../index.php?i=6&op='.$op.$busca.$cbopor.'&pag='.$pag.'";				
						  </script>';	
}

if($falta>0){
	//require("../conecta.php");
	$usr = (int)$_SESSION["CdUsuario"];
	$dt = date("Y-m-d")." ".date("H:i:s");
	for($i=0;$i<count($falta);$i++){
		if(validafat($falta[$i],1))
		{
			$cd = $falta[$i];
				set_falta($cd);
		}//validação
	}
	if($sql){
		//@mysqli_close();
		@mysqli_free_result($sql);		
		echo '<script language="JavaScript" type="text/javascript"> 
							alert("Falta(s) aplicadas com sucesso!");
							window.location.href="../index.php?i=6&op='.$op.$busca.$cbopor.'&pag='.$pag.'";				
						  </script>';
	}	
}

$CdUser = $_SESSION["CdUsuario"];
//funcao para formatar data para formato americano
function FData($data){
	$val = explode("/",$data);
	return $val[2]."-".$val[1]."-".$val[0];	
}
//foreach ($_POST as $campo => $valor) { $$campo = trim(strip_tags($valor));}
//indice das consultas
$i = 0;
$j = 0;
//recebe os registros da grid e organiza-os em um array
foreach($_POST as $campo => $valor){
	//echo $campo." = ".trim(strip_tags($valor))."<br />";
	switch($j){
		case 0:	$FormVars[$i]['CdCons'] = (int)substr($campo,4);
				$FormVars[$i]['Data'] = trim(strip_tags($valor));
				break;
		case 1: $FormVars[$i]['Hora'] = trim(strip_tags((string)$valor));
				break;
		case 2:	$FormVars[$i]['Local'] = (int)trim(strip_tags($valor));
				break;
		case 3: $FormVars[$i]['Valor'] = str_replace(",",".",str_replace(".","",trim(strip_tags($valor))));
				break;
	}
	$j++;
	if ($j == 4){
		$j = 0;
		$i++;
	}
}

//recebe variaveis do formulario de pesquisa para recuperar o filtro
	$varspesq = $_POST["varspesq"];

for ($j=0;$j<$i;$j++){
	
	$CdCons = $FormVars[$j]['CdCons'];
	$Data = FData($FormVars[$j]['Data']);
	if( !@checkdate( substr($Data,5,2),substr($Data,8,2),substr($Data,0,4) ) )
		$Data = NULL;
	
	$Hora = $FormVars[$j]['Hora'];
	if((!is_numeric(substr($Hora,0,2)) || !is_numeric(substr($Hora,3,2)) ) && substr($Hora,0,2) > 23 && substr($Hora,3,2) > 59)
		$Hora = NULL;
	
	$Local  = $FormVars[$j]['Local'];
	if ($Local === 0)
		$Local = NULL;
		
	$Valor  = $FormVars[$j]['Valor'];
	if (!is_numeric($Valor))
		$Valor = NULL;
		
	$sql = "SELECT CdSolCons,CdForn,DtAgCons,HoraAgCons,Valor FROM tbagendacons WHERE CdSolCons=".$CdCons;
	//require("../conecta.php");
	$qry = mysqli_query($db,$sql) 
			or die(TrataErro(mysqli_errno(),'','../index.php?p=lista_agendamento','regn_agd: select dados agenda'));
	$result = mysqli_num_rows($qry);
	if ($result === 1){
			 $dados = mysqli_fetch_array($qry);
			 
			 echo $_POST['data_cons'];
			 
			 
		    /*$sql = "UPDATE tbagendacons
					 SET DtAgCons   = '$Data',
						 HoraAgCons = '$Hora',					
						 Valor	    = $valor,
						 CdForn     = $Local,
						 DtAlt      = NOW(),
						 UserAlt    = $CdUser
					 WHERE CdSolCons = $CdCons";
			if (($dados["CdForn"] != $Local || $dados["DtAgCons"] != $Data || $dados["HoraAgCons"] != $Hora) 
				&& $Local > 0 && $Data != NULL && $Hora != NULL)
			{
				$qry = mysqli_query($db,$sql) or die (TrataErro(mysqli_errno(),'','../index.php?p=lista_agendamento','regn_agd: update dados agenda'));											
				$msg = 1;
			}*/

	}
	else
	{
		
		
			$sql = "INSERT INTO tbagendacons (CdSolCons,CdForn,CdUsuario";
			if (isset($Data))
				$sql .= ",DtAgCons";
			if (isset($Hora))
				$sql .= ",HoraAgCons";	
			if (isset($Valor))
				$sql .= ",Valor";
			
			$sql .= ") VALUES ($CdCons,$Local,$CdUser";
			if (isset($Data))
				$sql .= ",'$Data'";
			if (isset($Hora))
				$sql .= ",'$Hora'";
			if (isset($Valor))
				$sql .= ",'$Valor'";	
			
			$sql .= ")";
		//echo $sql;
		if ($Local > 0 && $Data != NULL && $Hora != NULL && $Valor != NULL && $Valor > 0 )
		{
			$qry = mysqli_query($db,$sql) or die (TrataErro(mysqli_errno(),'','../index.php?p=lista_agendamento','regn_agd: insert dados agenda'));

			//consulta prefeitura
			$qry = mysqli_query($db,"SELECT CdPref FROM tbsolcons s INNER JOIN tbpaciente p ON s.CdPaciente=p.CdPaciente
															 INNER JOIN tbbairro b ON p.CdBairro=b.CdBairro														
								WHERE s.CdSolCons=$CdCons") or die (TrataErro(mysqli_errno(),'','../index.php?p=lista_agendamento','regn_agd: select pref'));
			if(mysqli_num_rows($qry) == 1)
			{						   
				$CdPref = mysqli_result($qry,0,'CdPref');
				$qry = mysqli_query($db,"SELECT MAX(CdMov) FROM tbmovimentacao")
								or die (TrataErro(mysqli_errno(),'','../index.php?p=lista_agendamento','regn_agd: gera cd movimentacao'));	
				$CdMov = mysqli_result($qry,0) + 1;
				
				$sql = "INSERT INTO tbmovimentacao(CdMov,CdPref,CdUsuario,CdSolCons,TpMov,Debito)
							VALUES($CdMov,$CdPref,$_SESSION[CdUsuario],$CdCons,'3','$Valor')";
				$qry = mysqli_query($db,$sql)or die (TrataErro(mysqli_errno(),'','../index.php?p=lista_agendamento','regn_agd: insert movimentacao'));				
			}
			$msg = 1;
		}
	}
//echo "<br />".$FormVars[$j][CdCons]." ".$FormVars[$j][Data]." ".$FormVars[$j][Hora]." ".$FormVars[$j][Local]." ".$FormVars[$j][Valor];
}
//end for

	if ($msg)
		echo '<script language="JavaScript" type="text/javascript"> 
					alert("Dados alterados com sucesso!");
					window.location.href="../index.php?i='.$i2.'&pag='.$pag.'&'.$varspesq.'";					
			  </script>';
	else
		echo '<script language="JavaScript" type="text/javascript"> 
					alert("Nenhum item foi alterado!");
					window.location.href="../index.php?i='.$i2.'&pag='.$pag.'&'.$varspesq.'";				
			  </script>';
?>