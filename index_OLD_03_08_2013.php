<?php 
	session_start();
	include "conecta.php";
	function escreve_caminho($i){
			include "conecta.php";
			$sql = "SELECT si.cdsubitem, si.nmsubitem, si.arquivo FROM tbsubitem AS si WHERE si.cdsubitem = $i";
			$sql = mysqli_query($db,$sql);
			$l = mysqli_fetch_array($sql);				
			echo "$l[cdsubitem] - $l[nmsubitem] - $l[arquivo]";	
	}	

  $useragent = $_SERVER['HTTP_USER_AGENT'];
 
  if (preg_match('|MSIE ([0-9].[0-9]{1,2})|',$useragent,$matched)) {
    $browser_version=$matched[1];
    $browser = 'IE';
  } elseif (preg_match( '|Opera/([0-9].[0-9]{1,2})|',$useragent,$matched)) {
    $browser_version=$matched[1];
    $browser = 'Opera';
  } elseif(preg_match('|Firefox/([0-9\.]+)|',$useragent,$matched)) {
    $browser_version=$matched[1];
    $browser = 'Firefox';
  } elseif(preg_match('|Chrome/([0-9\.]+)|',$useragent,$matched)) {
    $browser_version=$matched[1];
    $browser = 'Chrome';
  } elseif(preg_match('|Safari/([0-9\.]+)|',$useragent,$matched)) {
    $browser_version=$matched[1];
    $browser = 'Safari';
  } else {
    // browser not recognized!
    $browser_version = 0;
    $browser= 'other';
  }

	//	include "config/funcoes.php";
	//require_once('config/function_trata_erro.php');
	
if (($_SESSION["CdUsuario"] > 0) and ($browser=="Chrome" or $browser=="Safari")) {
	

	session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	
	<title>Iconsorcio Vs. 6.0</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <meta name="classification" content="" />
    <meta http-equiv="content-style-type" content="text/css" />
    <meta name="Description" content="" />
    <meta name="Keywords" content="Iconsórcio,Gestão de Consórcio de Saúde" />
    <meta name="language" content="pt-br" />
    <meta name="resource-type" content="document" />
	
	<link rel="shortcut icon" href="favicon.ico">
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>

<link rel="stylesheet" type="text/css" href="css/form.css">
<link rel="stylesheet" type="text/css" href="css/menus.css">
<link rel="stylesheet" type="text/css" href="css/geral.css">
<link rel="stylesheet" type="text/css" href="msg.css">

<script type="text/javascript" src="autocomplete/js/autocomplete.js"></script>
<script type="text/javascript" src="autocomplete/js/jquery-1.3.2.min.js"></script>
<link rel="stylesheet" type="text/css" href="autocomplete/css/autocomplete.css">

<!-- FORMATAÇÃO E AÇÕES DO BANNER -->
<style type="text/css">
#movediv {
	width:300px;
	height:287px;
	/*background-color: #B5021D;
	padding:20px; */
	margin-top:60px;
	margin-left:560px;
	cursor:pointer;
	alignment-adjust:central;
}
</style>
<script type="text/javascript">
$(document).ready(function() {
    //$('#movediv').click(function() {			
	$('#movediv').animate({
       //marginLeft: '500px',
	   marginTop: '400px'
	 }, 5000, function() {
     //$(this).append('<div>Feliz Natal!</div>');
	});
});								  
//});
function fechar() {
	document.getElementById("movediv").style.display = "none";
}
</script>
<!-- FIM BANNER -->

</head>

<?php include "incjs.php";  // bibliotecas JS ?>
<body>
<!-- INÍCIO BANNER
<?php if($_GET[p] == "inicial" && ((int)$_SESSION["cdgrusuario"] == 4 OR (int)$_SESSION["cdgrusuario"] == 1)) { //para ativar mude para $_GET[p] == "inicial"  ?>
<div id="movediv" style="position:absolute;"><a href="#" onclick="fechar();" title="Clique aqui para fechar">x</a><br />
<a href="http://www.sitconsistemas.com.br" target="_blank"><img src="banner.png" /></a></div>
<!-- FIM BANNER -->	
<!-- EFEITO DE NEVE
<script type="text/javascript"> endereco = "neve1.png";</script>
<script type="text/javascript" src="neve.js"></script> -->
<?php   } ?>
<!-- FIM EFEITO DE NEVE -->	
    <div id="topo" style="margin:0 auto 0PX; width:960PX; height:90px; ">
    <div id="topo2" style="background: url(img/bg_meio.png); height:83px;">
        
    <div id="busca_top" style="float:right; margin-top:30px; margin-right:96px; ;">  
                    <li style="color:#FFF; margin-bottom:7px; padding:"> Seja bem vindo(a), </li>
                    <li style="color:#FFF; margin-bottom:7px; padding:">  <?php if (isset($_SESSION["CdUsuario"])) echo ''.$_SESSION["NmUsuario"];echo ''.$_SESSION["nmgrusuario"]?>
<span style="color:#000; text-decoration:underline"> 
                   <!-- <a href='?i=13' title="Alterar senha"> Alterar senha</a> --> 
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
				  if($_SESSION["cdgrusuario"] == 1){
					  escreve_caminho($_GET[i]);				 
					  //print_r(scandir(session_save_path(),1));
				  }
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

<div id="rodape"  style="background:#0099FF; height:120px;" > <!-- style="background:#0099FF url(img/rodape.png) repeat-x; height:90px; clear:both" -->
	<div id="meio_rod" style="height:90px; width:982px; margin:0 auto 0px;" >
  				<!--  <a href="google.php?keepThis=true&TB_iframe=true&height=480&width=650" title="Mapa - Como Chegar" class="thickbox">-->  <img src="img/fund_rodape.png" border="0" usemap="#Map2" />
                <map name="Map2" id="Map2">
                  <area shape="rect" coords="17,53,126,97" href="http://www.sitcon.com.br" target="_blank" alt="www.sitcon.com.br" title="www.sitcon.com.br" />
                </map>
    </div>
</div>

</body>
</html>       
 		<?php
			}
			else 
			{  if(($browser=="Chrome") or ($browser=="Safari")) { include("frm_login.php"); } else {  include("msg.php"); } }
		?>
