<?php
	session_start();
	//include_once "config.php";
	//require_once('BD.class.php');
	
		define('HOST', 'db-cisamso-dev.cp2misfrgznr.us-east-1.rds.amazonaws.com');
		define('BD', 'cismetro_prod');
		define('USER','admin');
		define('PASS', 'Pipo81192020');

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
	
	$acao = $_POST['acao'];
	
	switch($acao){
		case 'inserir':
			$para = $_POST['para'];
			$mensagem = strip_tags($_POST['mensagem']);
			
			$pegar_nome = BD::conn()->prepare("SELECT Login FROM `tbusuario` WHERE CdUsuario = ?");
			$pegar_nome->execute(array($_SESSION['CdUsuario']));
			$ft = $pegar_nome->fetchObject();
			
			$inserir = BD::conn()->prepare("INSERT INTO `mensagens` (id_de, id_para, data, mensagem) VALUES(?,?,NOW(),?)");
			if($inserir->execute(array($_SESSION['CdUsuario'], $para, $mensagem))){
				echo '<li><span>'.$ft->Login.' disse:</span><p>'.$mensagem.'</p></li>';
			}
			
		break;
		
		case 'verificar':
			$ids = $_POST['ids'];
			$retorno = array();
			
			if($ids == ''){
				$retorno['mensagens'] == '';
			}else{
				foreach($ids as $indice => $id){
					$selecionar = BD::conn()->prepare("SELECT * FROM `mensagens` WHERE id_de = ? AND id_para = ? OR id_de = ? AND id_para = ?");
					$selecionar->execute(array($_SESSION['CdUsuario'], $id, $id, $_SESSION['CdUsuario']));
					
					$mensagem = '';
					while($ft = $selecionar->fetchObject()){
						$nome = BD::conn()->prepare("SELECT Login FROM `tbusuario` WHERE CdUsuario = ?");
						$nome->execute(array($ft->id_de));
						$name = $nome->fetchObject();
						/*20-02-2015*/
						if($name->Login == $_SESSION['Login']){
							$mensagem .= '<li><span style="color: #007ACB;">'.$name->Login.' disse: <br><i>('.$ft->data.')</i></span><p>'.$ft->mensagem.'</p></li>';
						}else{
							$mensagem .= '<li><span style="color: #E78A00;">'.$name->Login.' disse: <br><i>('.$ft->data.')</i></span><p>'.$ft->mensagem.'</p></li>';
						}
						/*20-02-2015*/
					}
					$retorno['mensagens'][$id] = $mensagem;
				}
			}
		
			$verificar = BD::conn()->prepare("SELECT id_de FROM `mensagens` WHERE id_para = ? AND lido = ? GROUP BY id_de");
			$verificar->execute(array($_SESSION['CdUsuario'], 0));
			
			if($verificar->rowCount() == 0){
				$retorno['nao_lidos'] == '';
			}else{
				while($user = $verificar->fetchObject()){
					$retorno['nao_lidos'][] = $user->id_de;
				}
			}
			$retorno = json_encode($retorno);
			echo $retorno;
		break;
		
		case 'mudar_status':
			$user = $_POST['user'];
			$mudar_st = BD::conn()->prepare("UPDATE `mensagens` SET lido = '1' WHERE id_de = ? AND id_para = ?");
			$mudar_st->execute(array($user, $_SESSION['CdUsuario']));
		break;
	}
?>