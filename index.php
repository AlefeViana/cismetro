<?php
//caio - 25/05/2020 - integraÃ§Ã£o bootstrap 4.5 - refatoraÃ§Ã£o front-end

// ini_set('display_errors', 'On');

if (session_status() == PHP_SESSION_NONE) {
	// session_start();
}

require "../vendor/autoload.php";

include "conecta.php";

define('DIRECT_ACCESS', true);

$msg = new \Plasticbrain\FlashMessages\FlashMessages();



use Stringy\Stringy as S;


require "functions.php";

require "funcoes.php";

$user_id = (int) $_SESSION["CdUsuario"];
$autenticacao_config = getConfiguracao(41);

if ($autenticacao_config['estado'] == 'A') {
	if (!$user_id || !isset($_COOKIE['auth_two_factor_token'])) {
		$msg->info("Faça o login para acessar o menu", "frm_login.php");
	}
}else{
	if (!$user_id) {
		$msg->info("Faça o login para acessar o menu", "frm_login.php");
	}
}


//sessionTimeout($db, $user_id);

$group = mysqli_fetch_array(mysqli_query($GLOBALS['db'], "SELECT cdgrusuario as id, nmgrusuario as name FROM tbgrusuario WHERE cdgrusuario = " . $_SESSION['cdgrusuario']));

$_SESSION['userData'] = [
	"id" => $_SESSION["CdUsuario"],
	"name" => capitalize($_SESSION["NmUsuario"]),
	"county_id" => $_SESSION['CdOrigem'],
	"group_id" => $group['id'],
	"group" => $group['name'],
	"email" => $_SESSION["Email"],
	"supplier_id" => $_SESSION["cdfornecedor"],
	"professional_id" => $_SESSION["cdprofissional"]
];

$userType = $_SESSION["CdTpUsuario"] ?? null;

//Tipos de usuÃ¡rio
$isDoctor = $_SESSION["CdTpUsuario"] === 5; // mÃ©dico
$isCounty = $_SESSION["CdTpUsuario"] === 3; //municÃ­pio
$isSyndicate = $_SESSION["CdTpUsuario"] === 1; //consorcio
// var_dump($_SESSION['CdOrigem']);
// var_dump(municipioBlock($_SESSION['CdOrigem']));
if (municipioBlock($_SESSION['CdOrigem']) == 1) {
	$menusBlock = listmenusBlock();
	$bloqueio_menus = listmenusBlock();
}
// var_dump($bloqueio_menus);
$username = capitalize($_SESSION["NmUsuario"]);
include "verifica.php";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>

	<title>Iconsorcio Vs. 7.4</title>
	<meta charset="utf-8">
	<meta name="classification" content="" />

	<meta name="resource-type" content="document" />

	<link rel="stylesheet" href="Gestor/css/main.css" />

	<link id="favicon" rel="icon" type="image/png" sizes="64x64" href="ico_sitcon.png">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	

	<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
	<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/jszip-2.5.0/dt-1.10.21/af-2.3.5/b-1.6.2/b-colvis-1.6.2/b-flash-1.6.2/b-html5-1.6.2/b-print-1.6.2/cr-1.5.2/fc-3.3.1/fh-3.1.7/kt-2.5.2/r-2.2.5/rg-1.1.2/rr-1.2.7/sc-2.0.2/sp-1.1.1/sl-1.3.1/datatables.min.css" />
	<link href="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/css/bootstrap4-toggle.min.css" rel="stylesheet">
	<link href="https://cdn.jsdelivr.net/npm/animate.css@3.5.1" rel="stylesheet" type="text/css">
	<script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
	<link href="../fonteawesome/css/all.css" rel="stylesheet">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha512-iBBXm8fW90+nuLcSKlbmrPcLa0OT92xO1BIsZ+ywDWZCvqsWgccV3gFoRBv0z+8dLJgyAHIhR35VZc2oM/gI1w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
	<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.maskedinput/1.4.1/jquery.maskedinput.min.js" integrity="sha256-+4KHeBj6I8jAKAU8xXRMXXlH+sqCvVCoK5GAFkmb+2I=" crossorigin="anonymous"></script>
	<script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.js"></script>
	<script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.0/additional-methods.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-maskmoney/3.0.2/jquery.maskMoney.min.js" integrity="sha256-U0YLVHo5+B3q9VEC4BJqRngDIRFCjrhAIZooLdqVOcs=" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.26.0/moment-with-locales.min.js" integrity="sha256-4HOrwHz9ACPZBxAav7mYYlbeMiAL0h6+lZ36cLNpR+E=" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.26.0/moment.min.js" integrity="sha256-5oApc/wMda1ntIEK4qoWJ4YItnV4fBHMwywunj8gPqc=" crossorigin="anonymous"></script>
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js" integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"></script>
	<script src="https://raw.githubusercontent.com/jquery/jquery-ui/master/ui/i18n/datepicker-pt-BR.js"></script>
	<script src="https://cdn.tiny.cloud/1/72cmvdez4j4d3q81lq2hmqu4s81jupluwz0ciaakte1ylse7/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
	<script src="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/js/bootstrap4-toggle.min.js"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
	<script type="text/javascript" src="https://cdn.datatables.net/v/bs4/jszip-2.5.0/dt-1.10.21/af-2.3.5/b-1.6.2/b-colvis-1.6.2/b-flash-1.6.2/b-html5-1.6.2/b-print-1.6.2/cr-1.5.2/fc-3.3.1/fh-3.1.7/kt-2.5.2/r-2.2.5/rg-1.1.2/rr-1.2.7/sc-2.0.2/sp-1.1.1/sl-1.3.1/datatables.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.19.2/axios.js" integrity="sha256-bd8XIKzrtyJ1O5Sh3Xp3GiuMIzWC42ZekvrMMD4GxRg=" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/lodash@4.17.21/lodash.min.js"></script>



	<style>
		[v-cloak] {
			display: none;
		}

		.saldoreal {
			width: 16%;
			height: 50px;
			/* margin-right: 4%;  */
			top: 0px;
			/* position: sticky !important; */
			float: right !important;
		}

		.saldost {
			width: 96%;
			color: #fff;
			background-color: #ff8300 !important;
			/* border-color: #007bff !important; */
		}


		.bd-callout {
			padding: 1.25rem;
			margin-top: 1.25rem;
			margin-bottom: 1.25rem;
			border: 1px solid #eee;
			border-left-width: .25rem;
			border-radius: .25rem
		}

		.bd-callout h4 {
			margin-top: 0;
			margin-bottom: .25rem
		}

		.bd-callout p:last-child {
			margin-bottom: 0
		}

		.bd-callout code {
			border-radius: .25rem
		}

		.bd-callout+.bd-callout {
			margin-top: -.25rem
		}

		.bd-callout-info {
			border-left-color: #5bc0de
		}

		.bd-callout-info h4 {
			color: #5bc0de
		}

		.bd-callout-warning {
			border-left-color: #f0ad4e
		}

		.bd-callout-warning h4 {
			color: #f0ad4e
		}

		.bd-callout-danger {
			border-left-color: #d9534f
		}

		.bd-callout-danger h4 {
			color: #d9534f
		}

		.slow .toggle-group {
			transition: left 0.7s;
			-webkit-transition: left 0.7s;
		}

		.fast .toggle-group {
			transition: left 0.1s;
			-webkit-transition: left 0.1s;
		}

		.quick .toggle-group {
			transition: none;
			-webkit-transition: none;
		}

		html {
			position: relative;
			min-height: 100%;
			font-size: 1rem;
		}

		body {
			/* Margin bottom by footer height */
			margin-bottom: 60px;
		}

		.footer {
			position: absolute;
			bottom: 0;
			width: 100%;
			/* Set the fixed height of the footer here */
			height: 60px;
			line-height: 60px;
			/* Vertically center the text there */
			background-color: #f5f5f5;
		}

		#overlay {
			position: fixed;
			/* Sit on top of the page content */
			display: none;
			/* Hidden by default */
			width: 100%;
			/* Full width (cover the whole page) */
			height: 100%;
			/* Full height (cover the whole page) */
			top: 0;
			left: 0;
			right: 0;
			bottom: 0;
			background-color: rgba(0, 0, 0, 0.5);
			/* Black background with opacity */
			z-index: 2;
			/* Specify a stack order in case you're using a different order for other elements */
			cursor: pointer;
			/* Add a pointer on hover */
		}

		#overlay-content {
			position: absolute;
			top: 50%;
			left: 50%;
			font-size: 50px;
			color: white;
			transform: translate(-50%, -50%);
			-ms-transform: translate(-50%, -50%);
		}




		/* Custom page CSS
		-------------------------------------------------- */
		/* Not required for template or sticky footer method. */

		body>.container {
			padding: 60px 15px 0;
		}

		.footer>.container {
			padding-right: 15px;
			padding-left: 15px;
		}

		i {
			font-size: 21px;
			margin: 3px;
		}

		code {
			font-size: 80%;
		}

		.select2-selection__rendered {
			line-height: 31px !important;
			width: 100%;
		}

		.select2-container .select2-selection--single {
			height: 35px !important;
			width: 100%;
		}

		.select2-selection__arrow {
			height: 34px !important;
			width: 100%;
		}
	</style>






</head>


<body>

	<header>




		<div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modal-label" aria-hidden="true">
			<div class="modal-dialog modal-xl" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="modal-label">Content not loaded</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div id="modal-content" class="modal-body">
						...
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
						<button style="display: none;" id="modal-save-btn" type="button" class="btn btn-primary">Not configured</button>
					</div>
				</div>
			</div>
		</div>

		<nav class="navbar navbar-expand-lg navbar-dark bg-primary text-white border">
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo03" aria-controls="navbarTogglerDemo03" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>
			<a class="navbar-brand" href="#">
				<img width="240" height="55" src="./img/iconsorcio_logo_branca_sem_gradiente.png" alt="" />
			</a>

			<div class="collapse navbar-collapse" id="navbarTogglerDemo03">
				<ul class="navbar-nav mr-auto mt-2 mt-lg-0">
				</ul>

				<ul class="navbar-nav">
					<li class="nav-item dropdown " style="display: flex; align-items: baseline; color: rgba(255,255,255,.75);">

						<p>Tempo restante: <span id="countdown">10:00</span></p> <!-- Cronômetro -->

						<a class="nav-link dropdown-toggle" href="#" id="profile_dropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							<?php echo strlen($username) > 16 ? substr($username, 0, 16) . "..." : $username; ?>
						</a>
						<div class="dropdown-menu dropdown-menu" aria-labelledby="profile_dropdown">
							<a class="dropdown-item" href="#"><img src="./img/ico_sitcon_suporte.png" style="width: 25px;"> Suporte</a>
							<div class="dropdown-divider"></div>
							<a class="dropdown-item" id="showInfo" href="#"><i class="far fa-user-circle"></i>Minha conta</a>
							<a class="dropdown-item" id="showManual" href="#"><i class="fas fa-book-open"></i>Manual</a>
							<a class="dropdown-item" onclick="event.preventDefault();document.getElementById('logout-form').submit();" href="#"><i class="fas fa-sign-out-alt" style="color:red;"></i>Sair</a>
					</li>
			</div>
			</li>
			</ul>

			</div>
		</nav>


		<br>

		<form id="logout-form" action="logout.php" method="POST" style="display: none;"></form>

	</header>

	<main role="main" class="container-fluid">
		<?php if (isset($_SESSION['CdOrigem']) && $_SESSION['CdOrigem'] > 0 && $_SESSION['CdTpUsuario'] == 3 && $_GET['i'] == 176) { ?>
			<div class="dropdown saldoreal sticky-top">
				<button class="btn btn-secondary dropdown-toggle saldost" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					Saldos Disponíveis
				</button>
				<div class="dropdown-menu" aria-labelledby="dropdownMenu2">
					<ul class="list-group">
						<?php
						include "conecta.php";
						$qry_saldo = "	SELECT t.cdsaldo,t.cdteto,t.idgenfat,t.valorTeto,t.valorUnidade,s.vigencia,s.`Desc`,s.cdpref,s.AbtSaldo,sp.cdprocedimento,ep.CdEspecProc
							FROM tbsldgensaldo s 
							INNER JOIN tbsldteto t ON t.cdsaldo = s.cdsaldo
							INNER JOIN tbgenfat g ON g.idgenfat = t.idgenfat
							INNER JOIN tbsldgensaldo_proc sp ON sp.cdsaldo = s.cdsaldo
							AND sp.`status` = 1
							INNER JOIN tbprefeitura pf ON pf.CdPref = s.cdpref
							LEFT JOIN tbespecproc ep ON ep.CdProcedimento = sp.cdprocedimento
							WHERE s.cdpref = $_SESSION[CdOrigem] AND CURDATE() BETWEEN g.dtini AND g.dtfim
							AND s.`status` = 1 AND g.estado = 'A'	AND pf.`Status` = 1
							GROUP BY t.cdteto";
						//echo $qry_saldo;
						$query_saldo = mysqli_query($db, $qry_saldo);
						if (mysqli_num_rows($query_saldo) > 0) {
							$saldo = mysqli_fetch_array($query_saldo);
							$sql_teto = 	"	SELECT IFNULL(SUM(CASE
													WHEN s.AbtSaldo = 'PPI' THEN ac.valorppi 
													WHEN s.AbtSaldo = 'CTR' THEN ac.valor
													END),0) as saldo
									FROM tbsldgensaldo s
									INNER JOIN tbsldteto t ON s.cdsaldo = s.cdsaldo
									INNER JOIN tbsldmovimentacao m ON m.cdteto = t.cdteto AND s.cdsaldo = m.cdsaldo
									INNER JOIN tbsolcons sc on sc.CdSolCons = m.cdsolcons
									LEFT JOIN tbagendacons ac on ac.CdSolCons = sc.CdSolCons
									LEFT JOIN tbfornecedor_mun fm on fm.CdForn = m.cdubs
									WHERE m.`status` = 1 and t.cdteto = " . $saldo['cdteto'];
							//var_dump($sql_saldo); die();
							$query_saldo_consulmo = mysqli_query($db, $sql_teto);

							if (mysqli_num_rows($query_saldo_consulmo) > 0) {
								while ($cdteto = mysqli_fetch_array($query_saldo_consulmo)) {
									$SaldoRes = ($saldo['valorTeto'] - $saldo['valorUnidade']) - $cdteto['saldo'];
									$SaldoRes = number_format($SaldoRes, 2, ',', '.');
									echo '<li class="list-group-item" title="' . $saldo['Desc'] . '"><i class="fas fa-money-bill fa-1x" style="color:green"></i> R$ ' . $SaldoRes . '</li>';
								}
							} else
								echo '<li class="list-group-item">Sem saldo para competÃªncia atual!</li>';
						} else
							echo '<li class="list-group-item">Sem contrato rateio!</li>';
						?>
					</ul>
				</div>
			</div>
		<?php } ?>


		<div id="overlay" style="display:none">
			<div id="overlay-content">
				<div class="spinner-border" style="width: 8rem; height: 8rem;" role="status">
					<span class="sr-only">Loading...</span>
				</div>
			</div>
		</div>



		<?php
		if ($_SESSION['first_access']) {
			include "session/dadosContato.php";
		} else {
			echo  '<ul class="nav nav-pills">';
			include "menu_gestor.php";
			echo '</ul><hr>';





			/*11-02-2015*/
			$query_per = mysqli_query(
				$db,
				"SELECT * FROM tbmultigrupo Where CdUsuario = $_SESSION[CdUsuario]"
			);

			$multigrupo = "";
			if (mysqli_num_rows($query_per) > 0) {
				while ($n = mysqli_fetch_array($query_per)) {
					$multigrupo .= " OR tbitemus.cdgrusuario = '$n[cdgrusuario]' ";
				};
			}

			if (isset($_GET['i']) && $_GET['i'] > 0) {
				$i = $_GET['i'];
				$queryMenu = "SELECT
							tbsubitem.nmsubitem,
							tbitemus.cditem,
							tbitemus.cdsubitem,
							tbitem.nmitem,
							tbitemus.cdgrusuario,
							tbsubitem.arquivo,
							tbsubitem.tutorial
							FROM
							tbitem
							INNER JOIN tbitemus ON tbitem.cditem = tbitemus.cditem
							INNER JOIN tbsubitem ON tbsubitem.cdsubitem = tbitemus.cdsubitem
							WHERE  tbsubitem.cdsubitem = '$i' AND  (tbitemus.cdgrusuario ='$_SESSION[cdgrusuario]'
							$multigrupo )
						";
				//echo $queryMenu;
				$sql = mysqli_query($db, $queryMenu);

				$lin = mysqli_fetch_array($sql);
				$_SESSION['current_page'] = $lin;


				// var_dump($menusBlock);
				// var_dump($bloqueio_menus);
				// var_dump($i);
				if (in_array($i, $bloqueio_menus)) {
		?>
					<div class="alert alert-danger" role="alert">
						<strong>403</strong>
						Acesso bloqueada - entre em contato com o consórcio!
					</div> 
			<?php
				die();
				} else {
					$cdsubitem = $i;
					$nmsubitem = $lin['nmsubitem'];
				}
			}

			?>

			<nav aria-label="breadcrumb">

				<ol class="breadcrumb">
					<li class="breadcrumb-item">
						<a href="?i=">Iní­cio</a>
					</li>
					<?php if ($lin) { ?>
						<li class="breadcrumb-item"><a href="?i=<?= $lin['cdsubitem'] ?>"><?php echo $lin['nmitem']; ?> </a></li>
						<li class="breadcrumb-item active" aria-current="page"><?php echo $lin['nmsubitem']; ?></li>
					<?php }
					if ($lin['tutorial'] != null)
						echo '<li class="ml-auto" aria-current="page"><a class="tutorial" data-src="' . $lin['tutorial'] . '" title="Tutorial ' . $lin['nmitem'] . ' de ' . $lin['nmsubitem'] . '"><button type="button" class="btn btn-outline-danger" style="font-size: 1rem !important; padding: 0.1rem !important;">Tutorial</button></a></li>';
					else

					?>

				</ol>
			</nav>
			<?php if (($_SESSION["CdTpUsuario"] == 1 || $_SESSION['CdTpUsuario'] == 3) && $_GET['i'] == '')
			echo '<div>			
							 		<button type="button" class="btn btn-primary gestor_controle" value="P"><i class="fas fa-chalkboard-teacher"></i>Painel de Avisos</button>
							 		<button type="button" class="btn btn-warning gestor_controle" value="G"><i class="fas fa-chart-bar"></i>Painel de Gestor</button> 
								  </div>';
			?>
			<div class="card">
				<div class="card-body">
					<!-- <div
										id="chat-token"
										data-token="<?php echo $_SESSION['token'] ?>"
									>
										<div id="chat-app"></div>
									</div> -->
					<?php

					if ($msg->hasMessages()) {
						$msg->display();
					}

					if ($lin) {
						include "$lin[arquivo]";

						if ($_SESSION["CdTpUsuario"] == 1)
							escreve_caminho($_GET['i']);
					} else {
						if (($_SESSION["CdTpUsuario"] == 1 || $_SESSION['CdTpUsuario'] == 3) && $_GET['pc'] == 'G') {
							include "Gestor/Ver_gestor.php";
							//include "pginicial_novo.php";
					?>
							<!-- <div class="sticky-top saldoreal"><i class="fas fa-funnel-dollar"></i></div> -->
					<?php
						} else
							include "pginicial_novo.php";
					} ?>
				</div>
			</div>

		<?php

		}
		?>

	</main>

	<br>


	<footer class="footer">
		<div class="container">
			<span class="text-muted">Sitcon © 2020 - Todos os direitos reservados</span>
		</div>
	</footer>

	<script>
		$(document).ready(function() {
			function countdown() { //Cronômetro
				var timer = <?= $_SESSION['exp_length'] ?>;
				var cdusuario = <?= $_SESSION['CdUsuario'] ?>;
				var swalShown = false;
				var interval = setInterval(function() {
					var minutes = Math.floor(timer / 60);
					var seconds = timer % 60;
					var tempo_restante = minutes + ":" + (seconds + '').padStart(2, '0');
					$("#countdown").html(tempo_restante);
					timer--;
					if (timer <= 0) {
						clearInterval(interval);
						sessionStorage.clear();

						$.ajax({
							type: "POST",
							url: "encerraSessao.php",
							// data: "data",
							dataType: "json",
							beforeSend: Swal.fire({
								title: 'Desconectando...',
								icon: 'warning',
								showConfirmButton: false,
							}),
							success: function(response) {
								if (response.success == true) {
									$("#logout-form").submit();
									var urlAtual = window.location.href;
									var novaUrl = urlAtual.substring(0, urlAtual.lastIndexOf("/") + 1) + "frm_login.php?dc=s";
									window.location.href = novaUrl;
								} else {
									$("#logout-form").submit();
									var urlAtual = window.location.href;
									var novaUrl = urlAtual.substring(0, urlAtual.lastIndexOf("/") + 1) + "frm_login.php?dc=s";
									window.location.href = novaUrl;
								}
							}
						});
						$("#logout-form").submit();
						var urlAtual = window.location.href;
						var novaUrl = urlAtual.substring(0, urlAtual.lastIndexOf("/") + 1) + "frm_login.php?dc=s";
						window.location.href = novaUrl;
					}
					if (timer < 60) {
						$('#swal2-title').html(`Sua sessão irá expirar em ${tempo_restante} segundos. Deseja revalidar a sessão?`);
						if (swalShown == false) {
							Swal.fire({
								title: `Sua sessão irá expirar em ${tempo_restante} segundos. Deseja revalidar a sessão?`,
								showDenyButton: true,
								confirmButtonText: 'Revalidar',
								denyButtonText: `Desconectar`,
								reverseButtons: true,
								allowOutsideClick: false,
								allowEscapeKey: false,
								allowEnterKey: false
							}).then((result) => {
								/* Read more about isConfirmed, isDenied below */
								if (result.isConfirmed) {


									$.ajax({
										type: "POST",
										url: "revalidaSessao.php",
										// data: "data",
										dataType: "json",
										beforeSend: Swal.fire({
											title: 'Revalidando...',
											icon: 'warning',
											showConfirmButton: false,
										}),
										complete: Swal.close(),
										success: function(response) {
											if (response.success == true) {
												clearInterval(interval);
												countdown();
												Swal.fire('Revalidado!', '', 'success');
											}

										},
										error: function() {
											$("#logout-form").submit();
											var urlAtual = window.location.href;
											var novaUrl = urlAtual.substring(0, urlAtual.lastIndexOf("/") + 1) + "frm_login.php?dc=s";
											window.location.href = novaUrl;
										}
									});
								} else if (result.isDenied) {
									clearInterval(interval);
									sessionStorage.clear();
									$.ajax({
										type: "POST",
										url: "encerraSessao.php",
										// data: "data",
										dataType: "json",
										beforeSend: Swal.fire({
											title: 'Desconectando...',
											icon: 'warning',
											showConfirmButton: false,
										}),
										success: function(response) {
											if (response.success == true) {
												$("#logout-form").submit();
												var urlAtual = window.location.href;
												var novaUrl = urlAtual.substring(0, urlAtual.lastIndexOf("/") + 1) + "frm_login.php?dc=s";
												window.location.href = novaUrl;
											} else {
												$("#logout-form").submit();
												var urlAtual = window.location.href;
												var novaUrl = urlAtual.substring(0, urlAtual.lastIndexOf("/") + 1) + "frm_login.php?dc=s";
												window.location.href = novaUrl;
											}
										}
									});
									$("#logout-form").submit();
									var urlAtual = window.location.href;
									var novaUrl = urlAtual.substring(0, urlAtual.lastIndexOf("/") + 1) + "frm_login.php?dc=s";
									window.location.href = novaUrl;
								}
							})
							swalShown = true;
						}
						if (timer == 0) {
							$.ajax({
								type: "POST",
								url: "encerraSessao.php",
								// data: "data",
								dataType: "json",
								success: function(response) {
									if (response.success == true) {
										$("#logout-form").submit();
										var urlAtual = window.location.href;
										var novaUrl = urlAtual.substring(0, urlAtual.lastIndexOf("/") + 1) + "frm_login.php?dc=s";
										window.location.href = novaUrl;
									} else {
										$("#logout-form").submit();
										var urlAtual = window.location.href;
										var novaUrl = urlAtual.substring(0, urlAtual.lastIndexOf("/") + 1) + "frm_login.php?dc=s";
										window.location.href = novaUrl;
									}
								}
							});
							clearInterval(interval);
							$("#logout-form").submit();
							var urlAtual = window.location.href;
							var novaUrl = urlAtual.substring(0, urlAtual.lastIndexOf("/") + 1) + "frm_login.php?dc=s";
							window.location.href = novaUrl;
						}
					}
				}, 1000);
			}
			countdown();
		});


		var $videoSrc;
		$(".tutorial").click(function() {
			$videoSrc = $(this).attr("data-src");
			console.log("button clicked " + $videoSrc);
			console.log("modal opened " + $videoSrc);
			$("#modal").modal({
				show: true
			});
			$(".modal-dialog").addClass("modal-lg")
			$("#modal-label").text("Tutorial Iconsórcio");
			$("#modal-content").html('<div class="embed-responsive embed-responsive-16by9"><iframe class="embed-responsive-item" src="" id="video" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen>></iframe></div>');

			$("#video").attr(
				"src",
				$videoSrc + "?amp;showinfo=0&amp;modestbranding=1&amp;autoplay=1"
			);
			$("#video").attr("src", $videoSrc);
		});

		$(document).on('click', '.gestor_controle', function(e) {
			//e.preventDefault();
			var valor_pc = $(this).val();
			console.log(valor_pc);
			window.location.href = "./index.php?i=&pc=" + valor_pc;
		});

		startList = function() {
			if (document.all && document.getElementById) {
				navRoot = document.getElementById("nav");
				for (i = 0; i < navRoot.childNodes.length; i++) {
					node = navRoot.childNodes[i];
					if (node.nodeName == "LI") {
						node.onmouseover = function() {
							this.className += " over";
						}
						node.onmouseout = function() {
							this.className = this.className.replace(" over", "");
						}
					}
				}
			}
		}
		window.onload = startList;

		function valida_data(value) {
			//contando chars
			if (value.length != 10) return false;
			// verificando data
			var data = value;
			var dia = data.substr(0, 2);
			var barra1 = data.substr(2, 1);
			var mes = data.substr(3, 2);
			var barra2 = data.substr(5, 1);
			var ano = data.substr(6, 4);

			if (isNaN(dia) && isNaN(mes) && isNaN(ano)) return true;

			var hoje = new Date();
			var dia_a = hoje.getDate();
			if (dia_a < 10) dia_a = '0' + dia_a;
			var mes_a = hoje.getMonth() + 1; //soma se 1 devido o mes comeÃ§ar com 0
			if (mes_a < 10) mes_a = '0' + mes_a;
			var ano_a = hoje.getFullYear();
			//verifica se a data do agendamento Ã© maior ou igual a data atual
			//alert(ano_a+mes_a+dia_a+'-'+ano+mes+dia);

			if (ano < ano_a) {
				alert('A data informada deve ser maior ou igual a data atual: ' + dia_a + '/' + mes_a + '/' + ano_a);
				return false;
			} else {
				if (ano == ano_a) {
					if (mes < mes_a) {
						alert('A data informada deve ser maior ou igual a data atual: ' + dia_a + '/' + mes_a + '/' + ano_a);
						return false;
					} else {
						if (mes == mes_a) {
							if (dia_a > dia) {
								alert('A data informada deve ser maior ou igual a data atual: ' + dia_a + '/' + mes_a + '/' + ano_a);
								return false;
							}
						}
					}
				}
			}

			if (data.length != 10 || barra1 != "/" || barra2 != "/" || isNaN(dia) || isNaN(mes) || isNaN(ano) || dia > 31 || mes > 12) return false;
			if ((mes == 4 || mes == 6 || mes == 9 || mes == 11) && dia == 31) return false;
			if (mes == 2 && (dia > 29 || (dia == 29 && ano % 4 != 0))) return false;
			if (ano < 1890) return false;
			return true;
		}

		function valida_horario(value) {
			//contando chars
			if (value.length != 5) return false;
			// verificando hora
			var horario = value;
			var hora = horario.substr(0, 2);
			var separador = horario.substr(2, 1);
			var minuto = horario.substr(3, 2);

			if (isNaN(hora) && isNaN(minuto)) return true;

			if (horario.length != 5 || separador != ":" || isNaN(hora) || isNaN(minuto) || hora > 23 || minuto > 59) return false;
			return true;
		}

		function abrirpop(url, nome, w, h, s) {
			janela = window.open(url, nome, 'width=' + w + ',height=' + h + ',top=1,left=1,scrollbars=' + s + ',toolbar=no,menubar=no,status=no,location=no,resizable=no');
			janela.focus();
		}
		//caio 2020-06-24

		var userData = <?php echo json_encode($_SESSION['userData']) ?>;
		var sessionData = <?php echo json_encode($_SESSION['sessionData']) ?>;

		tinymce.init({
			selector: '.tinymce',
			language: 'pt_BR',
			plugins: "code table",

		});

		$.datepicker.setDefaults($.datepicker.regional["pt-BR"]);
		const Toast = Swal.mixin({
			toast: true,
			position: 'top-end',
			showConfirmButton: false,
			timer: 3000,
			timerProgressBar: true,
			onOpen: (toast) => {
				toast.addEventListener('mouseenter', Swal.stopTimer)
				toast.addEventListener('mouseleave', Swal.resumeTimer)
			}
		})

		function titleize(sentence) {
			if (!sentence.split) return sentence;
			var _titleizeWord = function(string) {
					return string.charAt(0).toUpperCase() + string.slice(1).toLowerCase();
				},
				result = [];
			sentence.split(" ").forEach(function(w) {
				result.push(_titleizeWord(w));
			});
			return result.join(" ");
		}

		function redirectOnConfirm(url, title, text, icon = 'warning', confirmButtonText = "Sim", cancelButtonText = "Cancelar") {
			Swal.fire({
				title: title,
				text: text,
				icon: icon,
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText,
				cancelButtonText
			}).then((result) => {
				if (result.value) {
					window.location.href = url
				}
			})
		}

		$(".telefone").mask("(99)9999-9999");
		$(".celular").mask("(99)99999-9999");
		$(".cpf").mask("999.999.999-99");
		$(".cnpj").mask("99.999.999.9999-99");
		$(".cdsus").mask("99.99.99.999.9");
		$(".date_format").mask("99/99/9999");
		$(".datetime_format").mask("99/99/9999 99:99:99");
		$(".cep").mask("99999-999");
		$(".certidao_de_nascimento").mask("999999 99 99 9999 9 99999 999 9999999 99");

		$('#modal').on('hidden.bs.modal', function(e) {
			$(".modal-body").html("...")
			$(".modal-title").html("No content loaded");
			$(".modal-dialog").addClass("modal-xl")
			console.log("closed")
		})
		$('#modal').on('show.bs.modal', function(e) {
			$(".modal-body").html("...")
			$(".modal-title").html("Carregando informaçoes...");
		});

		$("#showInfo").click(function() {
			var html =
				'<div class="card">' +
				'<div class="card-body">' +
				'					<h4 class="card-title">' + userData.name + '</h4>' +
				'					<h6 class="card-subtitle text-muted"><span class="badge badge-success">Online</span></h6>' +
				'				  </div>' +
				'				  ' +
				'				  <div class="card-body">' +
				'					' +
				'					<dl class="row">' +
				'						<dt class="col-sm-3">Grupo</dt>' +
				'						<dd class="col-sm-9 text-primary"><strong>' + userData.group + '</strong></dd>' +
				'						<dt class="col-sm-3">Email</dt>' +
				'						<dd class="col-sm-9">' + (userData.email ? userData.email : 'Não definido') + '</dd>' +
				'					</dl>' +
				'' +
				'					<hr>' +
				'<dl class="row">' +
				'<dt class="col-sm-9">Último login</dt>' +
				'<dd id="last_sign_in" class="col-sm-3">' + userData.session_info.last_sign_in + '</dd>' +
				'<dt class="col-sm-9">Sua sessão irá expirar em</dt>' +
				'<dd id="session_expires_at" class="col-sm-3">00:00</dd>' +
				'					</dl>' +
				'				  </div>' +
				'				</div>';

			$("#modal").modal({
				show: true
			});
			$(".modal-dialog").removeClass("modal-xl")
			$("#modal-content").html(html)
			$("#modal-label").html("Informações de conta");


		});
		
	</script>
	<script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
	<script src="Gestor/js/chartjs-gauge.js"></script>
	<script src=Gestor/js/graphicFilters.js></script>
	<script src=Gestor/js/graphic.js></script>
	<script src=Gestor/js/main.js></script>

	<script type="text/javascript">
		window._mfq = window._mfq || [];
		(function() {
			var mf = document.createElement("script");
			mf.type = "text/javascript"; mf.defer = true;
			mf.src = "//cdn.mouseflow.com/projects/4918c552-8ebe-422c-b2f9-4d68cca86f9a.js";
			document.getElementsByTagName("head")[0].appendChild(mf);
		 })();
	</script>
</body>

</html>
<?php mysqli_close($db);