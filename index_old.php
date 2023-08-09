<?php 
	session_start();
	
	//INICIO - DADOS PARA O CHAT
		define('HOST', 'mysql11.sitcon.com.br');
		define('BD', 'sitcon10');
		define('USER','sitcon10');
		define('PASS', 'master');
	
		class BD{
			private static $conn;
			public function __construct(){}
			
			public function conn(){
				if(is_null(self::$conn)){
					self::$conn = new PDO('mysql:host='.HOST.';dbname='.BD.'', ''.USER.'', ''.PASS.'');
				}
				return self::$conn;
			}
		}
		BD::conn();
	//FIM - DADOS PARA O CHAT
	
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
    <!--script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script-->

<link rel="stylesheet" type="text/css" href="css/form.css">
<link rel="stylesheet" type="text/css" href="css/menus.css">
<link rel="stylesheet" type="text/css" href="css/geral.css">
<link rel="stylesheet" type="text/css" href="msg.css">
<link rel="stylesheet" type="text/css" href="chat/chat.css">

<!--link rel="stylesheet" type="text/css" href="autocomplete/css/autocomplete.css"-->

<!-- FORMATAÇÃO E AÇÕES DO BANNER -->
<style type="text/css">
/*
#movediv {
	width:300px;
	height:287px;
	/*background-color: #B5021D;
	padding:20px; * /
	margin-top:60px;
	margin-left:560px;
	cursor:pointer;
	alignment-adjust:central;
}
*/
</style>

<!--script type="text/javascript" src="autocomplete/js/autocomplete.js"></script>
<script type="text/javascript" src="autocomplete/js/jquery-1.3.2.min.js"></script-->

<script type="text/javascript" src="js/jquery.js"></script>           
<script type="text/javascript" src="js/jquery-ui.js"></script>        
<!--script type="text/javascript" src="js/jquery-mousewheel.js"></script>
<script type="text/javascript" src="js/jScrollbar.jquery.min.js"></script-->
<script type="text/javascript">

	$(document).ready(function() {
		//$(".jScrollbar3").hide();
		
		/*$(".jScrollbar3").jScrollbar({
		  allowMouseWheel : true,
		  scrollStep : 10,
		  showOnHover : true,
		  position : 'right'
		});*/
		
		// PARTE DO CHAT
		
		var janelas = new Array();
	
		function add_janelas(id, nome){
			var html_add = '<div class="janela" id="jan_'+id+'"><div class="topo" id="'+id+'"><span>'+nome+'</span><a href="javascript:void(0);" id="fechar">X</a></div><div id="corpo"><div class="mensagens"><ul id="msg_'+id+'" class="listar"></ul></div><input type="text" class="mensagem" id="'+id+'" maxlength="255" /></div></div>';
			$('#janelas').append(html_add);
		}
		
		function abrir_janelas(x){
			//alert('ABRIR JANELAS: ' + x);
			$('#contatos ul li a').each(function(){
				var link = $(this);
				var id = link.attr('id');
				
				if(id == x){
					link.click();
				}

			//var height = $('#msg_'+x).height();
			//alert(height);
			//$('#msg_'+x).animate({scrollTop: 100000}, 'slow');	
				
			});
		}
		var antes = -1;
		var depois = 0;
		function verificar(){
			//alert('VERIFICAR');
			beforeSend: antes = depois;
			$.post('chat/chat.php', {acao: 'verificar', ids: janelas}, function(x){
				
				if(x.nao_lidos != ''){
					var arr = x.nao_lidos;
					for(i in arr){
						abrir_janelas(arr[i]);
					}
				}
				
				if(janelas.length > 0){
					var mens = x.mensagens;
					if(mens != ''){
						for(i in mens){
							$('#jan_'+i+' ul.listar').html(mens[i]);
							$('#msg_'+i).scrollTop(100000)
						}
					}
				}
				depois += 1;
				
			}, 'jSON');
				
		}
		verificar();
		
		
		$('.janela').live('click', function(){
			var id = $(this).children('.topo').attr('id');
			$.post('chat/chat.php',{acao: 'mudar_status', user: id});
		});
		
		$('.comecar').live('click', function(){
			var id = $(this).attr('id');
			var nome = $(this).attr('nome');
			janelas.push(id);
			for(var i = 0; i < janelas.length; i++){
				if(janelas[i] == undefined){
					janelas.splice(i, 1);
					i--;
				}
			}
			
			add_janelas(id, nome);
			$(this).removeClass('comecar');
			return false;
		});
		
		$('a#fechar').live('click', function(){
			var id = $(this).parent().attr('id');
			var parent = $(this).parent().parent().hide();
			$('#contatos a#'+id+'').addClass('comecar');
			
			var n = janelas.length;
			for(i = 0; i < n; i++){
				if(janelas[i] != undefined){
					if(janelas[i] == id){
						delete janelas[i];
					}
				}
			}
		});
		
		$('body').delegate('.topo', 'click', function(){
			var pai = $(this).parent();
			var isto = $(this);
			
			if(pai.children('#corpo').is(':hidden')){
				isto.removeClass('fixar');
				pai.children('#corpo').toggle(100);
			}else{
				isto.addClass('fixar');
				pai.children('#corpo').toggle(100);
			}
		});
		
		setInterval(function(){
			if(antes != depois){
				verificar();
			}
		}, 2000);	
		
		$('body').delegate('.mensagem', 'keydown', function(e){
			var campo = $(this);
			var mensagem = campo.val();
			var to = $(this).attr('id');
			
			if(e.keyCode == 13){
				if(mensagem != ''){
				
					$.post('chat/chat.php',{
						acao: 'inserir',
						mensagem: mensagem,
						para: to
					}, function(retorno){
						$('#jan_'+to+' ul.listar').append(retorno);
						campo.val('');
						$('#msg_'+to).scrollTop(100000);
					});
					
					
				}
			}
		});
		
		$("#abre_chat").click(function() {
			var options = {};
      		//$("#contatos").toggle('blind', options, 500);
			$("#contatos").animate({width:'toggle'},350);
      		//return false;
    	});
		
		// FIM CHAT
		

		
		//$('#movediv').click(function() {			
		$('#movediv').animate({
		   //marginLeft: '500px',
		   marginTop: '400px'
		 }, 5000, function() {
		 //$(this).append('<div>Feliz Natal!</div>');
		});

	});								  

	function fechar() {
		document.getElementById("movediv").style.display = "none";
	}
</script>
<!-- FIM BANNER -->
<!--script type="text/javascript" src="chat.js"></script-->

</head>

<?php include "incjs.php";  // bibliotecas JS ?>
<body>
<!--div id="contatos" class="jScrollbar3" style="z-index:0; display:none;">
	<h1 style="position:relative; background:#F8FCFF; font-weight:normal; font-size:15px; border-top-right-radius: 10px; margin-bottom:10px; z-index:9" > Chat - Sitcon </h1>
	<div class="jScrollbar_mask" style="z-index:1;>
        <ul style="z-index:2">
        < ?php
            $selecionar_usuarios = BD::conn()->prepare("SELECT * FROM `tbusuario` WHERE CdUsuario != ?");
            $selecionar_usuarios->execute(array($_SESSION['CdUsuario']));
            if($selecionar_usuarios->rowCount() == 0){
                echo '<p>Desculpla, não há contatos ainda!</p>';
            }else{
            while($usuario = $selecionar_usuarios->fetchObject()){
        ?>
            <li><a href="javascript:void(0);" nome="< ?php echo $usuario->Login;?>" id="< ?php echo $usuario->CdUsuario;?>" class="comecar">< ?php echo $usuario->Login;?></a></li>
        < ?php }}?>
        </ul>    
	</div>
	
	<!--div class="jScrollbar_draggable">
		<a href="#" class="draggable"></a>
	</div>
		
	<div class="clr"></div-- >
</div>
<div style="top:0; right:0;" id="retorno"><div>
<div id="janelas"></div-->




<div id="contatos" class="contatos_teste" style="display:none">
<!--h1> Chat - SITCON </h1-->

	<ul>
	<?php
		$selecionar_usuarios = BD::conn()->prepare("SELECT * FROM `tbusuario` WHERE CdUsuario != ?");
		$selecionar_usuarios->execute(array($_SESSION['CdUsuario']));
		if($selecionar_usuarios->rowCount() == 0){
			echo '<p>Desculpla, não há contatos ainda!</p>';
		}else{
		while($usuario = $selecionar_usuarios->fetchObject()){
	?>
		<li><a href="javascript:void(0);" nome="<?php echo $usuario->Login;?>" id="<?php echo $usuario->CdUsuario;?>" class="comecar"><?php echo $usuario->Login;?></a></li>
	<?php }}?>
	</ul>    
</div>
<!--div style="position:absolute; top:0; right:0;" id="retorno"><div-->
<div id="janelas"></div>



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
                    <li style="color:#FFF; margin-bottom:7px; padding:">  <?php if (isset($_SESSION["CdUsuario"])) echo ''.$_SESSION["NmUsuario"];echo ''.$_SESSION["nmgrusuario"]?> - <a href="javascript:void(0)" id="abre_chat" style="color:#FFF;">CHAT</a>
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
