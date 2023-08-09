<?php 
	session_start();
	include "conecta.php";
	
	//	include "config/funcoes.php";
	//require_once('config/function_trata_erro.php');
	
if ($_SESSION["CdUsuario"] > 0){
?>
<?php
	session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	
	<title>Iconsorcio | Gest&atilde;o em Cons&oacute;rcio de Sa&uacute;de VS 4.0</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <meta name="classification" content="" />
    <meta http-equiv="content-style-type" content="text/css" />
    <meta name="Description" content="" />
    <meta name="Keywords" content="Iconsórcio,Gestão de Consórcio de Saúde" />
    <meta name="language" content="pt-br" />
    <meta name="resource-type" content="document" />
	
<link rel="stylesheet" type="text/css" href="css/form.css">
<link rel="stylesheet" type="text/css" href="css/menus.css">
<link rel="stylesheet" type="text/css" href="css/geral.css">



<?php include "incjs.php";  // bibliotecas JS ?>
<body>	
    <div id="topo" style="margin:0 auto 0PX; width:960PX; height:90px; ">
  <div id="topo2" style="background: url(img/bg_meio.png); height:83px;">
        
<div id="busca_top" style="float:right; margin-top:30px; margin-right:96px; ;">  
       			<li style="color:#FFF; margin-bottom:7px; padding:"> Seja bem vindo, </li>
       			<li style="color:#FFF; margin-bottom:7px; padding:">  <?php if (isset($_SESSION["CdUsuario"])) echo ''.$_SESSION["Login"]; ?>, <span style="color:#000; text-decoration:underline"> 
                <a href='?i=13' title="Alterar senha"> Alterar senha</a>
             </span>  </li>
   			 
        </div>
    </div>

</div>
	<div id="menu" > 
        <div id="meio">	
        <?php 
        if (isset($_SESSION["CdUsuario"])){
				include "menu_gestor.php";
        }
        ?>             
        <map name="MapMap" id="MapMap">
          <area shape="rect" coords="106,0,134,28" href="?i=frm_trsenha" target="_self" title="Troca de senha" />
        </map>
      </div>
	</div>
	<div id="geral">  
	  <div id="cont">
		  <div>    
			
			
		 <!--   <img src="img/barra.png" border="0" usemap="#Map" />-->
			<map name="Map" id="Map">
			  <area shape="rect" coords="216,2,241,29" href="?i=frm_caixa" target="_self" title="Fluxo de Caixa" />
			  <area shape="rect" coords="188,3,213,30" href="?i=frm_vacinacao" target="_self" title="Vacinação" />
			  <area shape="rect" coords="-1,5,24,26" href="?i=usuario" target="_self" title="Usuário" />
			  <area shape="rect" coords="945,2,970,29" href="?i=ajuda" target="_self" alt="Ajuda" title="Ajuda" />
			  <area shape="rect" coords="105,5,131,28" href="?i=estoque" target="_self" title="Estoque" />
			  <area shape="rect" coords="161,2,186,29" href="?i=frm_trsenha" target="_self" title="Troca de senha" />
			  <area shape="rect" coords="132,5,158,28" href="?i=rel" target="_self" title="Relátorios" />
			  <area shape="rect" coords="78,5,103,26" href="?i=calendario" target="_self" title="Calendário" />
			  <area shape="rect" coords="51,5,76,26" href="?i=pessoa&amp;tp=Funcion&aacute;rio" target="_self" 
			  title="Funcion&aacute;rio" />
			  <area shape="rect" coords="25,5,50,26" href="?i=pessoa&amp;tp=Paciente" target="_self" title="Paciente" />
			  </map>
	   </div>
		<?php	
		/*	if ($_SESSION["CdTpUsuario"] == 3 || $_SESSION["CdTpUsuario"] == 4)
			{
			
			
			
			$sql = "SELECT p.CdPref,NmCidade,SUM(Credito)-SUM(Debito) as Saldo 
					FROM tbprefeitura p LEFT JOIN tbmovimentacao m ON p.CdPref=m.CdPref 
					WHERE p.CdPref = $_SESSION[CdOrigem]
					GROUP BY p.CdPref,NmCidade";
			
			require("conecta.php");
			
			
			
			$qry = mysqli_query($db,$sql) or die ('Erro ao verificar contrato com fornecedores '.mysqli_error());
			if (mysqli_num_rows($qry) > 0){
				$alert = '';
				if(mysqli_result($qry,0,'Saldo') <= 0){
					$cor = "color:#F00; font-size:20px;";
					//$alert = '<p><img src="./imagens/Warning.png" /></p>';
					$saldo = mysqli_result($qry,0,'Saldo');
				}else
				{
					$cor = "color:#090; font-size:20px;";
					$saldo = mysqli_result($qry,0,'Saldo');
				}
			// IMPORTATEEEE SALDO DO MUNICIPIO	echo "<li> <span style='$cor'> Saldo R$:" .number_format($saldo,2,',','.'). "</span> </li>";
				/*
				echo $alert;	
				
				echo "<h2>Saldo atual: R$  </h2>";
				
				echo "<h2>".$cor.."</<h2>"; 
			}
		} */
		
		
		
		?>
		
        
        
        <?php 
			/* $sql = mysqli_query($db," SELECT * from tbusuario WHERE CdTpUsuario=5");
			
			while($l = mysqli_fetch_array($sql))
			{
				
				echo $l[CdUsuario]."<br />";	
				
				mysqli_query($db,"INSERT INTO `tbitemus` (`cditem`, `cdsubitem`, `cdpessoa`) VALUES ('3', '26', '$l[CdUsuario]')") or die (mysqli_error());
				mysqli_query($db,"INSERT INTO `tbitemus` (`cditem`, `cdsubitem`, `cdpessoa`) VALUES ('3', '40', '$l[CdUsuario]')")or die (mysqli_error());
				
			}
		 */
		?> 
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
          <?php 	
		  
		  
		  
		  
			$i = $_GET['i'];
			$sql = mysqli_query($db," 
							   SELECT
								tbsubitem.nmsubitem,
								tbitemus.cditem,
								tbitemus.cdsubitem,
								tbitem.nmitem,
								tbitemus.cdgrusuario,
								tbsubitem.arquivo
								FROM
								tbitem
								INNER JOIN tbitemus ON tbitem.cditem = tbitemus.cditem
								INNER JOIN tbsubitem ON tbsubitem.cdsubitem = tbitemus.cdsubitem
								WHERE  tbsubitem.cdsubitem = '$i' AND  tbitemus.cdgrusuario ='$_SESSION[cdgrusuario]'
							   ");
			
			$lin = mysqli_fetch_array($sql);
			if(mysqli_num_rows($sql)>0)
			{
				switch($i)
				{
				case "$i":
				$cdsubitem = $i;
				$nmsubitem = $lin[nmsubitem];
				include "$lin[arquivo]";
				break;
				
				}	
			}
			if($i=="")
			{
				include "pginicial.php";
				
			}
			/*el$se 
			{
				echo "Acesso Negado";
			}*/
			?>
		    
            <map name="MapMap2" id="MapMap2">
              <area shape="rect" coords="161,2,186,29" href="?i=frm_trsenha" target="_self" alt="Troca de Senha" title="Troca de senha" />
            </map>
		</div>
	</div>
		<br clear="all" />

<div id="rodape" style="background:#0099FF url(img/rodape.png) repeat-x; height:90px; clear:both">
	<div id="meio_rod" style="height:90px; width:982px; margin:0 auto 0px;" >
  				<!--  <a href="google.php?keepThis=true&TB_iframe=true&height=480&width=650" title="Mapa - Como Chegar" class="thickbox">-->  <a href="http://www.sitcon.com.br" target="_blank">	<img src="img/fund_rodape.png"  /> </a> 
    </div>
</div>

</body>
</html>       
 		<?php
			}
			else 
			{ include("frm_login.php"); }
		?>
