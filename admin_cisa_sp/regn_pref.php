<?php
//verifica se o usuario esta logado no sistema
require_once("verifica.php");
//verifica se o usuario tem permissão para acessar a pagina
if ((int)$_SESSION["CdTpUsuario"] != 1)	
{
	echo '<script language="JavaScript" type="text/javascript"> 
		window.location.href="index.php?p=inicial";				
	  </script>';	
}

//funcao para tratar erro
require_once("function_trata_erro.php");

//recebe as variaveis do formulario
$CdPref            = $_POST["cd_pref"];
$NmCidade	       = $_POST["nm_cidade"];
$Email		       = $_POST["email"];
$LimiteMax	       = $_POST["limite"];
$CdEstado		   = $_POST["cd_estado"];
$consorciado	   = $_POST["consorciado"];
$cdibge			   = $_POST["cdibge"]; #Código da tabela do Bpa Magnético

if(empty($cdibge)) $cdibge = 0;
//recebe o tipo de acao
$acao       = $_POST["acao"];

//verifica campos obrigatórios
$tudook = 1;
if ($NmCidade == ""){
	$msg_erro .= 'Preencha o campo Cidade<br />';
	$tudook = 0; 
}
if ($LimiteMax == ""){
	$LimiteMax = 0; 
}


//Controla se os campos obrigatorios estao preenchidos
if ($tudook == 0){
	echo "Favor verificar o seguinte campo!<br /><br />".$msg_erro;
	echo '<br /><br /><a href="#" onclick="javascript: history.go(-1);">Voltar</a>';		
}
else
{
	
	$LimiteMax = str_replace(',','.',str_replace('.','',$LimiteMax));
	
	if (substr($CdPref,0,4) == "Auto")
	{
		require("../conecta.php");
		//gera um novo codigo
		$qry = mysqli_query($db,"SELECT MAX(CdPref) FROM tbprefeitura") 
			or die(TrataErro(mysqli_errno(),'Prefeitura','../index.php?i=21','regn_pref:gerar novo codigo'));
			
		$CdPref = mysqli_result($qry,0) + 1;
		$sql = "INSERT INTO tbprefeitura (CdPref,NmCidade,CdEstado,Email,LimiteMax,consorciado,UserInc,cdibge)
					VALUES($CdPref,'$NmCidade',$CdEstado,'$Email','$LimiteMax','$consorciado',$_SESSION[CdUsuario],$cdibge)";
						
		$qry = mysqli_query($db,$sql) 
				or die (TrataErro(mysqli_errno(),'Prefeitura','../index.php?p=frm_cadpref','regn_pref:insert prefeitura'));
		
			echo '<script language="JavaScript" type="text/javascript"> 
					
					var agree=confirm("Cadastro realizado com sucesso! \nGostaria de cadastrar outra?");
					if (!agree)
						window.location.href="../index.php?i=21";
					else	
						window.location.href="../index.php?i=21";
					
					
				  </script>';
					
			//echo "Cadastro realizado com sucesso!";
		
	}
	else
	{
		$CdPref = (int)$CdPref;
		if($acao == "edit")
		{
			//alterar
			$sql = "UPDATE tbprefeitura
						SET NmCidade	   = '$NmCidade',	
							CdEstado	   = $CdEstado,
							Email		   = '$Email',
							LimiteMax 	   = $LimiteMax,
							consorciado    = '$consorciado',
							UserAlt 	   = $_SESSION[CdUsuario],							
							DtAlt  		   = NOW(),
							cdibge		   = $cdibge
						WHERE CdPref=$CdPref";
			
			require("../conecta.php");
			$qry = mysqli_query($db,$sql) 
				or die (TrataErro(mysqli_errno(),'Prefeitura','../index.php?p=lista_pref','regn_pref:update prefeitura'));
				
			echo '<script language="JavaScript" type="text/javascript"> 
				alert("Dados alterados com sucesso!");
				window.location.href="../index.php?i=21&id='.$CdPref.'";				
			  </script>';
		}
	}
	@mysqli_close();
	@mysqli_free_result($qry);
}
?>