<?php
	session_start();
	require('../conecta.php');
	$cdUsuario 	= $_SESSION['CdUsuario'];
	$cdBanner	= $_POST['cdBanner'];
	$dataAlc 	= date('Y-m-d');
	$contato	= $_POST['contato'];
	$cdCliente 	= (int)constant("CLIENTE");

	if (isset($cdUsuario)) {
		if (isset($_POST['cdBanner'])) {
			$insert_query = "INSERT INTO `tbbannersalc` (`cdUsuario`,`cdBanner`,`dataAlc`,`contato`) VALUES ('$cdUsuario','$cdBanner','$dataAlc','$contato')";
			$insert_banner = mysqli_query($db,$insert_query);
			$dados = array('banner' => 1);
			if ($contato==1) {
				$dbBanner = mysqli_connect('mysql.iconsorciosaude3.com.br', 'iconsorciosaud13', 'QF84xMlgP4') or die ("Nao foi possivel conectar ao banco de dados");
				mysqli_select_db('iconsorciosaud13',$dbBanner) or die (mysqli_error());
				$insert_banner = mysqli_query($db,"INSERT INTO `tbbannersalc` (`cdUsuario`,`cdCliente`,`cdBanner`,`dataAlc`) VALUES ('$cdUsuario','$cdCliente','$cdBanner','$dataAlc')");
				mysqli_close($dbBanner);
			}
		}else{
			$dados = array('banner' => 0);
		}
	}else{
		$dados = array('banner' => 0);
	}

	header('Content-type: application/json');
	print json_encode($dados);


/*Base de teste
$dbBanner = mysqli_connect('mysql.iconsorciosaude5.com.br', 'iconsorciosaud55', 'bdpadrao2015') or die ("Nao foi possivel conectar ao banco de dados");
mysqli_select_db('iconsorciosaud55',$dbBanner) or die (mysqli_error());
*/