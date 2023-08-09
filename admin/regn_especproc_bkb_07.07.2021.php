<?php

require("../conecta.php");

function moeda($get_valor) {
                $source = array('.', ','); 
				$replace = array('', '.');
                $valor = str_replace($source, $replace, $get_valor); //remove os pontos e substitui a virgula pelo ponto
                return $valor; //retorna o valor formatado para gravar no banco
        }
		
define("DIRECT_ACCESS", true);
//verifica se o usuario esta logado no sistema
require_once("verifica.php");
//verifica se o usuario tem permissão para acessar a pagina
if ((int)$_SESSION["CdTpUsuario"] != 1 && (int)$_SESSION["CdTpUsuario"] != 2)	
{
//	echo '<script language="JavaScript" type="text/javascript"> 
//		window.location.href="index.php";				
//	  </script>';	
echo json_encode(array('msg'=>"",'erro'=>"Você não tem permissão para acessar esta funcionalidade"));
}

//funcao para tratar erro
require_once("function_trata_erro.php");
//recebe as variaveis do formulario
//require "../funcoes.php";



	if($_POST['cancelar'] == "del")
			{
			
			$CdEspecProc = $_POST['CdEspecProc'];
			$query = mysqli_query($db,"SELECT Status FROM tbespecproc WHERE CdEspecProc = $CdEspecProc");
			$n = mysqli_fetch_array($query);
			if($n['Status'] == 1){
			$sql = "UPDATE tbfornespec SET Status_BKP = `Status` WHERE CdEspec = $CdEspecProc";
			($qry = mysqli_query($db, $sql)) or die(mysqli_error($db));
			$sql = "UPDATE tbespecproc SET `Status` = 0 WHERE CdEspecProc=$CdEspecProc ";
			($qry = mysqli_query($db, $sql)) or die(mysqli_error($db));			
			$sql = "UPDATE tbfornespec SET `Status` = 0 WHERE CdEspec = $CdEspecProc";
			$qry = mysqli_query($db, $sql) or die(mysqli_error($db));
			echo json_encode(array('msg' => "Especificação inativada com sucesso!", 'erro' => ""));
			
		}else{
			$sql = "UPDATE tbespecproc SET `Status` = 1 WHERE CdEspecProc=$CdEspecProc ";
			($qry = mysqli_query($db, $sql)) or die(mysqli_error($db));
			$sql = "UPDATE tbfornespec SET `Status` = Status_BKP WHERE CdEspec = $CdEspecProc";
			($qry = mysqli_query($db, $sql)) or die(mysqli_error($db));
			
			echo json_encode(array('msg' => "Especificação ativada com sucesso!", 'erro' => ""));

		}

	}else{



$params = array();
parse_str($_POST['dados'], $params);


$CdEspecProc       = $params["cd_especproc"];
$NmEspecProc       = $params["nm_especproc"];
$CdProcedimento    = $params["cd_procedimento"];
$Status			   = $params["status"];
$desc_sus		   = $params["desc_sus"];
$ppi			   = $params["ppi"];
$bpa			   = $params["bpa"];
$cdespecialidade   = $params["cdespecialidade"];
$cdgrupoproc	   = $params["cdgrupoproc"];
$nmpreparo		   = $params["nmpreparo"];
$cid			   = $params["cid"];
$maxano 		   = $params["maxano"];
$cdservico		   = $params["servico"];
$cdclass		   = $params["class"];
$valorOld		   = $_POST['valorOld'];
$principal		   = $params["principal"];
$quemAgendar 	   = $params["quemAgendar"];

$Valor = $params["valor"];
$Valor =  moeda($Valor);	

$valorm = $params["valorm"];
$valorm = moeda($valorm);

$valorOld = moeda($valorOld);

$valorsus = $params["valorsus"];
$valorsus=  moeda($valorsus);	
$cdsus = $params["cdsus"];

$acao  = $params["acao"];

//verifica campos obrigatórios
$tudook = 1;
if ($NmEspecProc == ""){
	$msg_erro .= 'Preencha o campo Especificação<br />';
	$tudook = 0; 
}

//Controla se os campos obrigatorios estao preenchidos
if ($tudook == 0){
	echo "Favor verificar o seguinte campo!<br /><br />".$msg_erro;
	echo '<br /><br /><a href="#" onclick="javascript: history.go(-1);">Voltar</a>';		
}
else
{
	if (substr($CdEspecProc,0,4) == "Auto")
	{
	//	require("../conecta.php");
		//gera um novo codigo
		/* $qry = mysqli_query($db,"SELECT MAX(CdEspecProc) FROM tbespecproc") 
			or die(TrataErro(mysqli_errno(),'Especificação','../index.php?p=frm_cadespecproc','regn_especproc:gerar novo codigo'));
			
		$CdEspecProc = mysqli_result($qry,0) + 1; */
		$sql = "INSERT INTO tbespecproc (NmEspecProc,CdProcedimento,UserAlt,Status, valor,valorsus,cdsus, desc_sus, cdespecialidade,ppi,bpa,cdgrupoproc, nmpreparo,cid,cdservico,cdclass,valorm,maxano,principal,quemAgendar)
					VALUES('$NmEspecProc',$CdProcedimento,$_SESSION[CdUsuario],'$Status', '$Valor', '$valorsus', '$cdsus', '$desc_sus','$cdespecialidade', '$ppi','$bpa','$cdgrupoproc', '$nmpreparo','$cid','$cdservico','$cdclass','$valorm','$maxano','$principal','$quemAgendar')";	
		
		$qry = mysqli_query($db,$sql) 
				or die (TrataErro(mysqli_errno(),'Especificação','../index.php?i=4','regn_especproc:insert especificacao'));
		
			//echo '<script language="JavaScript" type="text/javascript"> 
			//		var agree=confirm("Cadastro realizado com sucesso! \nGostaria de cadastrar outro?");
			//		if (!agree)
			//			window.location.href="../index.php?i=4";
			//		else	
			//			window.location.href="../index.php?i=4&s=n";		
			//	  </script>';

			//echo "Cadastro realizado com sucesso!";
		echo json_encode(array('msg' => "Procedimento criado com sucesso!" , 'erro' => ""));
	}
	else
	{
		$CdEspecProc = (int)$CdEspecProc;
		if($acao == "edit")
		{
			if(isset($_POST['change'])){

			$dtinicial = $_POST['dataIni'] ?? null;
			
			if(!$dtinicial){
				echo json_encode(array('msg' => '' , 'erro' => 'Informar a data de início!'));
				die();
			}

			$dttermino = $_POST['dataFim'] ?? null;
			
			$datas = ($dttermino) ? " AND ac.DtAgCons BETWEEN '$dtinicial' AND '$dttermino' " : " AND ac.DtAgCons >= '$dtinicial' ";
			//$config =  getConfiguracao(17);
			$valormed = $Valor;

		//	if($config['estado'] == 'A'){
		//		$valor = $valor + (($valor * $config['valor'])/100);
		//	}
			$horaIni = $_POST['horaIni'];
			$horaFim = $_POST['horaFim'];
			
			if(isset($horaIni) AND isset($horaFim) AND $horaIni != '' AND $horaFim != ''){
				$hora = " AND ac.HoraAgCons BETWEEN '$horaIni' AND '$horaFim' ";
			}else if(isset($horaIni) AND !isset($horaFim) AND $horaIni != '' AND $horaFim == ''){
				$hora = " AND ac.HoraAgCons >= $horaIni ";
			}else if(!isset($horaIni) AND isset($horaFim) AND $horaIni == '' AND $horaFim != ''){
				$hora = " AND ac.HoraAgCons <= $horaFim ";
			}else{
				$hora = "";
			}


			 $sql = "UPDATE tbcontratoespec SET valor_mun = '$Valor', valor_ctr = '$valormed' WHERE CdEspecProc = '$CdEspecProc' ";
	
			$query = mysqli_query($db, $sql) or die (mysqli_error($db));

			$sql = "UPDATE tblctfornecedor_licitacaoespec SET valorf = '$valormed' WHERE cdespec = '$CdEspecProc'";
			$query = mysqli_query($db, $sql) or die (mysqli_error($db));

			$sql = "UPDATE tblctespeclicitacao SET valorlct = '$valormed' WHERE cdespec = '$CdEspecProc' ";
			$query = mysqli_query($db, $sql) or die (mysqli_error($db));

			 $sql = "UPDATE tbagendacons ac inner join tbsolcons sc on ac.CdSolCons = sc.CdSolCons
			SET ac.valor =  IF(ISNULL(sc.cdhrmed),IF( sc.Obs LIKE '%LANÇAMENTO PRODUÇÃO%', ac.valor, '$Valor'), IF(ac.valor = 0 , 0 , '$Valor')), 
			ac.valormed = IF(ISNULL(sc.cdhrmed),IF( sc.Obs LIKE '%LANÇAMENTO PRODUÇÃO%', ac.valormed, '$Valor'), IF(ac.valormed = 0 , 0 , '$Valor'))
			WHERE sc.CdEspecProc = $CdEspecProc $datas $hora ";
			$query = mysqli_query($db, $sql) or die (mysqli_error($db));

			$sql = "UPDATE tbfornespec SET valorf = '$Valor' , valorc = '$Valor' WHERE CdEspec = '$CdEspecProc'";
			$query = mysqli_query($db, $sql) or die (mysqli_error($db));
			
			$sql = "INSERT INTO tblogespec (`CdUsuario`,`Valor_Antigo`,`Valor_Novo`,`DtInc`,`HrInc`,`CdEspecProc`,`DtAgIni`,`DtAgFim`) VALUES ('$_SESSION[CdUsuario]','$valorOld','$Valor',CURDATE(),CURTIME(),'$CdEspecProc','$dtinicial','$dttermino')";
			$query = mysqli_query($db, $sql) or die (mysqli_error($db));
			$sqllogespec = mysqli_query($db,'SELECT MAX(CdLogEspec) FROM tblogespec');
			$logEspec = mysqli_fetch_array($sqllogespec);

			$idlogespec = $logEspec[0];

		}
			//alterar
			$sql = "UPDATE tbespecproc
						SET NmEspecProc    = '$NmEspecProc',	
							CdProcedimento = $CdProcedimento,
							UserAlt 	   = $_SESSION[CdUsuario],
							Status		   = '$Status',
							valor		   = '$Valor',
							cdsus		   = '$cdsus',
							valorsus		   = '$valorsus',
							desc_sus		   = '$desc_sus',
							ppi		   = '$ppi',
							bpa		   = '$bpa',
							cdgrupoproc		   = '$cdgrupoproc',
							cdespecialidade		   = '$cdespecialidade',
							nmpreparo		   = '$nmpreparo',
							cid 		= '$cid',
							maxano      = '$maxano',
							cdservico = '$cdservico',
							cdclass  = '$cdclass',
							valorm   =  '$valorm',
							principal = '$principal',
							quemAgendar='$quemAgendar',
							DtAlt  		   = NOW()
						WHERE CdEspecProc=$CdEspecProc";
			//echo $sql; die();
			$qry = mysqli_query($db,$sql) 
				or die (TrataErro(mysqli_errno(),'Especificação','../index.php?p=lista_especproc','regn_especproc:update especificacao'));
				
			//echo '<script language="JavaScript" type="text/javascript"> 
			//	alert("Dados alterados com sucesso!");
			//	window.location.href="../index.php?i=4&s=l";				
			// </script>';
			echo json_encode(array('msg' => "Procedimento editado com sucesso!" , 'erro' => "", 'CdLogEspec' =>$idlogespec));
		}
		else
		{
			
			
		}
	}
	@mysqli_close();
	@mysqli_free_result($qry);
}
}
