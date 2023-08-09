<?php 
	session_start();

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
	//INICIO - DADOS PARA O CHAT
		define('HOST', 'mysql.iconsorciosaude10.com.br');
		define('BD', 'iconsorciosaud94');
		define('USER','iconsorciosaud94');
		define('PASS', 'bdnew18cisa');

		class BD{
			private static $conn;
			public function __construct(){}
			
			public function conn(){
				if(is_null(self::$conn)){
					try {
						self::$conn = new PDO('mysql:host='.HOST.';dbname='.BD.'', ''.USER.'', ''.PASS.'');
					} catch (PDOException $e) {
							print "N&atilde;o foi poss&iacute;vel conectar ao banco de dados!<br/>";
							die();	
					}	
				}
				return self::$conn;
			}
		}
		//BD::conn();
	//FIM - DADOS PARA O CHAT
	
	include "conecta.php";
	/*11-02-2015*/
	require "functions.php";
	/*11-02-2015*/
	function escreve_caminho($i){
			include "conecta.php";
			$sql = "SELECT si.cdsubitem, si.nmsubitem, si.arquivo FROM tbsubitem AS si WHERE si.cdsubitem = $i";
			$sql = mysqli_query($db,$sql);
			$l = mysqli_fetch_array($sql);				
			echo "$l[cdsubitem] - $l[nmsubitem] - $l[arquivo]";	
	}
	function municipioBlock($CdPref)
	{
		$result = mysqli_query($db,"SELECT bloquear FROM tbprefeitura WHERE CdPref = $CdPref ");
		while ($dados = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
			return $dados[bloquear];
		}
	}
	function listmenusBlock()
	{
		$result = mysqli_query($db,"SELECT cdsubitem FROM tbsubitembloqueio WHERE status = 1 ");
		while ($dados = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
			$array[] = $dados[cdsubitem];
		}
		return $array;
	}
	if (municipioBlock($_SESSION[CdOrigem])) {
		$menusBlock = listmenusBlock();
	}else{
		$menusBlock = array('0' => 0);
	}	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	
	<title>Iconsorcio Vs. 6.0</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <meta name="classification" content="" />
    <meta http-equiv="content-style-type" content="text/css" />
    <meta name="Description" content="" />
    <meta name="Keywords" content="Icons�rcio,Gest�o de Cons�rcio de Sa�de" />
    <meta name="language" content="pt-br" />
    <meta name="resource-type" content="document" />
	
    <!--script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script-->

    <link id="favicon" rel="icon" type="image/png" sizes="64x64" href="ico_sitcon.png">
	<link rel="stylesheet" type="text/css" href="css/form.css">
	<link rel="stylesheet" type="text/css" href="css/menus.css">
	<link rel="stylesheet" type="text/css" href="css/geral.css">
	<link rel="stylesheet" type="text/css" href="msg.css">
	<link rel="stylesheet" type="text/css" href="chat/chat.css">
	<link rel="stylesheet" type="text/css" href="css/novos.css">
	<link rel="stylesheet" href="js/mult/multiple-select.css" type="text/css"/>
	<script src="js/dist/sweetalert.min.js"></script> 
	<link rel="stylesheet" type="text/css" href="js/dist/sweetalert.css">
	<!--link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css"-->
	<link rel="stylesheet" type="text/css" href="css/fontawesome.min.css">
	<script src="https://use.fontawesome.com/1ae21d35a5.js"></script>
<!--link rel="stylesheet" type="text/css" href="autocomplete/css/autocomplete.css"-->

<!-- FORMATA��O E A��ES DO BANNER -->
<style type="text/css">
.chamado{
	width: 35px;
	height: 35px;
	background-image: url("img/ico_sitcon_c2.png");
	background-size: 35px;
}
.divchamado{
	float: right;
	padding-top: 40px;
	margin-right: 15px;
	padding-right: 5px;
	padding-left: 5px;
}
#movediv {
	width:300px;
	height:287px;
	/*background-color: #B5021D;
	padding:20px; */
	margin-top:10px;
	margin-left:560px;
	cursor:pointer;
	alignment-adjust:central;
}
</style>

<!--script type="text/javascript" src="autocomplete/js/autocomplete.js"></script>
<script type="text/javascript" src="autocomplete/js/jquery-1.3.2.min.js"></script-->

<script type="text/javascript" src="js/jquery.js"></script>           
<script type="text/javascript" src="js/jquery-ui.js"></script>
<script type="text/javascript" src="packs/tinymce/tinymce.min.js"></script>
<!--script type="text/javascript" src="js/jquery-mousewheel.js"></script>
<script type="text/javascript" src="js/jScrollbar.jquery.min.js"></script-->
<script type="text/javascript">

	$(document).ready(function() {
		// //$(".jScrollbar3").hide();
		
		// /*$(".jScrollbar3").jScrollbar({
		//   allowMouseWheel : true,
		//   scrollStep : 10,
		//   showOnHover : true,
		//   position : 'right'
		// });*/
		
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

		// 	//var height = $('#msg_'+x).height();
		// 	//alert(height);
		// 	//$('#msg_'+x).animate({scrollTop: 100000}, 'slow');	
				
		 	});
		 }
		 var antes = -1;
		 var depois = 0;
		 function verificar(){
		// 	//alert('VERIFICAR');
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
  //     		//$("#contatos").toggle('blind', options, 500);
		 	$("#contatos").animate({width:'toggle'},350);
  //     		//return false;
     	});
		
		// // FIM CHAT
		

		
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
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-47320653-13', 'iconsorciosaude1.com');
  ga('send', 'pageview');

</script>
</head>

<?php include "incjs.php";  // bibliotecas JS ?>
<body>
<?php
/*11-02-2015*/
$idUsuario = $_SESSION['CdUsuario'];
$senhaPadrao = mysqli_query($db,"SELECT Senha FROM tbusuario WHERE CdUsuario='$idUsuario'");
while($r = mysqli_fetch_array($senhaPadrao,MYSQLI_BOTH)){
	$senhaPadraoU = $r['Senha'];
}
echo'<input type="hidden" id="inputSenha" name="senhaSalva" value="'.$senhaPadraoU.'"/>';
//echo'<pre class="prentece">'.$senhaPadraoU.'</pre>';
if ($_SESSION[cdgrusuario] == 1) {
	
}else{
	require('session/cronometro.php');
}
/*11-02-2015*/
?>
<!--div id="contatos" class="jScrollbar3" style="z-index:0; display:none;">
	<h1 style="position:relative; background:#F8FCFF; font-weight:normal; font-size:15px; border-top-right-radius: 10px; margin-bottom:10px; z-index:9" > Chat - Sitcon </h1>
	<div class="jScrollbar_mask" style="z-index:1;>
        <ul style="z-index:2">
        < ?php
            $selecionar_usuarios = BD::conn()->prepare("SELECT * FROM `tbusuario` WHERE CdUsuario != ?");
            $selecionar_usuarios->execute(array($_SESSION['CdUsuario']));
            if($selecionar_usuarios->rowCount() == 0){
                echo '<p>Desculpla, n�o h� contatos ainda!</p>';
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
	<div class="titulo_contatos">CONTATOS</div>
	<ul>
	<?php
		//$selecionar_usuarios = BD::conn()->prepare("SELECT * FROM `tbusuario` WHERE CdUsuario != ? AND `Status` = ?");
		$selecionar_usuarios = BD::conn()->prepare("SELECT
													tbusuario.CdUsuario,
													tbusuario.CdTpUsuario,
													tbusuario.NmUsuario,
													tbusuario.Email,
													tbusuario.Login,
													tbusuario.Senha,
													tbusuario.DtInc,
													tbusuario.UserInc,
													tbusuario.CdOrigem,
													tbusuario.`Status`,
													tbusuario.cdfornecedor,
													tbusuario.cdgrusuario,
													tbuserconn.`data`,
													tbuserconn.tempo,
													tbuserconn.horalogin
													FROM
													tbusuario
													LEFT JOIN tbuserconn ON tbusuario.CdUsuario = tbuserconn.usuario 
													WHERE CdUsuario != ? AND `Status` = ?
													ORDER BY tbuserconn.tempo DESC");
		$selecionar_usuarios->execute(array($_SESSION['CdUsuario'],1));
		if($selecionar_usuarios->rowCount() == 0){
			echo '<p>Desculpe, n�o h� contatos ainda!</p>';
		}else{
				while($usuario = $selecionar_usuarios->fetchObject()){
					$nomeDoUser = strtoupper($usuario->NmUsuario);
					$loginDoUser = strtoupper($usuario->Login);
					if($usuario->tempo > time()){
						echo'<li><div class="status_chat_on"></div><a href="javascript:void(0);" nome="'.$usuario->Login.'" id="'.$usuario->CdUsuario.'" title="'.$nomeDoUser.'" class="comecar"> '.$loginDoUser.'</a></li>';
					}elseif($usuario->tempo < time() && $usuario->tempo !=''){
						echo'<li><div class="status_chat_als"></div><a href="javascript:void(0);" nome="'.$usuario->Login.'" id="'.$usuario->CdUsuario.'" title="'.$nomeDoUser.'" class="comecar"> '.$loginDoUser.'</a></li>';
					}else{
						echo'<li><div class="status_chat"></div><a href="javascript:void(0);" nome="'.$usuario->Login.'" id="'.$usuario->CdUsuario.'" title="'.$nomeDoUser.'" class="comecar"> '.$loginDoUser.'</a></li>';
					}
			}
		}
		
?>
	</ul>    
</div>
<!--div style="position:absolute; top:0; right:0;" id="retorno"><div-->
<div id="janelas"></div>

<?php // if($_GET[p] == "inicial") { //para ativar mude para $_GET[p] == "inicial" && ((int)$_SESSION["cdgrusuario"] == 4 OR (int)$_SESSION["cdgrusuario"] == 1) 
 if($_GET[p] == "inicial") {
//para ativar mude para $_GET[p] == "inicial" && ((int)$_SESSION["cdgrusuario"] == 4 OR (int)$_SESSION["cdgrusuario"] == 1) ?>
<!-- IN�CIO BANNER
<div id="movediv" style="position:absolute;z-index:4;"><a href="#" onclick="fechar();" title="Clique aqui para fechar">x</a><br />
<a href="http://www.sitconsistemas.com.br/" target="_blank"><img src="img/img.png" /></a></div>


<div id="movediv" style="position:absolute;z-index:4;"><a href="#" onclick="fechar();" title="Clique aqui para fechar">x</a><br />
<a href="http://www.sitconsistemas.com.br" target="_blank"><img src="papai_noel.png" /></a></div>
<!-- FIM BANNER 
<!-- EFEITO DE NEVE 
<script type="text/javascript"> endereco = "neve1.png";</script>
<script type="text/javascript" src="neve.js"></script> 
<?php   } ?>
<!-- FIM EFEITO DE NEVE -->	
    <div id="topo1" >
    <div id="topo2" >
				<?php 
				//*SUPORTE SITCON*//
					echo'<div class="divchamado">
						<a href="http://iconsorciosaude3.com.br/suporte" title="SUPORTE SITCON"target="_blank">
						<div class="chamado"></div></a>
						</div>';
						//print_r($_SESSION[Multigrupos]);
				//*SUPORTE SITCON*//
				?>        
        <!--11-02-2015-->
            <div id="busca_top" style="float:right; margin-top:15px; margin-right:28px; margin-bottom:15px;"> 
        <!--11-02-2015-->
                    <li style="color:#FFF; margin-bottom:7px; padding:"> Seja bem vindo(a), <?php echo $_SESSION["cdfornecedor"]?></li>
                    <li style="color:#FFF; margin-bottom:7px; padding:">  <?php if (isset($_SESSION["CdUsuario"])) echo ''.$_SESSION["NmUsuario"];echo ''.$_SESSION["nmgrusuario"]?> - <a href="javascript:void(0)" id="abre_chat"  title="Chat ">CHAT</a>
<span style="color:#000; text-decoration:underline"> 
                   <!-- <a href='?i=13' title="Alterar senha"> Alterar senha</a> --> 
                 </span>  </li>
                    <!--11-02-2015-->
                    <!--Contagem Regresiva-->
                    <span style="color:#FFF;">Sess�o </span><li id="sessao" style="color:#FFF; margin-bottom:7px; display:inline;"></li>
                    <!--11-02-2015-->
            </div>
        </div>
    </div>
	<?php 
	/*valida��o de dados*/
	if (isset($_GET['vl'])) {
		include "session/dadosContato.php";
	}else{
	?>
	<div id="menu" > 
        <div id="meio">	
        <?php 
        if (isset($_SESSION["CdUsuario"])){
				include "menu_gestor.php";
        }
        ?>             
      </div>
	</div>
	<div id="geral">  
	  <div id="cont">
          <?php 	
			/*11-02-2015*/
			//Controle de Session	
			if ($_SESSION[cdgrusuario] == 1) {
				
			}else{
				login_session_timeout(600);
			}
			/*11-02-2015*/
			$query_per = mysqli_query($db,"SELECT * FROM tbmultigrupo Where CdUsuario = $_SESSION[CdUsuario]"); 
            
            $multigrupo = "";
            if(mysqli_num_rows($query_per) > 0)
            	while ($n = mysqli_fetch_array($query_per))  
                	$multigrupo .= " OR tbitemus.cdgrusuario = '$n[cdgrusuario]' "; 
			/**
			 * Atualizado dia 24/08/2017
			 */
			if ((isset($_GET['i']))?($_GET['i'] > 0):FALSE) {
				$i = $_GET['i'];
				$queryMenu = "SELECT
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
									WHERE  tbsubitem.cdsubitem = '$i' AND  (tbitemus.cdgrusuario ='$_SESSION[cdgrusuario]'
									$multigrupo )
								   ";
				//echo $queryMenu;
				$sql = mysqli_query($db,$queryMenu);
				
				$lin = mysqli_fetch_array($sql);
				if(mysqli_num_rows($sql)>0)
				{
					if (in_array($i, $menusBlock)) {
						echo'<h1 id="alert">P�gina bloqueada entre em contato com o cons�rcio!</h1>';
					}else{
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
				}else{
					echo'<h1 id="alert">P�gina n�o encontrada!</h1>';
				}
			}else{
				include "pginicial.php";
			}
			/**
			 * Atualizado dia 24/08/2017
			 */
			?>
		</div>
	</div><!--geral-->
		<br clear="all" />

<div id="footer">
	<div id="footer_shadow"></div><!--footer_shadow-->
	<div id="footer_body">
		<div id="footer_imagem">
			<a href="http://www.sitcon.com.br" target="_blank">	
				<img src="img/sitcon_logo.png" /> 
			</a> 
		</div>
		<div id="footer_texto">Av. Zita Soares, 212, 6� andar sala 602 - Centro, 35160-007, Ipatinga - MG<br/> Telefone:(31) 3822-4656 - atendimento@sitcon.com.br</div>
	</div><!--footer_body-->
</div><!--footer-->
	<!--<script src="http://iconsorciosaude5.com.br/natal/CodigoNeve.js"></script>-->
</body>
</html>       
 		<?php
	/*valida��o*/
	}
}else{  
	if(($browser=="Chrome") or ($browser=="Safari")) { 
		include("frm_login.php"); 
	} else {  
		include("msg.php"); 
	} 
}
			//echo '<pre>' . print_r($_SESSION, TRUE) . '</pre>';
			//echo "<br />";
			//print_r(scandir(session_save_path(),1));			
		?>
