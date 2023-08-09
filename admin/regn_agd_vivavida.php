<?php

define("DIRECT_ACCESS", true);

//echo $_POST['protocolo'];
//verifica se o usuario esta logado no sistema
require_once("verifica.php");
require("../funcoes.php");


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

$pkCount = (is_array($conf) ? count($conf) : 0);
	
// if(count($conf)>0){
if($pkCount>0){
	require("../conecta.php");
	$usr = (int)$_SESSION["CdUsuario"];
	$dt = date("Y-m-d")." ".date("H:i:s");
	for($i=0;$i<count($conf);$i++){		
		$sql = mysqli_query($db,"UPDATE tbagvivavida SET estado = 'R' WHERE cdagvivavida = '$conf[$i]'") or die (mysqli_error());
		### CONTROLE ###
	    $sqlag = mysqli_query($db,"INSERT INTO tbauditoria_viva (descr,dtalt,usralt,cdag) VALUES ('Confirmação','".date("Y-m-d H:i:s")."','$_SESSION[CdUsuario]','$conf[$i]')") or die("Erro ao tentar incluir LOG!");
	    voltarTriagemCeae($conf[$i]);
	}
	if($sql){

		@mysqli_close();
		@mysqli_free_result($sql);		
		echo '<script language="JavaScript" type="text/javascript"> 
							alert("Agenda(s) confirmada(s) com sucesso!");
							window.location.href="../index.php?i='.$i2.'&op='.$op.$busca.$cbopor.'&pag='.$pag.'";				
						  </script>';
	}	
}

$cancCount = (is_array($canc) ? count($canc) : 0);
if($cancCount>0){
	require("../conecta.php");
	$usr = (int)$_SESSION["CdUsuario"];
	$dt = date("Y-m-d")." ".date("H:i:s");
	for($i=0;$i<count($canc);$i++){		
		$sql = mysqli_query($db,"UPDATE tbagvivavida SET estado = 'C' WHERE cdagvivavida = '$canc[$i]'") or die (mysqli_error());
		### CONTROLE ###
	    $sqlag = mysqli_query($db,"INSERT INTO tbauditoria_viva (descr,dtalt,usralt,cdag) VALUES ('Cancelamento','".date("Y-m-d H:i:s")."','$_SESSION[CdUsuario]','$canc[$i]')") or die("Erro ao tentar incluir LOG!");
	    voltarTriagemCeae($canc[$i]);	
	}
	if($sql){
		@mysqli_close();
		@mysqli_free_result($sql);		
		echo '<script language="JavaScript" type="text/javascript"> 
							alert("Agenda(s) cancelada(s) com sucesso!");
							window.location.href="../index.php?i='.$i2.'&op='.$op.$busca.$cbopor.'&pag='.$pag.'";				
						  </script>';
	}	
}

$faltaCount = (is_array($falta) ? count($falta) : 0);
// if(count($falta)>0){
if($faltaCount>0){
	require("../conecta.php");
	$usr = (int)$_SESSION["CdUsuario"];
	$dt = date("Y-m-d")." ".date("H:i:s");
	for($i=0;$i<count($falta);$i++){		
		$sql = mysqli_query($db,"UPDATE tbagvivavida SET estado = 'F' WHERE cdagvivavida = '$falta[$i]'") or die (mysqli_error());
		### CONTROLE ###
	    $sqlag = mysqli_query($db,"INSERT INTO tbauditoria_viva (descr,dtalt,usralt,cdag) VALUES ('Falta','".date("Y-m-d H:i:s")."','$_SESSION[CdUsuario]','$falta[$i]')") or die("Erro ao tentar incluir LOG!");
	    voltarTriagemCeae($falta[$i]);
						
	}
	if($sql){
		@mysqli_close();
		@mysqli_free_result($sql);		
		echo '<script language="JavaScript" type="text/javascript"> 
							alert("Falta(s) aplicadas com sucesso!");
							window.location.href="../index.php?i='.$i2.'&op='.$op.$busca.$cbopor.'&pag='.$pag.'";				
						  </script>';
	}	
}

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