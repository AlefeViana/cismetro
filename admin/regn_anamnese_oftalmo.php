<?php
    define("DIRECT_ACCESS", true);

//verifica se logado
	require_once("verifica.php");

//funcao para tratar erro
	require("function_trata_erro.php");

	//verifica se o usuario tem permissão para acessar a pagina
	if ((int)$_SESSION["CdTpUsuario"] != 7)	
	{
		echo '<script language="JavaScript" type="text/javascript"> 
			window.location.href="index.php?p=inicial";				
		  </script>';	
	}	
//recebe as variaveis do formulario
	$CdPaciente			= $_POST["cdpaciente"];
	$BaixaVisual  		= $_POST["baixavisual"];
	$AcuidadeVisualOD  	= $_POST["acuidadevisualod"];
	$AcuidadeVisualOE	= $_POST["acuidadevisualoe"];
	$TempoEvolucao		= $_POST["tempoevolucao"];
	$Comentarios		= $_POST["comentarios"];
	$aDoenca 			= $_POST["doenca"];
	$aMedicacaoUso 		= $_POST["medicacaouso"];
	$aTratamento 		= $_POST["tratamento"];
	$ComentariosT 		= $_POST["comentariost"];
	
//valida dados
	
	if (false){
		echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
						 <script language="JavaScript" type="text/javascript"> 
							alert("Todos os campos são obrigatórios!");
							window.history.go(-1);				
			 			 </script>';
	}
	else{
		
		$vDoenca = false;
		if ($aDoenca != ''){
			$aDoenca 		= explode(',',$aDoenca);
			$vDoenca = true;
		}
		$vMedicacaoUso = false;
		if ($aMedicacaoUso != ''){	
			$aMedicacaoUso	= explode(',',$aMedicacaoUso);
			$vMedicacaoUso = true;
		}
		$vTratamento = false;
		if ($aTratamento != ''){
			$aTratamento	= explode(',',$aTratamento);
			$vTratamento = true;
		}
		
		require("../conecta.php");
		
		$DtInc = date('Y-m-d H:i:s');
		
		$sql1 = "INSERT INTO tbanamnese (CdPaciente,DtInc,UserInc,TpAnamnese) 
					VALUES ($CdPaciente,'$DtInc',$_SESSION[CdUsuario],'tbanamneseoftalmo')";
					
		$sql2 = "INSERT INTO tbanamneseoftalmo
					(CdPaciente,DtInc,BaixaVisual,AcuidadeVisualOD,AcuidadeVisualOE,TempoEvolucao,Comentarios,ComentariosT) 
				VALUES($CdPaciente,'$DtInc','$BaixaVisual','$AcuidadeVisualOD','$AcuidadeVisualOE','$TempoEvolucao','$Comentarios','$ComentariosT')";
		
		if ($vDoenca){
			$sql3 = "INSERT INTO tbanaoftaldoenca (CdPaciente,DtInc,CdDoenca,Tempo) VALUES";
			foreach ($aDoenca as $item){
				$item = explode('-',$item);
				$sql3 .= "($CdPaciente,'$DtInc',$item[0],$item[1]),";
			}
			$sql3 = substr($sql3,0,strlen($sql3)-1);
		}
		
		if ($vMedicacaoUso){
			$sql4 = "INSERT INTO tbanaoftalmed (CdPaciente,DtInc,CdMedicamento) VALUES";
			foreach ($aMedicacaoUso as $item){			
				$sql4 .= "($CdPaciente,'$DtInc',$item),";
			}
			$sql4 = substr($sql4,0,strlen($sql4)-1);
		}
		
		if ($vTratamento){
			$sql5 = "INSERT INTO tbanaoftaltrat (CdPaciente,DtInc,CdTratamento,Olho) VALUES";
			foreach ($aTratamento as $item){
				$item = explode('-',$item);
				$sql5 .= "($CdPaciente,'$DtInc',$item[0],'$item[1]'),";
			}
			$sql5 = substr($sql5,0,strlen($sql5)-1);		
		}
		//echo $sql1.'<br /><br />'.$sql2.'<br /><br />'.$sql3.'<br /><br />'.$sql4.'<br /><br />'.$sql5.'<br /><br />';
		
		
		$qry = mysqli_query($db,$sql1) 
				or die(TrataErro(mysqli_errno(),'','../index.php?p=lista_pac','regn_anamnese_oftalmo:insert anamnese'));
		
		$qry = mysqli_query($db,$sql2) 
				or die(TrataErro(mysqli_errno(),'','../index.php?p=lista_pac','regn_anamnese_oftalmo:insert anamnese oftalmo'));
				
		if ($vDoenca)
			$qry = mysqli_query($db,$sql3) 
					or die(TrataErro(mysqli_errno(),'','../index.php?p=lista_pac','regn_anamnese_oftalmo:insert oftalmodoenca'));
					
		if ($vMedicacaoUso)		
			$qry = mysqli_query($db,$sql4) 
					or die(TrataErro(mysqli_errno(),'','../index.php?p=lista_pac','regn_anamnese_oftalmo:insert oftalmomed'));	
		
		if ($vTratamento)
			$qry = mysqli_query($db,$sql5) 
					or die(TrataErro(mysqli_errno(),'','../index.php?p=lista_pac','regn_anamnese_oftalmo:insert oftalmotrat'));
			
		echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
							 <script language="JavaScript" type="text/javascript"> 
									alert("Dados incluidos com sucesso!");							
									window.location.href="../index.php?p=lista_pac";								
							 </script>';
		
		@mysqli_close();
		@mysqli_free_result($qry);
	}
?>