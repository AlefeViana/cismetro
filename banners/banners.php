<link rel="stylesheet" type="text/css" href="css/banners.css">
<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
<script type="text/javascript" src="banners/banners.js"></script>
<?php 
	$cdTpUsuario_b 	= $_SESSION["CdTpUsuario"];
	$cdUsuario_b 	= $_SESSION["CdUsuario"];
	$hoje_b 		= date('Y-m-d');

	$query_banner = "SELECT
						tbbanners.cdBanner,tbbanners.titulo,tbbanners.corpo,tbbanners.tipo
					FROM tbbannersctrl
					INNER JOIN tbbanners ON tbbannersctrl.cdBanner = tbbanners.cdBanner
					WHERE
						tbbanners.`status` = 1
					AND tbbannersctrl.`status` = 1
					AND tbbannersctrl.cdTpUsuario = $cdTpUsuario_b
					AND tbbannersctrl.dataIni <= '$hoje_b'
					AND tbbannersctrl.dataExp >= '$hoje_b'
					AND tbbanners.cdBanner NOT IN (
						SELECT
							tbbannersalc.cdBanner
						FROM
							tbbannersalc
						WHERE
							tbbannersalc.cdUsuario = $cdUsuario_b)";
	//echo $query_banner;
	$select_banner = mysqli_query($db,$query_banner);

	$qtd_banners = mysqli_num_rows($select_banner);

	if ($qtd_banners) {
		echo '<div id="c" class="modal">
				<div class="data">
					<a href="#c" title="Fechar" id="fechar" class="fechar">x</a>
					<div class="data_limit">
						<ul class="banners">';
					while ($dado = mysqli_fetch_array($select_banner,MYSQLI_ASSOC)) {
						echo '<li data-banner="'.$dado['cdBanner'].'" data-tipo="'.$dado['tipo'].'"><div class="blocos">';
						echo utf8_decode($dado['corpo']);
						echo '</div></li>';
					}
					echo'</ul>
						</div>
						<input type="button" class="btn_desejo" data-btnbanner="" name="" value="Tenho interesse">
						<input type="button" class="btn_nao" data-btnbanner="" name="" value="Não tenho interesse">
						<i id="carrega" class="fa fa-spinner fa-pulse fa-fw"></i>
					</div>
				</div>';
	}